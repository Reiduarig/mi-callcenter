<?php

namespace App\Livewire;

use App\Domains\Staff\Models\Absence;
use App\Domains\Staff\Models\Shift;
use App\Models\User;
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

    public function mount(): void
    {
        $this->loadMetrics();
    }

    public function loadMetrics(): void
    {
        // Employee metrics (todos los users son employees)
        $this->totalEmployees = User::count();
        $this->activeEmployees = User::where('is_active', true)->count();

        // Agent metrics - count users with agente role
        $this->totalAgents = User::role('agente')->count();

        // Absence metrics
        $this->onLeaveEmployees = Absence::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('status', 'approved')
            ->count();

        $this->pendingAbsences = Absence::where('status', 'pending')->count();

        // Shift metrics
        $this->scheduledShiftsToday = Shift::whereDate('date', now())->count();

        // Recent shifts
        $this->recentShifts = Shift::with('employee')
            ->whereDate('date', '>=', now())
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(5)
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app-sidebar');
    }
}
