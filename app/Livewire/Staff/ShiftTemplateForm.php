<?php

declare(strict_types=1);

namespace App\Livewire\Staff;

use App\Models\ShiftTemplate;
use App\Services\ShiftTemplateService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ShiftTemplateForm extends Component
{
    public ?int $templateId = null;

    public string $name = '';

    public string $start_time = '';

    public string $end_time = '';

    public string $color = '#3B82F6';

    public ?string $description = null;

    public int $sort_order = 0;

    public bool $is_active = true;

    public function mount(?int $id = null): void
    {
        Gate::authorize('manage-shift-templates');

        if ($id) {
            $template = ShiftTemplate::findOrFail($id);
            $this->templateId = $template->id;
            $this->name = $template->name;
            $this->start_time = substr($template->start_time, 0, 5);
            $this->end_time = substr($template->end_time, 0, 5);
            $this->color = $template->color;
            $this->description = $template->description;
            $this->sort_order = $template->sort_order;
            $this->is_active = $template->is_active;
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }

    #[Computed]
    public function calculatedDuration(): float
    {
        if (! $this->start_time || ! $this->end_time) {
            return 0;
        }

        $start = Carbon::createFromFormat('H:i', $this->start_time);
        $end = Carbon::createFromFormat('H:i', $this->end_time);

        if ($end->lessThan($start)) {
            $end->addDay();
        }

        return round($start->diffInMinutes($end) / 60, 2);
    }

    #[Computed]
    public function isOvernightShift(): bool
    {
        if (! $this->start_time || ! $this->end_time) {
            return false;
        }

        return $this->end_time < $this->start_time;
    }

    public function save(): void
    {
        $this->validate();

        $service = app(ShiftTemplateService::class);

        if ($this->templateId) {
            $template = ShiftTemplate::findOrFail($this->templateId);
            $service->updateTemplate(
                template: $template,
                name: $this->name,
                startTime: $this->start_time,
                endTime: $this->end_time,
                color: $this->color,
                description: $this->description,
                sortOrder: $this->sort_order,
                isActive: $this->is_active
            );
            session()->flash('success', 'Plantilla actualizada correctamente');
        } else {
            $service->createTemplate(
                name: $this->name,
                startTime: $this->start_time,
                endTime: $this->end_time,
                color: $this->color,
                description: $this->description,
                sortOrder: $this->sort_order,
                isActive: $this->is_active
            );
            session()->flash('success', 'Plantilla creada correctamente');
        }

        $this->redirectRoute('staff.shift-templates.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.staff.shift-template-form')
            ->layout('layouts.app-sidebar', ['title' => $this->templateId ? 'Editar Plantilla de Turno' : 'Crear Plantilla de Turno']);
    }
}
