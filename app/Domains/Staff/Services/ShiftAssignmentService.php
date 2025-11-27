<?php

namespace App\Domains\Staff\Services;

use App\Domains\Staff\Actions\CreateShift;
use App\Domains\Staff\DataTransferObjects\CreateShiftData;
use App\Domains\Staff\Models\ShiftTemplate;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ShiftAssignmentService
{
    public function __construct(
        private CreateShift $createShift
    ) {}

    /**
     * Asignar plantilla de turno a empleado para un rango de fechas
     */
    public function assignTemplate(
        User $employee,
        ShiftTemplate $template,
        string $startDate,
        string $endDate,
        bool $excludeWeekends = true,
        array $excludeDates = []
    ): array {
        $period = CarbonPeriod::create($startDate, $endDate);
        $created = [];
        $errors = [];

        foreach ($period as $date) {
            // Saltar fines de semana si está configurado
            if ($excludeWeekends && $date->isWeekend()) {
                continue;
            }

            // Saltar fechas excluidas
            if (in_array($date->format('Y-m-d'), $excludeDates)) {
                continue;
            }

            try {
                $data = CreateShiftData::fromArray([
                    'user_id' => $employee->id,
                    'shift_template_id' => $template->id,
                    'date' => $date->format('Y-m-d'),
                    'start_time' => $template->start_time,
                    'end_time' => $template->end_time,
                    'is_custom' => false,
                ]);

                $shift = $this->createShift->execute($data);

                $created[] = $shift;
            } catch (\Exception $e) {
                $errors[] = [
                    'date' => $date->format('Y-m-d'),
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'created' => $created,
            'errors' => $errors,
            'total' => count($created),
            'failed' => count($errors),
        ];
    }

    /**
     * Asignar múltiples plantillas en patrón de rotación
     */
    public function assignRotation(
        User $employee,
        array $templateRotation,
        string $startDate,
        int $weeks = 1,
        bool $excludeWeekends = true
    ): array {
        $allCreated = [];
        $allErrors = [];
        $currentDate = Carbon::parse($startDate);

        for ($week = 0; $week < $weeks; $week++) {
            foreach ($templateRotation as $dayOfWeek => $templateId) {
                // dayOfWeek: 1=Lunes, 7=Domingo
                $date = $currentDate->copy()->addWeeks($week)->startOfWeek()->addDays($dayOfWeek - 1);

                if ($excludeWeekends && $date->isWeekend()) {
                    continue;
                }

                $template = ShiftTemplate::find($templateId);
                if (! $template) {
                    continue;
                }

                try {
                    $data = CreateShiftData::fromArray([
                        'user_id' => $employee->id,
                        'shift_template_id' => $template->id,
                        'date' => $date->format('Y-m-d'),
                        'start_time' => $template->start_time,
                        'end_time' => $template->end_time,
                        'is_custom' => false,
                    ]);

                    $shift = $this->createShift->execute($data);

                    $allCreated[] = $shift;
                } catch (\Exception $e) {
                    $allErrors[] = [
                        'date' => $date->format('Y-m-d'),
                        'error' => $e->getMessage(),
                    ];
                }
            }
        }

        return [
            'created' => $allCreated,
            'errors' => $allErrors,
            'total' => count($allCreated),
            'failed' => count($allErrors),
        ];
    }

    /**
     * Clonar turno de una semana a otra
     */
    public function cloneWeek(
        User $employee,
        string $sourceWeekStart,
        string $targetWeekStart
    ): array {
        $sourceStart = Carbon::parse($sourceWeekStart)->startOfWeek();
        $sourceEnd = $sourceStart->copy()->endOfWeek();

        $sourceShifts = $employee->shifts()
            ->whereBetween('date', [$sourceStart, $sourceEnd])
            ->with('template')
            ->get();

        $created = [];
        $errors = [];

        foreach ($sourceShifts as $sourceShift) {
            $dayOffset = $sourceShift->date->dayOfWeek;
            $targetDate = Carbon::parse($targetWeekStart)->startOfWeek()->addDays($dayOffset);

            try {
                $data = CreateShiftData::fromArray([
                    'user_id' => $employee->id,
                    'shift_template_id' => $sourceShift->shift_template_id,
                    'date' => $targetDate->format('Y-m-d'),
                    'start_time' => $sourceShift->start_time,
                    'end_time' => $sourceShift->end_time,
                    'is_custom' => $sourceShift->is_custom,
                ]);

                $shift = $this->createShift->execute($data);

                $created[] = $shift;
            } catch (\Exception $e) {
                $errors[] = [
                    'date' => $targetDate->format('Y-m-d'),
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'created' => $created,
            'errors' => $errors,
            'total' => count($created),
            'failed' => count($errors),
        ];
    }
}
