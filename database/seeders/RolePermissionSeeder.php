<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos solo si no existen
        $permissions = [
            // Dashboard
            'view-dashboard',
            'view-full-dashboard',
            'view-team-dashboard',

            // Usuarios
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',

            // Agentes
            'view-agents',

            // Turnos
            'view-shifts',
            'view-all-shifts',
            'view-team-shifts',
            'view-own-shifts',
            'create-shifts',
            'create-team-shifts',
            'edit-shifts',
            'edit-team-shifts',
            'delete-shifts',
            'delete-team-shifts',
            'manage-shift-templates',

            // Ausencias
            'view-absences',
            'view-all-absences',
            'view-team-absences',
            'view-own-absences',
            'create-absences',
            'create-own-absences',
            'edit-absences',
            'edit-team-absences',
            'edit-own-absences',
            'delete-absences',
            'delete-team-absences',
            'approve-absences',
            'approve-team-absences',

            // Calendario
            'view-calendar',
            'view-full-calendar',
            'view-team-calendar',
            'view-own-calendar',
            'edit-calendar',
            'edit-team-calendar',

            // Reportes
            'view-reports',
            'view-full-reports',
            'view-team-reports',

            // Sistema
            'manage-system',
            'manage-roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear rol ADMINISTRADOR con todos los permisos (super admin)
        $administrador = Role::firstOrCreate(['name' => 'administrador']);
        $administrador->givePermissionTo(Permission::all());

        // Crear rol GERENTE con todos los permisos
        $gerente = Role::firstOrCreate(['name' => 'gerente']);
        $gerente->givePermissionTo(Permission::all());

        // Crear rol COORDINADOR
        $coordinador = Role::firstOrCreate(['name' => 'coordinador']);
        $coordinador->syncPermissions([
            'view-dashboard',
            'view-team-dashboard',
            'view-agents',
            'view-all-shifts',
            'view-team-shifts',
            'create-shifts',
            'create-team-shifts',
            'edit-team-shifts',
            'delete-team-shifts',
            'manage-shift-templates',
            'view-all-absences',
            'view-team-absences',
            'edit-team-absences',
            'approve-team-absences',
            'view-calendar',
            'view-full-calendar',
            'view-team-calendar',
            'edit-team-calendar',
            'view-team-reports',
        ]);

        // Crear rol AGENTE
        $agente = Role::firstOrCreate(['name' => 'agente']);
        $agente->syncPermissions([
            'view-dashboard',
            'view-own-shifts',
            'view-own-absences',
            'create-own-absences',
            'edit-own-absences',
            'view-own-calendar',
        ]);
    }
}
