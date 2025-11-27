<?php

namespace App\Livewire;

use App\Domains\Staff\Services\DashboardMetricsService;
use Livewire\Component;

class Dashboard extends Component
{
    public int $totalEmployees;

    public int $activeEmployees;

    public int $totalAgents;

    public int $onLeaveEmployees;

    public int $pendingAbsences;

    public int $scheduledShiftsToday;

    public array $recentShifts;

    public int $availableVacationDays = 0;

    public int $usedVacationDays = 0;

    public int $annualVacationDays = 0;

    public function mount(): void
    {
        $this->loadMetrics();
    }

    public function loadMetrics(): void
    {
        $user = auth()->user();
        $service = app(DashboardMetricsService::class);

        // Obtener métricas según el rol del usuario
        $metrics = $service->getMetricsForUser($user);

        $this->totalEmployees = $metrics['totalEmployees'];
        $this->activeEmployees = $metrics['activeEmployees'];
        $this->totalAgents = $metrics['totalAgents'];
        $this->onLeaveEmployees = $metrics['onLeaveEmployees'];
        $this->pendingAbsences = $metrics['pendingAbsences'];
        $this->scheduledShiftsToday = $metrics['scheduledShiftsToday'];
        $this->availableVacationDays = $metrics['availableVacationDays'];
        $this->usedVacationDays = $metrics['usedVacationDays'];
        $this->annualVacationDays = $metrics['annualVacationDays'];

        // Obtener turnos recientes según el rol
        $this->recentShifts = $service->getRecentShifts($user)->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app-sidebar');
    }
}
