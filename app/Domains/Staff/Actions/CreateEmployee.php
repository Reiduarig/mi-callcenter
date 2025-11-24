<?php
namespace App\Domains\Staff\Actions;

use App\Domains\Staff\Models\Employee;

class CreateEmployee
{
    public function execute(array $data): Employee
    {
        return Employee::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'employee_code' => $data['employee_code'] ?? strtoupper(\Illuminate\Support\Str::random(6)),
            'position' => $data['position'] ?? null,
            'hired_at' => $data['hired_at'] ?? now(),
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}