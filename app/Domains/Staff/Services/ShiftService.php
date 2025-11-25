<?php

namespace App\Domains\Staff\Services;

use App\Domains\Staff\Models\Shift;
use App\Models\User;

class ShiftService
{
    /**
     * Genera un turno para un usuario.
     */
    public function assignShift(User $user, string $date, string $startTime, string $endTime): Shift
    {
        return Shift::create([
            'user_id' => $user->id,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime
        ]);
    }

    /**
     * Obtiene todos los turnos de un usuario en un rango de fechas.
     */
    public function getShifts(User $user, string $startDate, string $endDate)
    {
        return Shift::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();
    }
}
