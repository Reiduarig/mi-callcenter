<?php

namespace App\Domains\Staff\Actions;

use App\Domains\Staff\Models\Absence;
use App\Domains\Staff\Services\ShiftValidationService;

class UpdateAbsence
{
    public function __construct(
        private ShiftValidationService $validationService
    ) {}

    public function execute(Absence $absence, array $data): Absence
    {
        $userId = $data['user_id'] ?? $absence->user_id;
        $startDate = $data['start_date'] ?? $absence->start_date->format('Y-m-d');
        $endDate = $data['end_date'] ?? $absence->end_date->format('Y-m-d');
        $oldStatus = $absence->status;
        $newStatus = $data['status'] ?? $absence->status;

        // Validar solo si se modifican fechas o empleado
        if (
            $userId != $absence->user_id ||
            $startDate != $absence->start_date->format('Y-m-d') ||
            $endDate != $absence->end_date->format('Y-m-d')
        ) {
            $errors = $this->validationService->validateAbsence(
                employeeId: $userId,
                startDate: $startDate,
                endDate: $endDate,
                excludeAbsenceId: $absence->id
            );

            if (! empty($errors)) {
                throw new \Exception(implode(' ', $errors));
            }
        }

        // Si se aprueba o rechaza, agregar campos de aprobación
        $updateData = [
            'user_id' => $userId,
            'type' => $data['type'] ?? $absence->type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $newStatus,
        ];

        if ($oldStatus === 'pending' && $newStatus === 'approved' && auth()->check()) {
            $updateData['approved_by'] = auth()->id();
            $updateData['approved_at'] = now();
            $updateData['approval_notes'] = $data['approval_notes'] ?? null;

            // Actualizar días de vacaciones usados si es tipo vacation
            if (($data['type'] ?? $absence->type) === 'vacation') {
                $employee = $absence->employee;
                $days = $absence->durationInDays();
                $employee->increment('used_vacation_days', $days);
            }
        } elseif ($oldStatus === 'pending' && $newStatus === 'rejected' && auth()->check()) {
            $updateData['approved_by'] = auth()->id();
            $updateData['approved_at'] = now();
            $updateData['approval_notes'] = $data['approval_notes'] ?? null;
        }

        $absence->update($updateData);

        return $absence->fresh();
    }
}
