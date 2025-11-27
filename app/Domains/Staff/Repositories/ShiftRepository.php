<?php

namespace App\Domains\Staff\Repositories;

use App\Domains\Staff\Models\Shift;
use App\Domains\Staff\Repositories\Contracts\ShiftRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ShiftRepository implements ShiftRepositoryInterface
{
    public function create(array $data): Shift
    {
        return Shift::create($data);
    }

    public function findById(int $id): ?Shift
    {
        return Shift::find($id);
    }

    public function findByIdOrFail(int $id): Shift
    {
        return Shift::findOrFail($id);
    }

    public function update(Shift $shift, array $data): Shift
    {
        $shift->update($data);

        return $shift->fresh();
    }

    public function existsOnDate(int $userId, string $date, ?int $excludeId = null): bool
    {
        $query = Shift::where('user_id', $userId)
            ->where('date', $date);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function findOverlapping(int $userId, string $date, string $startTime, string $endTime, ?int $excludeId = null): Collection
    {
        $query = Shift::where('user_id', $userId)
            ->where('date', $date)
            ->where(function ($q) use ($startTime, $endTime) {
                // Turno nuevo empieza durante turno existente
                $q->whereBetween('start_time', [$startTime, $endTime])
                    // Turno nuevo termina durante turno existente
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    // Turno nuevo engloba turno existente
                    ->orWhere(function ($q2) use ($startTime, $endTime) {
                        $q2->where('start_time', '>=', $startTime)
                            ->where('end_time', '<=', $endTime);
                    });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->get();
    }

    public function getByUser(int $userId): Collection
    {
        return Shift::where('user_id', $userId)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
    }

    public function getByUserAndDateRange(int $userId, string $startDate, string $endDate): Collection
    {
        return Shift::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
    }

    public function countByUserAndDateRange(int $userId, string $startDate, string $endDate): int
    {
        return Shift::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->count();
    }
}
