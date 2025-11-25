<?php

namespace App\Livewire\Staff;

use App\Domains\Staff\Actions\ApproveAbsence;
use App\Domains\Staff\Actions\RejectAbsence;
use App\Domains\Staff\Models\Absence;
use Livewire\Component;

class AbsenceApproval extends Component
{
    public ?Absence $absence = null;

    public string $action = '';

    public string $notes = '';

    public bool $showModal = false;

    protected array $rules = [
        'notes' => 'nullable|string|max:500',
    ];

    public function openModal(int $absenceId, string $action): void
    {
        $this->absence = Absence::with(['employee', 'approver'])->findOrFail($absenceId);
        $this->action = $action;
        $this->notes = '';
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->absence = null;
        $this->action = '';
        $this->notes = '';
        $this->resetErrorBag();
    }

    public function submit(): void
    {
        $this->validate();

        try {
            if ($this->action === 'approve') {
                app(ApproveAbsence::class)->execute($this->absence, $this->notes ?: null);
                $message = 'Ausencia aprobada exitosamente';
            } else {
                app(RejectAbsence::class)->execute($this->absence, $this->notes ?: null);
                $message = 'Ausencia rechazada';
            }

            $this->closeModal();
            $this->dispatch('absenceSaved');
            $this->dispatch('toast', message: $message, type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error: '.$e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.staff.absence-approval');
    }
}
