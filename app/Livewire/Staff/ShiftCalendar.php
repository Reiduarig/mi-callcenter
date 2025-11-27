<?php

namespace App\Livewire\Staff;

use App\Models\Absence;
use App\Models\Shift;
use App\Traits\FiltersDataByRole;
use Carbon\Carbon;
use Livewire\Component;

class ShiftCalendar extends Component
{
    use FiltersDataByRole;

    public int $year;

    public int $month;

    public string $view = 'month'; // month, week, day

    public ?int $selectedUserId = null;

    public array $calendarDays = [];

    public array $shiftsData = [];

    public array $absencesData = [];

    public function mount(): void
    {
        $this->year = now()->year;
        $this->month = now()->month;

        // Si es agente, auto-seleccionar su usuario
        if (auth()->user()->hasRole('agente')) {
            $this->selectedUserId = auth()->id();
        }

        $this->loadCalendarData();
    }

    public function previousMonth(): void
    {
        $date = Carbon::create($this->year, $this->month, 1)->subMonth();
        $this->year = $date->year;
        $this->month = $date->month;
        $this->loadCalendarData();
    }

    public function nextMonth(): void
    {
        $date = Carbon::create($this->year, $this->month, 1)->addMonth();
        $this->year = $date->year;
        $this->month = $date->month;
        $this->loadCalendarData();
    }

    public function goToToday(): void
    {
        $this->year = now()->year;
        $this->month = now()->month;
        $this->loadCalendarData();
    }

    public function updatedSelectedUserId(): void
    {
        $this->loadCalendarData();
    }

    public function moveShift(int $shiftId, string $newDate): void
    {
        try {
            $shift = Shift::findOrFail($shiftId);
            $newDateCarbon = Carbon::parse($newDate);

            // Validate the new date
            if ($newDateCarbon->isPast() && ! $newDateCarbon->isToday()) {
                $this->dispatch('toast', [
                    'type' => 'error',
                    'message' => 'No se puede mover un turno a una fecha pasada',
                ]);

                return;
            }

            // Validar conflictos antes de mover
            $validationService = new \App\Domains\Staff\Services\ShiftValidationService;
            $errors = $validationService->validateShift(
                employeeId: $shift->user_id,
                date: $newDate,
                startTime: $shift->start_time,
                endTime: $shift->end_time,
                excludeShiftId: $shift->id
            );

            if (! empty($errors)) {
                $this->dispatch('toast', [
                    'type' => 'error',
                    'message' => $errors[0],
                ]);

                return;
            }

            // Update the shift date
            $action = new \App\Domains\Staff\Actions\UpdateShift($validationService);
            $action->execute($shift, ['date' => $newDate]);

            $this->loadCalendarData();

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Turno movido exitosamente',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Error al mover el turno: '.$e->getMessage(),
            ]);
        }
    }

    public function loadCalendarData(): void
    {
        $startDate = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $endDate = Carbon::create($this->year, $this->month, 1)->endOfMonth();

        // Get first day of calendar (might be from previous month)
        $calendarStart = $startDate->copy()->startOfWeek(Carbon::MONDAY);
        $calendarEnd = $endDate->copy()->endOfWeek(Carbon::SUNDAY);

        // Build calendar days array
        $this->calendarDays = [];
        $currentDate = $calendarStart->copy();

        while ($currentDate <= $calendarEnd) {
            $this->calendarDays[] = [
                'date' => $currentDate->format('Y-m-d'),
                'day' => $currentDate->day,
                'isCurrentMonth' => $currentDate->month === $this->month,
                'isToday' => $currentDate->isToday(),
                'isWeekend' => $currentDate->isWeekend(),
            ];
            $currentDate->addDay();
        }

        // Load shifts for the month
        $shiftsQuery = Shift::with(['user', 'template'])
            ->whereBetween('date', [$calendarStart, $calendarEnd]);

        // Aplicar filtro por rol
        $shiftsQuery = $this->applyRoleFilter($shiftsQuery);

        if ($this->selectedUserId) {
            $shiftsQuery->where('user_id', $this->selectedUserId);
        }

        $shifts = $shiftsQuery->get();

        // Group shifts by date
        $this->shiftsData = [];
        foreach ($shifts as $shift) {
            $date = $shift->date->format('Y-m-d');
            if (! isset($this->shiftsData[$date])) {
                $this->shiftsData[$date] = [];
            }
            $this->shiftsData[$date][] = [
                'id' => $shift->id,
                'employee_name' => $shift->user->name,
                'start_time' => $shift->start_time,
                'end_time' => $shift->end_time,
                'color' => $shift->getColor(),
                'is_custom' => $shift->is_custom,
                'template_name' => $shift->template?->name,
            ];
        }

        // Load absences
        $absencesQuery = Absence::with('user')
            ->where(function ($query) use ($calendarStart, $calendarEnd) {
                $query->whereBetween('start_date', [$calendarStart, $calendarEnd])
                    ->orWhereBetween('end_date', [$calendarStart, $calendarEnd])
                    ->orWhere(function ($q) use ($calendarStart, $calendarEnd) {
                        $q->where('start_date', '<=', $calendarStart)
                            ->where('end_date', '>=', $calendarEnd);
                    });
            });

        // Aplicar filtro por rol
        $absencesQuery = $this->applyRoleFilter($absencesQuery);

        if ($this->selectedUserId) {
            $absencesQuery->where('user_id', $this->selectedUserId);
        }

        $absences = $absencesQuery->get();

        // Group absences by date
        $this->absencesData = [];
        foreach ($absences as $absence) {
            $start = Carbon::parse($absence->start_date);
            $end = Carbon::parse($absence->end_date);

            $currentDate = max($start, $calendarStart);
            $endDate = min($end, $calendarEnd);

            while ($currentDate <= $endDate) {
                $date = $currentDate->format('Y-m-d');
                if (! isset($this->absencesData[$date])) {
                    $this->absencesData[$date] = [];
                }
                $this->absencesData[$date][] = [
                    'id' => $absence->id,
                    'employee_name' => $absence->user->name,
                    'type' => $absence->type->label(),
                    'status' => $absence->status->label(),
                ];
                $currentDate->addDay();
            }
        }
    }

    public function render()
    {
        // Obtener usuarios accesibles según el rol
        $users = $this->getAccessibleEmployees()->filter(fn ($user) => $user->is_active);
        $currentMonthName = Carbon::create($this->year, $this->month, 1)->locale('es')->monthName;

        return view('livewire.staff.shift-calendar', [
            'users' => $users,
            'currentMonthName' => ucfirst($currentMonthName),
        ])->layout('layouts.app-sidebar');
    }
}
