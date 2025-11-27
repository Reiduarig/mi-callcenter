<?php

namespace App\Repositories;

use App\Enums\AbsenceStatus;
use App\Models\Absence;
use App\Repositories\Contracts\AbsenceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AbsenceRepository implements AbsenceRepositoryInterface
{
    public function create(array $data): Absence
    {
        return Absence::create($data);
    }

    public function findById(int $id): ?Absence
    {
        return Absence::find($id);
    }

    public function findByIdOrFail(int $id): Absence
    {
        return Absence::findOrFail($id);
    }

    public function update(Absence $absence, array $data): Absence
    {
        $absence->update($data);

        return $absence->fresh();
    }

    public function hasApprovedAbsenceOnDate(int $userId, string $date): bool
    {
        return Absence::where('user_id', $userId)
            ->where('status', AbsenceStatus::Approved)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->exists();
    }

    public function findOverlapping(int $userId, string $startDate, string $endDate, ?int $excludeId = null): Collection
    {
        $query = Absence::where('user_id', $userId)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q2) use ($startDate, $endDate) {
                        $q2->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->get();
    }

    public function getByUser(int $userId): Collection
    {
        return Absence::where('user_id', $userId)
            ->orderBy('start_date')
            ->get();
    }

    public function getByStatus(AbsenceStatus $status): Collection
    {
        return Absence::where('status', $status)
            ->orderBy('start_date')
            ->get();
    }

    public function getByUserAndDateRange(int $userId, string $startDate, string $endDate): Collection
    {
        return Absence::where('user_id', $userId)
            ->where('start_date', '>=', $startDate)
            ->where('end_date', '<=', $endDate)
            ->orderBy('start_date')
            ->get();
    }
}
