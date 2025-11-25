<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class RoleIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $deleteId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->dispatch('confirm-delete', id: $id);
    }

    public function delete(int $id): void
    {
        try {
            $role = Role::findOrFail($id);

            // Evitar eliminar roles del sistema
            if (in_array($role->name, ['administrador', 'gerente', 'coordinador', 'agente'])) {
                $this->dispatch('toast', message: 'No se puede eliminar un rol del sistema', type: 'error');

                return;
            }

            // Verificar si hay usuarios con este rol
            if ($role->users()->count() > 0) {
                $this->dispatch('toast', message: 'No se puede eliminar un rol que está asignado a usuarios', type: 'error');

                return;
            }

            $role->delete();

            $this->deleteId = null;
            $this->dispatch('toast', message: 'Rol eliminado exitosamente', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error al eliminar: '.$e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $query = Role::withCount('users', 'permissions');

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        $roles = $query->orderBy('name')->paginate(10);

        return view('livewire.staff.role-index', [
            'roles' => $roles,
        ])->layout('layouts.app-sidebar');
    }
}
