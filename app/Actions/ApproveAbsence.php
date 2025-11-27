<?php

namespace App\Actions;

use App\DataTransferObjects\ApprovalData;
use App\Enums\AbsenceStatus;
use App\Exceptions\InsufficientVacationDaysException;
use App\Exceptions\InvalidAbsenceStatusException;
use App\Models\Absence;
use App\Models\AuditLog;

class ApproveAbsence
{
    public function execute(Absence $absence, ?ApprovalData $approvalData = null): Absence
    {
        if (! $absence->status->isPending()) {
            throw new InvalidAbsenceStatusException(
                absence: $absence,
                expectedStatus: AbsenceStatus::Pending,
                action: 'aprobar'
            );
        }

        $oldStatus = $absence->status;

        $absence->update([
            'status' => AbsenceStatus::Approved,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $approvalData?->getNotes(),
        ]);

        // Actualizar días de vacaciones usados si es tipo vacation
        if ($absence->type->consumesVacationDays()) {
            $user = $absence->user;
            $days = $absence->durationInDays();

            if ($user->availableVacationDays() < $days) {
                throw new InsufficientVacationDaysException(
                    user: $user,
                    requestedDays: $days,
                    availableDays: $user->availableVacationDays()
                );
            }

            $user->increment('used_vacation_days', $days);
        }

        // Registrar acción en auditoría
        AuditLog::log('approved', $absence, ['status' => $oldStatus->value], ['status' => AbsenceStatus::Approved->value, 'approved_by' => auth()->id()]);

        return $absence->fresh();
    }
}
