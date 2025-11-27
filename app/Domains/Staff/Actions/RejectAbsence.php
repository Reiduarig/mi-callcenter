<?php

namespace App\Domains\Staff\Actions;

use App\Domains\Staff\DataTransferObjects\ApprovalData;
use App\Domains\Staff\Enums\AbsenceStatus;
use App\Domains\Staff\Exceptions\InvalidAbsenceStatusException;
use App\Domains\Staff\Models\Absence;
use App\Domains\Staff\Models\AuditLog;

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
