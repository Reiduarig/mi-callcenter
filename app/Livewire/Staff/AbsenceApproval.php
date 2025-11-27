<?php

namespace App\Livewire\Staff;

use App\Domains\Staff\Actions\ApproveAbsence;
use App\Domains\Staff\Actions\RejectAbsence;
use App\Domains\Staff\DataTransferObjects\ApprovalData;
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
            $approvalData = $this->notes ? ApprovalData::fromArray(['notes' => $this->notes]) : null;

            if ($this->action === 'approve') {
                app(ApproveAbsence::class)->execute($this->absence, $approvalData);
                $message = 'Ausencia aprobada exitosamente';
            } else {
                app(RejectAbsence::class)->execute($this->absence, $approvalData);
                $message = 'Ausencia rechazada';
            }

            $this->closeModal();
            $this->dispatch('absenceSaved');
            $this->dispatch('toast', message: $message, type: 'success');
        } catch (\App\Domains\Staff\Exceptions\InvalidAbsenceStatusException $e) {
            $this->dispatch('toast', message: $e->getUserMessage(), type: 'error');
        } catch (\App\Domains\Staff\Exceptions\InsufficientVacationDaysException $e) {
            $this->dispatch('toast', message: $e->getUserMessage(), type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error: '.$e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.staff.absence-approval');
    }
}
