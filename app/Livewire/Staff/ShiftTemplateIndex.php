<?php

namespace App\Livewire\Staff;

use App\Exceptions\ShiftTemplateCannotBeDeletedException;
use App\Models\ShiftTemplate;
use App\Services\ShiftTemplateService;
use Livewire\Attributes\On;
use Livewire\Component;

class ShiftTemplateIndex extends Component
{
    public ?int $deleteId = null;

    #[On('templateSaved')]
    public function refresh(): void
    {
        // Refresh component
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->dispatch('confirm-delete', id: $id);
    }

    public function delete(int $id): void
    {
        try {
            $template = ShiftTemplate::findOrFail($id);
            $service = app(ShiftTemplateService::class);

            $service->deleteTemplate($template);

            $this->deleteId = null;
            $this->dispatch('toast', message: 'Plantilla eliminada exitosamente', type: 'success');
        } catch (ShiftTemplateCannotBeDeletedException $e) {
            $this->dispatch('toast', message: $e->getUserMessage(), type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error al eliminar: '.$e->getMessage(), type: 'error');
        }
    }

    public function toggleActive(int $id): void
    {
        try {
            $template = ShiftTemplate::findOrFail($id);
            $service = app(ShiftTemplateService::class);

            $template = $service->toggleStatus($template);

            $status = $template->is_active ? 'activada' : 'desactivada';
            $this->dispatch('toast', message: "Plantilla {$status}", type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error: '.$e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $templates = ShiftTemplate::ordered()->get();

        return view('livewire.staff.shift-template-index', [
            'templates' => $templates,
        ])
            ->layout('layouts.app-sidebar');
    }
}
