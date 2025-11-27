<?php

namespace App\Livewire\Staff;

use App\Domains\Staff\Exceptions\UserCannotBeDeletedException;
use App\Domains\Staff\Services\UserService;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $deleteId = null;

    public string $roleFilter = '';

    #[On('userSaved')]
    public function refresh(): void
    {
        // Refresh component
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
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
            $user = User::findOrFail($id);
            $service = app(UserService::class);

            $service->deleteUser($user, auth()->user());

            $this->deleteId = null;
            $this->dispatch('toast', message: 'Usuario eliminado exitosamente', type: 'success');
        } catch (UserCannotBeDeletedException $e) {
            $this->dispatch('toast', message: $e->getUserMessage(), type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error al eliminar: '.$e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $users = User::query()
            ->with('roles')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->role($this->roleFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $roles = \Spatie\Permission\Models\Role::all();

        return view('livewire.staff.user-index', [
            'users' => $users,
            'roles' => $roles,
        ])
            ->layout('layouts.app-sidebar');
    }
}
