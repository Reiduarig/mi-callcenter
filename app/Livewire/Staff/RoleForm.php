<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleForm extends Component
{
    public ?int $roleId = null;

    public ?Role $role = null;

    public string $name = '';

    public array $selectedPermissions = [];

    public function mount(?int $roleId = null): void
    {
        if ($roleId) {
            $this->roleId = $roleId;
            $this->role = Role::with('permissions')->findOrFail($roleId);
            $this->name = $this->role->name;
            $this->selectedPermissions = $this->role->permissions->pluck('name')->toArray();
        }
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,'.$this->roleId],
            'selectedPermissions' => ['array'],
        ], [
            'name.required' => 'El nombre del rol es obligatorio',
            'name.unique' => 'Ya existe un rol con este nombre',
        ]);

        try {
            if ($this->roleId) {
                // Editar rol existente
                $this->role->update(['name' => $this->name]);
            } else {
                // Crear nuevo rol
                $this->role = Role::create(['name' => $this->name]);
            }

            // Sincronizar permisos
            $this->role->syncPermissions($this->selectedPermissions);

            session()->flash('success', 'Rol guardado correctamente');

            return redirect()->route('staff.roles.index');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error al guardar: '.$e->getMessage(), type: 'error');
        }
    }

    public function toggleAllPermissions(): void
    {
        $allPermissions = Permission::pluck('name')->toArray();

        if (count($this->selectedPermissions) === count($allPermissions)) {
            $this->selectedPermissions = [];
        } else {
            $this->selectedPermissions = $allPermissions;
        }
    }

    public function render()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            // Agrupar permisos por módulo (primera palabra antes del guion)
            $parts = explode('-', $permission->name);

            return $parts[0] ?? 'otros';
        });

        return view('livewire.staff.role-form', [
            'permissions' => $permissions,
        ])->layout('layouts.app-sidebar');
    }
}
