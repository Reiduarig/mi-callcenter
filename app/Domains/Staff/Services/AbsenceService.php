<?php

namespace App\Domains\Staff\Services;

use App\Domains\Staff\Models\Absence;
use App\Models\User;

class AbsenceService
{
    /**
     * Verifica si un usuario tiene ausencia en una fecha.
     */
    public function hasAbsence(User $user, string $date): bool
    {
        return Absence::where('user_id', $user->id)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where('status', 'approved')
            ->exists();
    }

    /**
     * Obtiene todas las ausencias de un usuario.
     */
    public function getAbsences(User $user)
    {
        return $user->absences()->orderBy('start_date')->get();
    }
}
