<?php

namespace App\Domains\Staff\Actions;

use App\Domains\Staff\Models\Shift;
use App\Domains\Staff\Services\ShiftValidationService;

class UpdateShift
{
    public function __construct(
        private ShiftValidationService $validationService
    ) {}

    public function execute(Shift $shift, array $data): Shift
    {
        $userId = $data['user_id'] ?? $shift->user_id;
        $date = $data['date'] ?? $shift->date->format('Y-m-d');
        $startTime = $data['start_time'] ?? $shift->start_time;
        $endTime = $data['end_time'] ?? $shift->end_time;

        // Validar solo si se modifican datos relevantes
        if (
            $userId != $shift->user_id ||
            $date != $shift->date->format('Y-m-d') ||
            $startTime != $shift->start_time ||
            $endTime != $shift->end_time
        ) {
            $errors = $this->validationService->validateShift(
                employeeId: $userId,
                date: $date,
                startTime: $startTime,
                endTime: $endTime,
                excludeShiftId: $shift->id
            );

            if (! empty($errors)) {
                throw new \Exception(implode(' ', $errors));
            }
        }

        $shift->update([
            'user_id' => $userId,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return $shift->fresh();
    }
}
