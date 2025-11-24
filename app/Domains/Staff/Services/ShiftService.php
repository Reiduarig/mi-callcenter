<?php 

namespace App\Domains\Staff\Services;

use App\Domains\Staff\Models\Shift;
use App\Domains\Staff\Models\Employee;
use Carbon\Carbon;

class ShiftService
{
    /**
     * Genera un turno para un empleado.
     */
    public function assignShift(Employee $employee, string $date, string $startTime, string $endTime): Shift
    {
        return Shift::create([
            'employee_id' => $employee->id,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'scheduled',
        ]);
    }

    /**
     * Obtiene todos los turnos de un empleado en un rango de fechas.
     */
    public function getShifts(Employee $employee, string $startDate, string $endDate)
    {
        return Shift::where('employee_id', $employee->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->orderBy('date')
                    ->get();
    }
}