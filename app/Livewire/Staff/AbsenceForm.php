<?php

namespace App\Livewire\Staff;

use App\Enums\AbsenceStatus;
use App\Enums\AbsenceType;
use App\Models\Absence;
use App\Http\Requests\StoreAbsenceRequest;
use App\Http\Requests\UpdateAbsenceRequest;
use App\Services\AbsenceService;
use App\Traits\FiltersDataByRole;
use Livewire\Attributes\On;
use Livewire\Component;

class AbsenceForm extends Component
{
    use FiltersDataByRole;

    public ?int $absenceId = null;

    public ?int $userId = null;

    public string $type = '';

    public string $start_date = '';

    public string $end_date = '';

    public string $status = '';

    public function mount(?int $absenceId = null): void
    {
        // Establecer valores por defecto usando enums
        $this->type = AbsenceType::Vacation->value;
        $this->status = auth()->user()->hasRole('agente')
            ? AbsenceStatus::Pending->value
            : AbsenceStatus::Approved->value;

        // Si es agente, siempre asignar su propio ID
        if (auth()->user()->hasRole('agente')) {
            $this->userId = auth()->id();
        }

        if ($absenceId) {
            $this->loadAbsence($absenceId);
        }
    }

    #[On('editAbsence')]
    public function loadAbsence(int $id): void
    {
        $absence = Absence::findOrFail($id);

        // Verificar que el agente solo pueda editar sus propias ausencias
        if (auth()->user()->hasRole('agente') && $absence->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar esta ausencia.');
        }

        $this->absenceId = $absence->id;
        $this->userId = $absence->user_id;
        $this->type = $absence->type->value;
        $this->start_date = $absence->start_date->format('Y-m-d');
        $this->end_date = $absence->end_date->format('Y-m-d');

        // Los agentes no pueden cambiar el estado
        $this->status = auth()->user()->hasRole('agente')
            ? AbsenceStatus::Pending->value
            : $absence->status->value;
    }

    public function save()
    {
        // Obtener reglas del Form Request apropiado
        $requestClass = $this->absenceId ? UpdateAbsenceRequest::class : StoreAbsenceRequest::class;
        
        // Crear el request con los datos del componente para que las reglas condicionales funcionen
        $request = $requestClass::createFrom(request());
        $request->replace([
            'userId' => $this->userId,
            'type' => $this->type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
        ]);
        
        $rules = $request->rules();
        $messages = $request->messages();

        $this->validate($rules, $messages);

        try {
            $service = app(AbsenceService::class);

            if ($this->absenceId) {
                $absence = Absence::findOrFail($this->absenceId);
                $service->updateAbsence(
                    absence: $absence,
                    userId: $this->userId,
                    type: $this->type,
                    startDate: $this->start_date,
                    endDate: $this->end_date,
                    status: $this->status,
                    currentUser: auth()->user()
                );
                $message = 'Ausencia actualizada exitosamente';
            } else {
                $service->createAbsence(
                    userId: $this->userId,
                    type: $this->type,
                    startDate: $this->start_date,
                    endDate: $this->end_date,
                    status: $this->status,
                    currentUser: auth()->user()
                );
                $message = 'Ausencia creada exitosamente';
            }

            $this->resetForm();
            $this->dispatch('absenceSaved');
            session()->flash('success', $message);

            return $this->redirect(route('staff.absences.index'), navigate: true);

        } catch (\App\Exceptions\ValidationException $e) {
            $this->addError('general', $e->getUserMessage());
            $this->dispatch('toast', message: $e->getUserMessage(), type: 'error');
        } catch (\App\Exceptions\InsufficientVacationDaysException $e) {
            $this->addError('general', $e->getUserMessage());
            $this->dispatch('toast', message: $e->getUserMessage(), type: 'error');
        } catch (\App\Exceptions\InvalidAbsenceStatusException $e) {
            $this->addError('general', $e->getUserMessage());
            $this->dispatch('toast', message: $e->getUserMessage(), type: 'error');
        } catch (\Exception $e) {
            $this->addError('general', 'Error al guardar la ausencia: '.$e->getMessage());
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

    private function resetForm(): void
    {
        $this->absenceId = null;
        $this->userId = null;
        $this->type = AbsenceType::Vacation->value;
        $this->start_date = '';
        $this->end_date = '';
        $this->status = auth()->user()->hasRole('agente')
            ? AbsenceStatus::Pending->value
            : AbsenceStatus::Approved->value;
    }

    public function render()
    {
        // Los usuarios accesibles dependen del rol
        $users = $this->getAccessibleEmployees();

        // Determinar si puede editar usuario y estado
        $canEditUser = ! auth()->user()->hasRole('agente');
        $canEditStatus = ! auth()->user()->hasRole('agente');

        // Obtener las opciones de los enums
        $typeOptions = collect(AbsenceType::cases())->mapWithKeys(fn ($type) => [$type->value => $type->label()]);
        $statusOptions = collect(AbsenceStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()]);

        return view('livewire.staff.absence-form', [
            'users' => $users,
            'canEditUser' => $canEditUser,
            'canEditStatus' => $canEditStatus,
            'typeOptions' => $typeOptions,
            'statusOptions' => $statusOptions,
        ])
            ->layout('layouts.app-sidebar');
    }
}
