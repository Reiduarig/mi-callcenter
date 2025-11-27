<?php

namespace App\Domains\Staff\Actions;

use App\Domains\Staff\DataTransferObjects\CreateAbsenceData;
use App\Domains\Staff\Enums\AbsenceStatus;
use App\Domains\Staff\Exceptions\InsufficientVacationDaysException;
use App\Domains\Staff\Exceptions\ValidationException;
use App\Domains\Staff\Models\Absence;
use App\Domains\Staff\Repositories\Contracts\AbsenceRepositoryInterface;
use App\Domains\Staff\Services\ShiftValidationService;
use App\Models\User;

class CreateAbsence
{
    public function __construct(
        private ShiftValidationService $validationService,
        private AbsenceRepositoryInterface $absenceRepository
    ) {}

    public function execute(CreateAbsenceData $data): Absence
    {
        // Validar la ausencia antes de crearla
        $errors = $this->validationService->validateAbsence(
            employeeId: $data->userId,
            startDate: $data->startDate->format('Y-m-d'),
            endDate: $data->endDate->format('Y-m-d')
        );

        if (! empty($errors)) {
            throw new ValidationException($errors);
        }

        // Crear la ausencia
        $absence = $this->absenceRepository->create($data->toArray());

        // Si se crea con estado Aprobado y consume días de vacaciones, actualizar contador
        if ($absence->status === AbsenceStatus::Approved && $absence->type->consumesVacationDays()) {
            $user = User::findOrFail($absence->user_id);
            $days = $absence->durationInDays();

            if ($user->availableVacationDays() < $days) {
                // Eliminar la ausencia recién creada y lanzar excepción
                $absence->delete();
                throw new InsufficientVacationDaysException(
                    user: $user,
                    requestedDays: $days,
                    availableDays: $user->availableVacationDays()
                );
            }

            $user->increment('used_vacation_days', $days);
        }

        return $absence->fresh();
    }
}
