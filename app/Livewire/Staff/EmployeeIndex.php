<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Domains\Staff\Models\Employee;

class EmployeeIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $employees = Employee::where('first_name', 'like', "%{$this->search}%")
            ->orWhere('last_name', 'like', "%{$this->search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.staff.employee-index', [
            'employees' => $employees,
        ])
        ->layout('layouts.app');

    }
}
