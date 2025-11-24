<?php 

namespace App\Domains\Staff\Actions;

use App\Domains\Staff\Models\Employee;

class DeleteEmployee
{
    public function execute(Employee $employee): void
    {
        $employee->delete();
    }
}