<?php 

namespace App\Domains\Staff\Actions;

use App\Domains\Staff\Models\Shift;

class CreateShift
{
    public function execute(array $data): Shift
    {
        return Shift::create([
            'employee_id' => $data['employee_id'],
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'status' => $data['status'] ?? 'scheduled',
        ]);
    }
}