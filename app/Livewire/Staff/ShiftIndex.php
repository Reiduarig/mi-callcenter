<?php

namespace App\Livewire\Staff;

use App\Actions\DeleteShift;
use App\Models\Shift;
use App\Traits\FiltersDataByRole;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ShiftIndex extends Component
{
    use FiltersDataByRole, WithPagination;

    public string $search = '';

    public ?int $filterUserId = null;

    public ?int $deleteId = null;

    #[On('shiftSaved')]
    public function refresh(): void
    {
        // Refresh component
    }

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
            $shift = Shift::findOrFail($id);
            app(DeleteShift::class)->execute($shift);

            $this->deleteId = null;
            $this->dispatch('toast', message: 'Turno eliminado exitosamente', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error al eliminar: '.$e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $query = Shift::with('user');

        // Aplicar filtro por rol
        $query = $this->applyRoleFilter($query);

        // Aplicar otros filtros
        $query->when($this->filterUserId, fn ($q) => $q->where('user_id', $this->filterUserId))
            ->when($this->search, fn ($q) => $q->whereHas('user', fn ($subQ) => $subQ->where('name', 'like', "%{$this->search}%")))
            ->orderBy('date', 'desc');

        $shifts = $query->paginate(10);

        $users = $this->getAccessibleEmployees();

        return view('livewire.staff.shift-index', [
            'shifts' => $shifts,
            'users' => $users,
        ])
            ->layout('layouts.app-sidebar');
    }
}
