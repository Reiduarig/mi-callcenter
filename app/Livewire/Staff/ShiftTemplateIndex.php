<?php

namespace App\Livewire\Staff;

use App\Domains\Staff\Models\ShiftTemplate;
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

            // Verificar si hay turnos usando esta plantilla
            $shiftsCount = $template->shifts()->count();

            if ($shiftsCount > 0) {
                $this->dispatch('toast', message: "No se puede eliminar: hay {$shiftsCount} turnos usando esta plantilla", type: 'error');

                return;
            }

            $template->delete();
            $this->deleteId = null;
            $this->dispatch('toast', message: 'Plantilla eliminada exitosamente', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error al eliminar: '.$e->getMessage(), type: 'error');
        }
    }

    public function toggleActive(int $id): void
    {
        try {
            $template = ShiftTemplate::findOrFail($id);
            $template->update(['is_active' => ! $template->is_active]);

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
