<?php

namespace App\Livewire\Staff;

use App\Domains\Staff\Actions\CreateAbsence;
use App\Domains\Staff\Actions\UpdateAbsence;
use App\Domains\Staff\Models\Absence;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class AbsenceForm extends Component
{
    public ?int $absenceId = null;

    public ?int $userId = null;

    public string $type = 'vacation';

    public string $start_date = '';

    public string $end_date = '';

    public string $status = 'approved';

    public function mount(?int $absenceId = null): void
    {
        if ($absenceId) {
            $this->loadAbsence($absenceId);
        }
    }

    protected array $rules = [
        'userId' => 'required|exists:users,id',
        'type' => 'required|string|in:vacation,sick,personal,other',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'status' => 'required|in:approved,pending,rejected',
    ];

    #[On('editAbsence')]
    public function loadAbsence(int $id): void
    {
        $absence = Absence::findOrFail($id);
        $this->absenceId = $absence->id;
        $this->userId = $absence->user_id;
        $this->type = $absence->type;
        $this->start_date = $absence->start_date->format('Y-m-d');
        $this->end_date = $absence->end_date->format('Y-m-d');
        $this->status = $absence->status;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->absenceId) {
                app(UpdateAbsence::class)->execute(Absence::findOrFail($this->absenceId), [
                    'user_id' => $this->userId,
                    'type' => $this->type,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'status' => $this->status,
                ]);
            } else {
                app(CreateAbsence::class)->execute([
                    'user_id' => $this->userId,
                    'type' => $this->type,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'status' => $this->status,
                ]);
            }

            $this->resetForm();
            $this->dispatch('absenceSaved');

            $message = $this->absenceId ? 'Ausencia actualizada exitosamente' : 'Ausencia creada exitosamente';
            session()->flash('success', $message);
            return $this->redirect(route('staff.absences.index'), navigate: true);
            
        } catch (\Exception $e) {
            // Mostrar error de validación o error general
            $errorMessage = $e->getMessage();

            // Si es un error de validación de negocio, mostrar directamente
            if (str_contains($errorMessage, 'ausencia') ||
                str_contains($errorMessage, 'turno') ||
                str_contains($errorMessage, 'periodo') ||
                str_contains($errorMessage, 'solapa')) {
                $this->addError('general', $errorMessage);
            } else {
                $this->addError('general', 'Error al guardar la ausencia: '.$errorMessage);
            }

            $this->dispatch('toast', message: $errorMessage, type: 'error');
        }
    }

    private function resetForm(): void
    {
        $this->absenceId = null;
        $this->userId = null;
        $this->type = 'vacation';
        $this->start_date = '';
        $this->end_date = '';
        $this->status = 'approved';
    }

    public function render()
    {
        $users = User::orderBy('name')->get();

        return view('livewire.staff.absence-form', [
            'users' => $users,
        ])
            ->layout('layouts.app-sidebar');
    }
}
