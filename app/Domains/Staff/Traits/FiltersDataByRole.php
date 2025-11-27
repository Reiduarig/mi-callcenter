<?php

namespace App\Domains\Staff\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait FiltersDataByRole
{
    public function applyRoleFilter(Builder $query, string $relation = 'user'): Builder
    {
        $user = auth()->user();

        // Si no hay usuario autenticado, devolver query vacía
        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        // Administradores y gerentes ven todo
        if ($user->hasRole(['administrador', 'gerente'])) {
            return $query;
        }

        // Coordinadores ven solo su equipo
        if ($user->hasRole('coordinador')) {
            // Obtener IDs del equipo (subordinados directos e indirectos)
            $teamMemberIds = $user->getTeamMembers()->pluck('id')->push($user->id);

            return $query->whereHas($relation, fn ($q) => $q->whereIn('id', $teamMemberIds));
        }

        // Agentes ven solo sus propios datos
        if ($user->hasRole('agente')) {
            return $query->whereHas($relation, fn ($q) => $q->where('id', $user->id));
        }

        // Por defecto, no mostrar nada
        return $query->whereRaw('1 = 0');
    }

    public function getAccessibleEmployees(): \Illuminate\Support\Collection
    {
        $user = auth()->user();

        if (! $user) {
            return collect();
        }

        // Administradores y gerentes ven todos
        if ($user->hasRole(['administrador', 'gerente'])) {
            return User::orderBy('name')->get();
        }

        // Coordinadores ven su equipo
        if ($user->hasRole('coordinador')) {
            return $user->getTeamMembers()->push($user)->sortBy('name')->values();
        }

        // Agentes ven solo a ellos mismos
        if ($user->hasRole('agente')) {
            return collect([$user]);
        }

        return collect();
    }
}
