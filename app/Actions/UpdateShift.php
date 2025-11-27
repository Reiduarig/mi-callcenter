<?php

namespace App\Actions;

use App\DataTransferObjects\UpdateShiftData;
use App\Exceptions\ValidationException;
use App\Models\Shift;
use App\Services\ShiftValidationService;

class UpdateShift
{
    public function __construct(
        private ShiftValidationService $validationService
    ) {}

    public function execute(Shift $shift, UpdateShiftData $data): Shift
    {
        $userId = $data->userId ?? $shift->user_id;
        $date = $data->date?->format('Y-m-d') ?? $shift->date->format('Y-m-d');
        $startTime = $data->startTime ?? $shift->start_time;
        $endTime = $data->endTime ?? $shift->end_time;

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
                throw new ValidationException($errors);
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
