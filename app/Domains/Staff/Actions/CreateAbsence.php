<?php

namespace App\Domains\Staff\Actions;

use App\Domains\Staff\Models\Absence;
use App\Domains\Staff\Services\ShiftValidationService;

class CreateAbsence
{
    public function __construct(
        private ShiftValidationService $validationService
    ) {}

    public function execute(array $data): Absence
    {
        // Validar la ausencia antes de crearla
        $errors = $this->validationService->validateAbsence(
            employeeId: $data['user_id'],
            startDate: $data['start_date'],
            endDate: $data['end_date']
        );

        if (! empty($errors)) {
            throw new \Exception(implode(' ', $errors));
        }

        return Absence::create([
            'user_id' => $data['user_id'],
            'type' => $data['type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => $data['status'] ?? 'pending',
        ]);
    }
}
