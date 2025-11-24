<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use App\Domains\Staff\Models\Absence;
use App\Domains\Staff\Models\Employee;
use App\Domains\Staff\Actions\CreateAbsence;
use App\Domains\Staff\Actions\UpdateAbsence;


class AbsenceForm extends Component
{
    public ?int $absenceId = null;
    public ?int $employeeId = null;
    public string $type = 'vacation';
    public string $start_date = '';
    public string $end_date = '';
    public string $status = 'approved';

    // protected  $listeners = ['editAbsence' => 'loadAbsence'];

    protected array $rules = [
        'employeeId' => 'required|exists:employees,id',
        'type' => 'required|string|in:vacation,sick,personal,other',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'status' => 'required|in:approved,pending,rejected',
    ];

    public function loadAbsence(int $id): void
    {
        $absence = Absence::findOrFail($id);
        $this->absenceId = $absence->id;
        $this->employeeId = $absence->employee_id;
        $this->type = $absence->type;
        $this->start_date = $absence->start_date->format('Y-m-d');
        $this->end_date = $absence->end_date->format('Y-m-d');
        $this->status = $absence->status;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->absenceId) {
            app(UpdateAbsence::class)->execute(Absence::findOrFail($this->absenceId), [
                'employee_id' => $this->employeeId,
                'type' => $this->type,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'status' => $this->status,
            ]);
        } else {
            app(CreateAbsence::class)->execute([
                'employee_id' => $this->employeeId,
                'type' => $this->type,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'status' => $this->status,
            ]);
        }

        $this->resetForm();
        $this->emit('absenceSaved');
    }

    private function resetForm(): void
    {
        $this->absenceId = null;
        $this->employeeId = null;
        $this->type = 'vacation';
        $this->start_date = '';
        $this->end_date = '';
        $this->status = 'approved';
    }

    public function render()
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('livewire.staff.absence-form', [
            'employees' => $employees,
        ])
        ->layout('layouts.app');
    }
}
