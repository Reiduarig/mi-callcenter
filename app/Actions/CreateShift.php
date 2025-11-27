<?php

namespace App\Actions;

use App\DataTransferObjects\CreateShiftData;
use App\Exceptions\ValidationException;
use App\Models\Shift;
use App\Repositories\Contracts\ShiftRepositoryInterface;
use App\Services\ShiftValidationService;

class CreateShift
{
    public function __construct(
        private ShiftValidationService $validationService,
        private ShiftRepositoryInterface $shiftRepository
    ) {}

    public function execute(CreateShiftData $data): Shift
    {
        // Validar el turno antes de crearlo
        $errors = $this->validationService->validateShift(
            employeeId: $data->userId,
            date: $data->date->format('Y-m-d'),
            startTime: $data->startTime,
            endTime: $data->endTime
        );

        if (! empty($errors)) {
            throw new ValidationException($errors);
        }

        return $this->shiftRepository->create($data->toArray());
    }
}
