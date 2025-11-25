<?php

namespace App\Livewire\Staff;

use App\Domains\Staff\Actions\CreateShift;
use App\Domains\Staff\Actions\UpdateShift;
use App\Domains\Staff\Models\Shift;
use App\Domains\Staff\Models\ShiftTemplate;
use App\Domains\Staff\Services\ShiftAssignmentService;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class ShiftForm extends Component
{
    public ?int $shiftId = null;

    public ?int $userId = null;

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

    protected function rules(): array
    {
        $rules = [
            'userId' => 'required|exists:users,id',
            'shift_template_id' => 'nullable|exists:shift_templates,id',
            'is_custom' => 'boolean',
            'use_date_range' => 'boolean',
            'exclude_weekends' => 'boolean',
            'date' => 'required|date|after_or_equal:today'
        ];

        // En modo rango de fechas con plantilla, start_time y end_time no son necesarios
        // ya que se toman de la plantilla
        if (! $this->use_date_range || $this->is_custom || ! $this->shift_template_id) {
            $rules['start_time'] = 'required|date_format:H:i';
            $rules['end_time'] = 'required|date_format:H:i|after:start_time';
        }

        if ($this->use_date_range) {
            $rules['end_date'] = 'required|date|after_or_equal:date';
            $rules['shift_template_id'] = 'required|exists:shift_templates,id'; // Template requerida en modo rango
        }

        return $rules;
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
        $this->userId = $shift->user_id;
        $this->shift_template_id = $shift->shift_template_id;
        $this->is_custom = $shift->is_custom;
        $this->date = $shift->date->format('Y-m-d');
        $this->start_time = $shift->start_time;
        $this->end_time = $shift->end_time;
    }

    public function save()
    {
        $this->validate();

        try {
            // Modo edición: siempre usa CreateShift/UpdateShift
            if ($this->shiftId) {
                $data = [
                    'user_id' => $this->userId,
                    'shift_template_id' => $this->shift_template_id,
                    'is_custom' => $this->is_custom,
                    'date' => $this->date,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time
                ];
                app(UpdateShift::class)->execute(Shift::findOrFail($this->shiftId), $data);
                $message = 'Turno actualizado exitosamente';
            }
            // Modo creación con rango de fechas: usa ShiftAssignmentService
            elseif ($this->use_date_range && $this->shift_template_id) {
                $service = app(\App\Domains\Staff\Services\ShiftAssignmentService::class);
                $user = User::findOrFail($this->userId);
                $template = ShiftTemplate::findOrFail($this->shift_template_id);

                $result = $service->assignTemplate(
                    employee: $user,
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
                $data = [
                    'user_id' => $this->userId,
                    'shift_template_id' => $this->shift_template_id,
                    'is_custom' => $this->is_custom,
                    'date' => $this->date,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time
                ];
                app(CreateShift::class)->execute($data);
                $message = 'Turno creado exitosamente';
            }

            $this->resetForm();
            $this->dispatch('shiftSaved');
            session()->flash('success', $message);

            return $this->redirect(route('staff.shifts.index'), navigate: true);
        } catch (\Exception $e) {
            // Mostrar error de validación o error general
            $errorMessage = $e->getMessage();

            // Si es un error de validación de negocio, mostrar directamente
            if (str_contains($errorMessage, 'turno') ||
                str_contains($errorMessage, 'ausencia') ||
                str_contains($errorMessage, 'horario') ||
                str_contains($errorMessage, 'solapa')) {
                $this->addError('general', $errorMessage);
            } else {
                $this->addError('general', 'Error al guardar el turno: '.$errorMessage);
            }

            $this->dispatch('toast', message: $errorMessage, type: 'error');
        }
    }

    private function resetForm(): void
    {
        $this->shiftId = null;
        $this->userId = null;
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
