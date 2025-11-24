<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Domains\Staff\Models\Shift;
use App\Domains\Staff\Models\Employee;

class ShiftIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $filterEmployeeId = null;

    #[On('shiftSaved')]
    public function refresh(): void
    {
        // Refresh component
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $shifts = Shift::with('employee')
            ->when($this->filterEmployeeId, fn($q) => $q->where('employee_id', $this->filterEmployeeId))
            ->whereHas('employee', fn($q) => $q->where('first_name', 'like', "%{$this->search}%")
                                              ->orWhere('last_name', 'like', "%{$this->search}%"))
            ->orderBy('date', 'desc')
            ->paginate(10);

        $employees = Employee::orderBy('first_name')->get();

        return view('livewire.staff.shift-index', [
            'shifts' => $shifts,
            'employees' => $employees,
        ])
        ->layout('layouts.app');
    }
}
