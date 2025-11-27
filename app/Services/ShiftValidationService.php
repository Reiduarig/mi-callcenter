<?php

namespace App\Services;

use App\Enums\AbsenceStatus;
use App\Repositories\Contracts\AbsenceRepositoryInterface;
use App\Repositories\Contracts\ShiftRepositoryInterface;
use Carbon\Carbon;

class ShiftValidationService
{
    public function __construct(
        private ShiftRepositoryInterface $shiftRepository,
        private AbsenceRepositoryInterface $absenceRepository
    ) {}

    /**
     * Valida si un turno puede ser creado o actualizado
     */
    public function validateShift(
        int $employeeId,
        string $date,
        string $startTime,
        string $endTime,
        ?int $excludeShiftId = null
    ): array {
        $errors = [];

        // 1. Validar conflicto de turnos (mismo empleado, mismo día)
        $conflictError = $this->checkShiftConflict($employeeId, $date, $excludeShiftId);
        if ($conflictError) {
            $errors[] = $conflictError;
        }

        // 2. Validar solapamiento de horarios
        $overlapError = $this->checkTimeOverlap($employeeId, $date, $startTime, $endTime, $excludeShiftId);
        if ($overlapError) {
            $errors[] = $overlapError;
        }

        // 3. Validar si tiene ausencia aprobada en esa fecha
        $absenceError = $this->checkAbsenceConflict($employeeId, $date);
        if ($absenceError) {
            $errors[] = $absenceError;
        }

        // 4. Validar que el horario sea válido
        $timeError = $this->validateTimeRange($startTime, $endTime);
        if ($timeError) {
            $errors[] = $timeError;
        }

        return $errors;
    }

    /**
     * Verifica si ya existe un turno para el empleado en la misma fecha
     */
    public function checkShiftConflict(int $employeeId, string $date, ?int $excludeShiftId = null): ?string
    {
        if ($this->shiftRepository->existsOnDate($employeeId, $date, $excludeShiftId)) {
            return 'El empleado ya tiene un turno asignado para esta fecha';
        }

        return null;
    }

    /**
     * Verifica solapamiento de horarios en el mismo día
     */
    public function checkTimeOverlap(
        int $employeeId,
        string $date,
        string $startTime,
        string $endTime,
        ?int $excludeShiftId = null
    ): ?string {
        $start = Carbon::parse("$date $startTime");
        $end = Carbon::parse("$date $endTime");

        // Si el turno termina después de medianoche
        if ($end->lessThan($start)) {
            $end->addDay();
        }

        $existingShifts = $this->shiftRepository->findOverlapping(
            $employeeId,
            $date,
            $startTime,
            $endTime,
            $excludeShiftId
        );

        foreach ($existingShifts as $shift) {
            $existingStart = Carbon::parse("{$shift->date->format('Y-m-d')} {$shift->start_time}");
            $existingEnd = Carbon::parse("{$shift->date->format('Y-m-d')} {$shift->end_time}");

            if ($existingEnd->lessThan($existingStart)) {
                $existingEnd->addDay();
            }

            // Verificar solapamiento
            if ($start->lessThan($existingEnd) && $end->greaterThan($existingStart)) {
                return "El horario del turno se solapa con otro turno existente ({$shift->start_time} - {$shift->end_time})";
            }
        }

        return null;
    }

    /**
     * Verifica si el empleado tiene una ausencia aprobada en la fecha
     */
    public function checkAbsenceConflict(int $employeeId, string $date): ?string
    {
        if ($this->absenceRepository->hasApprovedAbsenceOnDate($employeeId, $date)) {
            return 'El empleado tiene una ausencia aprobada en esta fecha';
        }

        return null;
    }

    /**
     * Valida que el rango de tiempo sea lógico
     */
    public function validateTimeRange(string $startTime, string $endTime): ?string
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        // Si el turno es nocturno (termina al día siguiente), es válido
        if ($end->lessThanOrEqualTo($start)) {
            // Permitir turnos de hasta 12 horas que cruzan medianoche
            $end->addDay();
            $duration = $start->diffInHours($end);

            if ($duration > 12) {
                return 'La duración del turno no puede exceder 12 horas';
            }
        } else {
            $duration = $start->diffInHours($end);

            if ($duration > 12) {
                return 'La duración del turno no puede exceder 12 horas';
            }

            if ($duration < 1) {
                return 'La duración del turno debe ser al menos de 1 hora';
            }
        }

        return null;
    }

    /**
     * Valida conflictos de ausencias
     */
    public function validateAbsence(
        int $employeeId,
        string $startDate,
        string $endDate,
        ?int $excludeAbsenceId = null
    ): array {
        $errors = [];

        // 1. Validar solapamiento con otras ausencias
        $overlapError = $this->checkAbsenceOverlap($employeeId, $startDate, $endDate, $excludeAbsenceId);
        if ($overlapError) {
            $errors[] = $overlapError;
        }

        // 2. Validar que no haya turnos programados en ese rango
        $shiftsError = $this->checkShiftsInAbsencePeriod($employeeId, $startDate, $endDate, $excludeAbsenceId);
        if ($shiftsError) {
            $errors[] = $shiftsError;
        }

        return $errors;
    }

    /**
     * Verifica solapamiento con otras ausencias aprobadas
     */
    public function checkAbsenceOverlap(
        int $employeeId,
        string $startDate,
        string $endDate,
        ?int $excludeAbsenceId = null
    ): ?string {
        $overlapping = $this->absenceRepository->findOverlapping(
            $employeeId,
            $startDate,
            $endDate,
            $excludeAbsenceId
        );

        // Filtrar solo ausencias no rechazadas
        $nonRejected = $overlapping->filter(fn ($absence) => $absence->status !== AbsenceStatus::Rejected);

        if ($nonRejected->isNotEmpty()) {
            return 'Ya existe otra ausencia en este período de fechas';
        }

        return null;
    }

    /**
     * Verifica si hay turnos programados durante el período de ausencia
     */
    public function checkShiftsInAbsencePeriod(
        int $employeeId,
        string $startDate,
        string $endDate,
        ?int $excludeAbsenceId = null
    ): ?string {
        $shiftsCount = $this->shiftRepository->countByUserAndDateRange(
            $employeeId,
            $startDate,
            $endDate
        );

        if ($shiftsCount > 0) {
            return "Hay {$shiftsCount} turno(s) programado(s) durante este período. Cancélalos primero.";
        }

        return null;
    }

    /**
     * Obtiene información de conflictos para mostrar al usuario
     */
    public function getConflictDetails(int $employeeId, string $date): array
    {
        $details = [];

        // Turnos existentes
        $shifts = $this->shiftRepository->getByUserAndDateRange($employeeId, $date, $date);

        if ($shifts->isNotEmpty()) {
            $details['shifts'] = $shifts->map(fn ($s) => [
                'id' => $s->id,
                'start_time' => $s->start_time,
                'end_time' => $s->end_time,
            ])->toArray();
        }

        // Ausencias
        $absences = $this->absenceRepository->getByUserAndDateRange($employeeId, $date, $date);
        $approved = $absences->filter(fn ($a) => $a->status === AbsenceStatus::Approved);

        if ($approved->isNotEmpty()) {
            $details['absences'] = $approved->map(fn ($a) => [
                'id' => $a->id,
                'type' => $a->type->label(),
                'start_date' => $a->start_date->format('Y-m-d'),
                'end_date' => $a->end_date->format('Y-m-d'),
            ])->toArray();
        }

        return $details;
    }
}
