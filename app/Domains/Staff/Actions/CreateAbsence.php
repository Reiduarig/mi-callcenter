<?php

namespace App\Domains\Staff\Actions;

use App\Domains\Staff\Models\Absence;

class CreateAbsence
{
    public function execute(array $data): Absence
    {
        return Absence::create([
            'employee_id' => $data['employee_id'],
            'type' => $data['type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => $data['status'] ?? 'approved',
        ]);
    }
}