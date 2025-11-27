<?php

namespace App\Domains\Staff\Repositories\Contracts;

use App\Domains\Staff\Models\Shift;
use Illuminate\Database\Eloquent\Collection;

interface ShiftRepositoryInterface
{
    /**
     * Crea un nuevo turno
     */
    public function create(array $data): Shift;

    /**
     * Encuentra un turno por ID
     */
    public function findById(int $id): ?Shift;

    /**
     * Encuentra un turno por ID o falla
     */
    public function findByIdOrFail(int $id): Shift;

    /**
     * Actualiza un turno
     */
    public function update(Shift $shift, array $data): Shift;

    /**
     * Verifica si existe turno en una fecha para un empleado
     */
    public function existsOnDate(int $userId, string $date, ?int $excludeId = null): bool;

    /**
     * Encuentra turnos solapados para un empleado en fecha y horarios
     */
    public function findOverlapping(int $userId, string $date, string $startTime, string $endTime, ?int $excludeId = null): Collection;

    /**
     * Obtiene todos los turnos de un empleado
     */
    public function getByUser(int $userId): Collection;

    /**
     * Obtiene turnos de un empleado en un rango de fechas
     */
    public function getByUserAndDateRange(int $userId, string $startDate, string $endDate): Collection;

    /**
     * Cuenta turnos de un empleado en un rango de fechas
     */
    public function countByUserAndDateRange(int $userId, string $startDate, string $endDate): int;
}
