<?php

namespace App\Actions;

use App\DataTransferObjects\ApprovalData;
use App\Enums\AbsenceStatus;
use App\Exceptions\InvalidAbsenceStatusException;
use App\Models\Absence;
use App\Models\AuditLog;

class RejectAbsence
{
    public function execute(Absence $absence, ?ApprovalData $approvalData = null): Absence
    {
        if (! $absence->status->isPending()) {
            throw new InvalidAbsenceStatusException(
                absence: $absence,
                expectedStatus: AbsenceStatus::Pending,
                action: 'rechazar'
            );
        }

        $oldStatus = $absence->status;

        $absence->update([
            'status' => AbsenceStatus::Rejected,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $approvalData?->getNotes(),
        ]);

        // Registrar acción en auditoría
        AuditLog::log('rejected', $absence, ['status' => $oldStatus->value], ['status' => AbsenceStatus::Rejected->value, 'approved_by' => auth()->id()]);

        return $absence->fresh();
    }
}
