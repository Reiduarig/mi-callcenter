<?php

namespace App\Domains\Staff\Actions;

use App\Domains\Staff\Models\Absence;
use App\Domains\Staff\Models\AuditLog;

class RejectAbsence
{
    public function execute(Absence $absence, ?string $notes = null): Absence
    {
        if ($absence->status !== 'pending') {
            throw new \Exception('Solo se pueden rechazar ausencias pendientes.');
        }

        $oldStatus = $absence->status;

        $absence->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $notes,
        ]);

        // Registrar acción en auditoría
        AuditLog::log('rejected', $absence, ['status' => $oldStatus], ['status' => 'rejected', 'approved_by' => auth()->id()]);

        return $absence->fresh();
    }
}
