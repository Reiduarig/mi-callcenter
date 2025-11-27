<?php

namespace App\Services;

use App\Actions\CreateAbsence;
use App\Actions\UpdateAbsence;
use App\DataTransferObjects\CreateAbsenceData;
use App\DataTransferObjects\UpdateAbsenceData;
use App\Enums\AbsenceStatus;
use App\Models\Absence;
use App\Repositories\Contracts\AbsenceRepositoryInterface;
use App\Models\User;

class AbsenceService
{
    public function __construct(
        private CreateAbsence $createAbsence,
        private UpdateAbsence $updateAbsence,
        private AbsenceRepositoryInterface $absenceRepository
    ) {}

    /**
     * Crea una nueva ausencia aplicando reglas de negocio según el rol del usuario
     */
    public function createAbsence(
        int $userId,
        string $type,
        string $startDate,
        string $endDate,
        string $status,
        User $currentUser
    ): Absence {
        // Si es agente, forzar su propio ID y estado pending
        if ($currentUser->hasRole('agente')) {
            $userId = $currentUser->id;
            $status = AbsenceStatus::Pending->value;
        }

        $data = CreateAbsenceData::fromArray([
            'user_id' => $userId,
            'type' => $type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status,
        ]);

        return $this->createAbsence->execute($data);
    }

    /**
     * Actualiza una ausencia existente aplicando reglas de negocio según el rol
     */
    public function updateAbsence(
        Absence $absence,
        int $userId,
        string $type,
        string $startDate,
        string $endDate,
        string $status,
        User $currentUser
    ): Absence {
        // Si es agente, forzar su propio ID y estado pending
        if ($currentUser->hasRole('agente')) {
            $userId = $currentUser->id;
            $status = AbsenceStatus::Pending->value;
        }

        $data = UpdateAbsenceData::fromArray([
            'user_id' => $userId,
            'type' => $type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status,
        ]);

        return $this->updateAbsence->execute($absence, $data);
    }

    /**
     * Verifica si un usuario tiene ausencia en una fecha
     */
    public function hasAbsence(User $user, string $date): bool
    {
        return $this->absenceRepository->hasApprovedAbsenceOnDate($user->id, $date);
    }

    /**
     * Obtiene todas las ausencias de un usuario
     */
    public function getAbsences(User $user)
    {
        return $this->absenceRepository->getByUser($user->id);
    }
}
