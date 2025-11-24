<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Domains\Staff\Models\Employee;
use App\Domains\Staff\Actions\CreateEmployee;;
use App\Domains\Staff\Actions\UpdateEmployee;

class EmployeeForm extends Component
{
    public ?int $employeeId = null;
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $employee_code = '';
    public ?string $position = null;
    public bool $is_active = true;

    protected array $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:employees,email',
        'employee_code' => 'required|unique:employees,employee_code',
        'position' => 'nullable|string|max:255',
        'is_active' => 'boolean',
    ];

    #[On('editEmployee')]
    public function loadEmployee(int $id): void
    {
        $employee = Employee::findOrFail($id);
        $this->employeeId = $employee->id;
        $this->first_name = $employee->first_name;
        $this->last_name = $employee->last_name;
        $this->email = $employee->email;
        $this->employee_code = $employee->employee_code;
        $this->position = $employee->position;
        $this->is_active = $employee->is_active;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->employeeId) {
            // Update
            app(UpdateEmployee::class)->execute(Employee::findOrFail($this->employeeId), [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'employee_code' => $this->employee_code,
                'position' => $this->position,
                'is_active' => $this->is_active,
            ]);
        } else {
            // Create
            app(CreateEmployee::class)->execute([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'employee_code' => $this->employee_code,
                'position' => $this->position,
                'is_active' => $this->is_active,
            ]);
        }

        $this->resetForm();
        $this->emit('employeeSaved');
    }

    private function resetForm(): void
    {
        $this->employeeId = null;
        $this->first_name = '';
        $this->last_name = '';
        $this->email = '';
        $this->employee_code = '';
        $this->position = null;
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.staff.employee-form')
            ->layout('layouts.app');
    }
}
