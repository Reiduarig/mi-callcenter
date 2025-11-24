<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Domains\Staff\Models\Shift;
use App\Domains\Staff\Models\Employee;
use App\Domains\Staff\Actions\CreateShift;
use App\Domains\Staff\Actions\UpdateShift;

class ShiftForm extends Component
{
    public ?int $shiftId = null;
    public ?int $employeeId = null;
    public string $date = '';
    public string $start_time = '';
    public string $end_time = '';
    public string $status = 'scheduled';

    protected array $rules = [
        'employeeId' => 'required|exists:employees,id',
        'date' => 'required|date',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        'status' => 'required|in:scheduled,completed,cancelled',
    ];

    #[On('editShift')]
    public function loadShift(int $id): void
    {
        $shift = Shift::findOrFail($id);
        $this->shiftId = $shift->id;
        $this->employeeId = $shift->employee_id;
        $this->date = $shift->date->format('Y-m-d');
        $this->start_time = $shift->start_time;
        $this->end_time = $shift->end_time;
        $this->status = $shift->status;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->shiftId) {
            app(UpdateShift::class)->execute(Shift::findOrFail($this->shiftId), [
                'employee_id' => $this->employeeId,
                'date' => $this->date,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'status' => $this->status,
            ]);
        } else {
            app(CreateShift::class)->execute([
                'employee_id' => $this->employeeId,
                'date' => $this->date,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'status' => $this->status,
            ]);
        }

        $this->resetForm();
        $this->emit('shiftSaved');
    }

    private function resetForm(): void
    {
        $this->shiftId = null;
        $this->employeeId = null;
        $this->date = '';
        $this->start_time = '';
        $this->end_time = '';
        $this->status = 'scheduled';
    }

    public function render()
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('livewire.staff.shift-form', [
            'employees' => $employees,
        ])
        ->layout('layouts.app');
    }
}
