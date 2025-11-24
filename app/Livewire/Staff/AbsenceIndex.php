<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Domains\Staff\Models\Absence;
use App\Domains\Staff\Models\Employee;

class AbsenceIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $filterEmployeeId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $absences = Absence::with('employee')
            ->when($this->filterEmployeeId, fn($q) => $q->where('employee_id', $this->filterEmployeeId))
            ->whereHas('employee', fn($q) => $q->where('first_name', 'like', "%{$this->search}%")
                                              ->orWhere('last_name', 'like', "%{$this->search}%"))
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        $employees = Employee::orderBy('first_name')->get();

        return view('livewire.staff.absence-index', [
            'absences' => $absences,
            'employees' => $employees,
        ])
        ->layout('layouts.app');
    }
}
