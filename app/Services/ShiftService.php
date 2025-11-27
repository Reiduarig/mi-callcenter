<?php

namespace App\Services;

use App\Actions\CreateShift;
use App\Actions\UpdateShift;
use App\DataTransferObjects\CreateShiftData;
use App\DataTransferObjects\UpdateShiftData;
use App\Models\Shift;
use App\Models\ShiftTemplate;
use App\Repositories\Contracts\ShiftRepositoryInterface;
use App\Models\User;

class ShiftService
{
    public function __construct(
        private CreateShift $createShift,
        private UpdateShift $updateShift,
        private ShiftAssignmentService $assignmentService,
        private ShiftRepositoryInterface $shiftRepository
    ) {}

    /**
     * Crea un turno individual
     */
    public function createShift(
        int $userId,
        ?int $shiftTemplateId,
        bool $isCustom,
        string $date,
        string $startTime,
        string $endTime
    ): Shift {
        $data = CreateShiftData::fromArray([
            'user_id' => $userId,
            'shift_template_id' => $shiftTemplateId,
            'is_custom' => $isCustom,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return $this->createShift->execute($data);
    }

    /**
     * Actualiza un turno existente
     */
    public function updateShift(
        Shift $shift,
        int $userId,
        ?int $shiftTemplateId,
        bool $isCustom,
        string $date,
        string $startTime,
        string $endTime
    ): Shift {
        $data = UpdateShiftData::fromArray([
            'user_id' => $userId,
            'shift_template_id' => $shiftTemplateId,
            'is_custom' => $isCustom,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return $this->updateShift->execute($shift, $data);
    }

    /**
     * Crea turnos en masa para un rango de fechas usando una plantilla
     */
    public function createShiftsInRange(
        User $user,
        ShiftTemplate $template,
        string $startDate,
        string $endDate,
        bool $excludeWeekends = true
    ): array {
        return $this->assignmentService->assignTemplate(
            employee: $user,
            template: $template,
            startDate: $startDate,
            endDate: $endDate,
            excludeWeekends: $excludeWeekends
        );
    }

    /**
     * Obtiene todos los turnos de un usuario en un rango de fechas
     */
    public function getShifts(User $user, string $startDate, string $endDate)
    {
        return $this->shiftRepository->getByUserAndDateRange($user->id, $startDate, $endDate);
    }
}
