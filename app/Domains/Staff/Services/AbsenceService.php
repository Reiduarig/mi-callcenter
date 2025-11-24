<?php
namespace App\Domains\Staff\Services;

use App\Domains\Staff\Models\Absence;
use App\Domains\Staff\Models\Employee;

class AbsenceService
{
    /**
     * Verifica si un empleado tiene ausencia en una fecha.
     */
    public function hasAbsence(Employee $employee, string $date): bool
    {
        return Absence::where('employee_id', $employee->id)
                      ->where('start_date', '<=', $date)
                      ->where('end_date', '>=', $date)
                      ->where('status', 'approved')
                      ->exists();
    }

    /**
     * Obtiene todas las ausencias de un empleado.
     */
    public function getAbsences(Employee $employee)
    {
        return $employee->absences()->orderBy('start_date')->get();
    }
}