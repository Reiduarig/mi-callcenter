<?php
namespace App\Domains\Staff\Actions;

use App\Domains\Staff\Models\Employee;

class UpdateEmployee
{
    public function execute(Employee $employee, array $data): Employee
    {
        $employee->update([
            'first_name' => $data['first_name'] ?? $employee->first_name,
            'last_name' => $data['last_name'] ?? $employee->last_name,
            'email' => $data['email'] ?? $employee->email,
            'employee_code' => $data['employee_code'] ?? $employee->employee_code,
            'position' => $data['position'] ?? $employee->position,
            'hired_at' => $data['hired_at'] ?? $employee->hired_at,
            'is_active' => $data['is_active'] ?? $employee->is_active,
        ]);

        return $employee;
    }
}