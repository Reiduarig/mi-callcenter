<?php

namespace App\Actions;

use App\Models\Absence;

class DeleteAbsence
{
    public function execute(Absence $absence): bool
    {
        // Si la ausencia estaba aprobada y consumía días de vacaciones, restaurar los días
        if ($absence->status->isApproved() && $absence->type->consumesVacationDays()) {
            $user = $absence->user;
            $days = $absence->durationInDays();
            $user->decrement('used_vacation_days', $days);
        }

        return $absence->delete();
    }
}
