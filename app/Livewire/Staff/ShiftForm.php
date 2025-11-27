<?php

namespace App\Livewire\Staff;

use App\Domains\Staff\Models\Shift;
use App\Domains\Staff\Models\ShiftTemplate;
use App\Domains\Staff\Requests\StoreShiftRequest;
use App\Domains\Staff\Requests\UpdateShiftRequest;
use App\Domains\Staff\Services\ShiftService;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class ShiftForm extends Component
{
    public ?int $shiftId = null;

    public ?int $user_id = null;

    public ?int $shift_template_id = null;

    public bool $is_custom = false;

    public bool $use_date_range = false;

    public string $date = '';

    public string $end_date = '';

    public bool $exclude_weekends = true;

    public string $start_time = '';

    public string $end_time = '';

    public function mount(?int $shiftId = null): void
    {
        if ($shiftId) {
            $this->loadShift($shiftId);
        }
    }

    public function updatedShiftTemplateId($value): void
    {
        if ($value && ! $this->is_custom) {
            $template = ShiftTemplate::find($value);
            if ($template) {
                $this->start_time = substr($template->start_time, 0, 5);
                $this->end_time = substr($template->end_time, 0, 5);
            }
        }
    }

    public function updatedIsCustom($value): void
    {
        if (! $value && $this->shift_template_id) {
            // Si desmarcamos custom, recargar tiempos de la plantilla
            $template = ShiftTemplate::find($this->shift_template_id);
            if ($template) {
                $this->start_time = substr($template->start_time, 0, 5);
                $this->end_time = substr($template->end_time, 0, 5);
            }
        }
    }

    #[On('editShift')]
    public function loadShift(int $id): void
    {
        $shift = Shift::findOrFail($id);
        $this->shiftId = $shift->id;
        $this->user_id = $shift->user_id;
        $this->shift_template_id = $shift->shift_template_id;
        $this->is_custom = $shift->is_custom;
        $this->date = $shift->date->format('Y-m-d');
        $this->start_time = $shift->start_time;
        $this->end_time = $shift->end_time;
    }

    public function save()
    {
        // Obtener reglas del Form Request apropiado
        $requestClass = $this->shiftId ? UpdateShiftRequest::class : StoreShiftRequest::class;
        
        // Crear el request con los datos del componente para que las reglas condicionales funcionen
        $request = $requestClass::createFrom(request());
        $request->replace([
            'user_id' => $this->user_id,
            'shift_template_id' => $this->shift_template_id,
            'is_custom' => $this->is_custom,
            'use_date_range' => $this->use_date_range,
            'exclude_weekends' => $this->exclude_weekends,
            'date' => $this->date,
            'end_date' => $this->end_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ]);
        
        $rules = $request->rules();
        $messages = $request->messages();

        $this->validate($rules, $messages);

        try {
            $service = app(ShiftService::class);

            // Modo edición: actualizar turno existente
            if ($this->shiftId) {
                $shift = Shift::findOrFail($this->shiftId);
                $service->updateShift(
                    shift: $shift,
                    userId: $this->user_id,
                    shiftTemplateId: $this->shift_template_id,
                    isCustom: $this->is_custom,
                    date: $this->date,
                    startTime: $this->start_time,
                    endTime: $this->end_time
                );
                $message = 'Turno actualizado exitosamente';
            }
            // Modo creación con rango de fechas: crear múltiples turnos
            elseif ($this->use_date_range && $this->shift_template_id) {
                $user = User::findOrFail($this->user_id);
                $template = ShiftTemplate::findOrFail($this->shift_template_id);

                $result = $service->createShiftsInRange(
                    user: $user,
                    template: $template,
                    startDate: $this->date,
                    endDate: $this->end_date,
                    excludeWeekends: $this->exclude_weekends
                );

                $message = "Se crearon {$result['total']} turnos exitosamente";
                if ($result['failed'] > 0) {
                    $message .= " ({$result['failed']} fallidos)";
                }
            }
            // Modo creación normal: un turno para una fecha
            else {
                $service->createShift(
                    userId: $this->userId,
                    shiftTemplateId: $this->shift_template_id,
                    isCustom: $this->is_custom,
                    date: $this->date,
                    startTime: $this->start_time,
                    endTime: $this->end_time
                );
                $message = 'Turno creado exitosamente';
            }

            $this->resetForm();
            $this->dispatch('shiftSaved');
            session()->flash('success', $message);

            return $this->redirect(route('staff.shifts.index'), navigate: true);
        } catch (\App\Domains\Staff\Exceptions\ValidationException $e) {
            $this->addError('general', $e->getUserMessage());
            $this->dispatch('toast', message: $e->getUserMessage(), type: 'error');
        } catch (\App\Domains\Staff\Exceptions\ShiftConflictException $e) {
            $this->addError('general', $e->getUserMessage());
            $this->dispatch('toast', message: $e->getUserMessage(), type: 'error');
        } catch (\Exception $e) {
            $this->addError('general', 'Error al guardar el turno: '.$e->getMessage());
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

    private function resetForm(): void
    {
        $this->shiftId = null;
        $this->user_id = null;
        $this->shift_template_id = null;
        $this->is_custom = false;
        $this->use_date_range = false;
        $this->date = '';
        $this->end_date = '';
        $this->exclude_weekends = true;
        $this->start_time = '';
        $this->end_time = '';
    }

    public function render()
    {
        $users = User::orderBy('name')->get();
        $templates = ShiftTemplate::active()->ordered()->get();

        return view('livewire.staff.shift-form', [
            'users' => $users,
            'templates' => $templates,
        ])
            ->layout('layouts.app-sidebar');
    }
}
