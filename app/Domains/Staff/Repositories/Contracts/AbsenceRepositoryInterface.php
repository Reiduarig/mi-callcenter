<?php

namespace App\Domains\Staff\Repositories\Contracts;

use App\Domains\Staff\Enums\AbsenceStatus;
use App\Domains\Staff\Models\Absence;
use Illuminate\Database\Eloquent\Collection;

interface AbsenceRepositoryInterface
{
    /**
     * Crea una nueva ausencia
     */
    public function create(array $data): Absence;

    /**
     * Encuentra una ausencia por ID
     */
    public function findById(int $id): ?Absence;

    /**
     * Encuentra una ausencia por ID o falla
     */
    public function findByIdOrFail(int $id): Absence;

    /**
     * Actualiza una ausencia
     */
    public function update(Absence $absence, array $data): Absence;

    /**
     * Verifica si existe ausencia aprobada en una fecha para un empleado
     */
    public function hasApprovedAbsenceOnDate(int $userId, string $date): bool;

    /**
     * Encuentra ausencias solapadas para un empleado en un rango de fechas
     */
    public function findOverlapping(int $userId, string $startDate, string $endDate, ?int $excludeId = null): Collection;

    /**
     * Obtiene todas las ausencias de un empleado
     */
    public function getByUser(int $userId): Collection;

    /**
     * Obtiene ausencias por estado
     */
    public function getByStatus(AbsenceStatus $status): Collection;

    /**
     * Obtiene ausencias de un empleado en un rango de fechas
     */
    public function getByUserAndDateRange(int $userId, string $startDate, string $endDate): Collection;
}
