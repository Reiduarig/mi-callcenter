<?php

namespace App\Livewire\Staff;

use App\Domains\Staff\Actions\DeleteAbsence;
use App\Domains\Staff\Models\Absence;
use App\Domains\Staff\Traits\FiltersDataByRole;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AbsenceIndex extends Component
{
    use FiltersDataByRole, WithPagination;

    public string $search = '';

    public ?int $filterUserId = null;

    public ?int $deleteId = null;

    #[On('absenceSaved')]
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
            $absence = Absence::findOrFail($id);
            app(DeleteAbsence::class)->execute($absence);

            $this->deleteId = null;
            $this->dispatch('toast', message: 'Ausencia eliminada exitosamente', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error al eliminar: '.$e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $query = Absence::with(['user', 'approver']);

        // Aplicar filtro por rol
        $query = $this->applyRoleFilter($query);

        // Aplicar otros filtros
        $query->when($this->filterUserId, fn ($q) => $q->where('user_id', $this->filterUserId))
            ->when($this->search, fn ($q) => $q->whereHas('user', fn ($subQ) => $subQ->where('name', 'like', "%{$this->search}%")))
            ->orderBy('start_date', 'desc');

        $absences = $query->paginate(10);

        $users = $this->getAccessibleEmployees();

        return view('livewire.staff.absence-index', [
            'absences' => $absences,
            'users' => $users,
        ])
            ->layout('layouts.app-sidebar');
    }
}
