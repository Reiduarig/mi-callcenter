<?php

namespace App\Actions;

use App\DataTransferObjects\UpdateAbsenceData;
use App\Exceptions\ValidationException;
use App\Models\Absence;
use App\Services\ShiftValidationService;

class UpdateAbsence
{
    public function __construct(
        private ShiftValidationService $validationService
    ) {}

    public function execute(Absence $absence, UpdateAbsenceData $data): Absence
    {
        $userId = $data->userId ?? $absence->user_id;
        $startDate = $data->startDate?->format('Y-m-d') ?? $absence->start_date->format('Y-m-d');
        $endDate = $data->endDate?->format('Y-m-d') ?? $absence->end_date->format('Y-m-d');
        $oldStatus = $absence->status;
        $newStatus = $data->status ?? $absence->status;
        $oldType = $absence->type;
        $newType = $data->type ?? $absence->type;

        // Calcular días antiguos y nuevos para ajustar vacaciones
        $oldDays = $absence->durationInDays();
        $newDays = \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1;

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
                throw new ValidationException($errors);
            }
        }

        // Si se aprueba o rechaza, agregar campos de aprobación
        $updateData = [
            'user_id' => $userId,
            'type' => $newType,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $newStatus,
        ];

        if ($oldStatus->isPending() && $newStatus->isApproved() && auth()->check()) {
            $updateData['approved_by'] = auth()->id();
            $updateData['approved_at'] = now();
            $updateData['approval_notes'] = $data->approvalNotes;

            // Incrementar días de vacaciones usados si es tipo vacation
            if ($newType->consumesVacationDays()) {
                $user = $absence->user;
                $user->increment('used_vacation_days', $newDays);
            }
        } elseif ($oldStatus->isPending() && $newStatus->isRejected() && auth()->check()) {
            $updateData['approved_by'] = auth()->id();
            $updateData['approved_at'] = now();
            $updateData['approval_notes'] = $data->approvalNotes;
        } elseif ($oldStatus->isApproved() && $newStatus->isApproved()) {
            // Si ya está aprobada y se editan fechas o tipo, ajustar días
            $user = $absence->user;

            // Si cambió de vacation a otro tipo, restar días antiguos
            if ($oldType->consumesVacationDays() && ! $newType->consumesVacationDays()) {
                $user->decrement('used_vacation_days', $oldDays);
            }
            // Si cambió de otro tipo a vacation, sumar días nuevos
            elseif (! $oldType->consumesVacationDays() && $newType->consumesVacationDays()) {
                $user->increment('used_vacation_days', $newDays);
            }
            // Si ambos son vacation y cambiaron los días, ajustar diferencia
            elseif ($oldType->consumesVacationDays() && $newType->consumesVacationDays() && $oldDays !== $newDays) {
                $difference = $newDays - $oldDays;
                if ($difference > 0) {
                    $user->increment('used_vacation_days', $difference);
                } else {
                    $user->decrement('used_vacation_days', abs($difference));
                }
            }
        } elseif ($oldStatus->isApproved() && ! $newStatus->isApproved()) {
            // Si se cambia de aprobada a pendiente/rechazada, restar días si era vacation
            if ($oldType->consumesVacationDays()) {
                $user = $absence->user;
                $user->decrement('used_vacation_days', $oldDays);
            }
        }

        $absence->update($updateData);

        return $absence->fresh();
    }
}
