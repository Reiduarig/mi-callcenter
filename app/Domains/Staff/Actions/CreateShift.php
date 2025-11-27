<?php

namespace App\Domains\Staff\Actions;

use App\Domains\Staff\DataTransferObjects\CreateShiftData;
use App\Domains\Staff\Exceptions\ValidationException;
use App\Domains\Staff\Models\Shift;
use App\Domains\Staff\Repositories\Contracts\ShiftRepositoryInterface;
use App\Domains\Staff\Services\ShiftValidationService;

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
