<?php

namespace App\Domains\Staff\Actions;

use App\Domains\Staff\Models\Absence;
use App\Domains\Staff\Models\AuditLog;

class ApproveAbsence
{
    public function execute(Absence $absence, ?string $notes = null): Absence
    {
        if ($absence->status !== 'pending') {
            throw new \Exception('Solo se pueden aprobar ausencias pendientes.');
        }

        $oldStatus = $absence->status;

        $absence->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $notes,
        ]);

        // Actualizar días de vacaciones usados si es tipo vacation
        if ($absence->type === 'vacation') {
            $employee = $absence->employee;
            $days = $absence->durationInDays();

            if ($employee->availableVacationDays() < $days) {
                throw new \Exception("El empleado no tiene suficientes días de vacaciones disponibles. Disponibles: {$employee->availableVacationDays()}, Solicitados: {$days}");
            }

            $employee->increment('used_vacation_days', $days);
        }

        // Registrar acción en auditoría
        AuditLog::log('approved', $absence, ['status' => $oldStatus], ['status' => 'approved', 'approved_by' => auth()->id()]);

        return $absence->fresh();
    }
}
