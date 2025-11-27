<?php

namespace App\Services;

use App\Enums\AbsenceStatus;
use App\Models\Absence;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Support\Collection;

class DashboardMetricsService
{
    /**
     * Obtiene todas las métricas del dashboard según el rol del usuario
     */
    public function getMetricsForUser(User $user): array
    {
        if ($user->hasRole(['administrador', 'gerente'])) {
            return $this->getAdminMetrics();
        }

        if ($user->hasRole('coordinador')) {
            return $this->getCoordinatorMetrics();
        }

        return $this->getAgentMetrics($user);
    }

    /**
     * Métricas completas para administradores y gerentes
     */
    private function getAdminMetrics(): array
    {
        return [
            'totalEmployees' => User::count(),
            'activeEmployees' => User::where('is_active', true)->count(),
            'totalAgents' => User::role('agente')->count(),
            'onLeaveEmployees' => $this->countEmployeesOnLeave(),
            'pendingAbsences' => Absence::where('status', AbsenceStatus::Pending)->count(),
            'scheduledShiftsToday' => Shift::whereDate('date', now())->count(),
            'availableVacationDays' => 0,
            'usedVacationDays' => 0,
            'annualVacationDays' => 0,
        ];
    }

    /**
     * Métricas limitadas para coordinadores
     */
    private function getCoordinatorMetrics(): array
    {
        return [
            'totalEmployees' => User::count(),
            'activeEmployees' => User::where('is_active', true)->count(),
            'totalAgents' => User::role('agente')->count(),
            'onLeaveEmployees' => 0,
            'pendingAbsences' => Absence::where('status', AbsenceStatus::Pending)->count(),
            'scheduledShiftsToday' => Shift::whereDate('date', now())->count(),
            'availableVacationDays' => 0,
            'usedVacationDays' => 0,
            'annualVacationDays' => 0,
        ];
    }

    /**
     * Métricas personales para agentes
     */
    private function getAgentMetrics(User $user): array
    {
        return [
            'totalEmployees' => 0,
            'activeEmployees' => 0,
            'totalAgents' => 0,
            'onLeaveEmployees' => 0,
            'pendingAbsences' => Absence::where('user_id', $user->id)
                ->where('status', AbsenceStatus::Pending)
                ->count(),
            'scheduledShiftsToday' => Shift::where('user_id', $user->id)
                ->whereDate('date', now())
                ->count(),
            'availableVacationDays' => $user->availableVacationDays(),
            'usedVacationDays' => $user->used_vacation_days,
            'annualVacationDays' => $user->annual_vacation_days,
        ];
    }

    /**
     * Obtiene los turnos recientes según el rol del usuario
     */
    public function getRecentShifts(User $user): Collection
    {
        if ($user->hasRole(['administrador', 'gerente', 'coordinador'])) {
            return Shift::with('user')
                ->whereDate('date', '>=', now())
                ->orderBy('date')
                ->orderBy('start_time')
                ->limit(5)
                ->get();
        }

        // Agentes solo ven sus propios turnos
        return Shift::where('user_id', $user->id)
            ->whereDate('date', '>=', now())
            ->orderBy('date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();
    }

    /**
     * Cuenta los empleados que están actualmente de ausencia
     */
    private function countEmployeesOnLeave(): int
    {
        return Absence::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('status', AbsenceStatus::Approved)
            ->count();
    }
}
