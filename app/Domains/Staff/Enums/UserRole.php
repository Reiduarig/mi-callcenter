<?php

namespace App\Domains\Staff\Enums;

enum UserRole: string
{
    case Administrator = 'administrador';
    case Manager = 'gerente';
    case Coordinator = 'coordinador';
    case Agent = 'agente';

    public function label(): string
    {
        return match ($this) {
            self::Administrator => 'Administrador',
            self::Manager => 'Gerente',
            self::Coordinator => 'Coordinador',
            self::Agent => 'Agente',
        };
    }

    public function canManageUsers(): bool
    {
        return in_array($this, [self::Administrator, self::Manager]);
    }

    public function canViewAllData(): bool
    {
        return in_array($this, [self::Administrator, self::Manager]);
    }

    public function canViewTeamData(): bool
    {
        return in_array($this, [self::Administrator, self::Manager, self::Coordinator]);
    }

    public function isAgent(): bool
    {
        return $this === self::Agent;
    }

    public function isAdministrator(): bool
    {
        return $this === self::Administrator;
    }

    public function isManager(): bool
    {
        return $this === self::Manager;
    }

    public function isCoordinator(): bool
    {
        return $this === self::Coordinator;
    }
}
