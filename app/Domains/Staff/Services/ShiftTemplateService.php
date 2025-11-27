<?php

namespace App\Domains\Staff\Services;

use App\Domains\Staff\Exceptions\ShiftTemplateCannotBeDeletedException;
use App\Domains\Staff\Models\ShiftTemplate;

class ShiftTemplateService
{
    /**
     * Crea una nueva plantilla de turno
     */
    public function createTemplate(
        string $name,
        string $startTime,
        string $endTime,
        string $color,
        ?string $description,
        int $sortOrder,
        bool $isActive
    ): ShiftTemplate {
        return ShiftTemplate::create([
            'name' => $name,
            'start_time' => $this->formatTime($startTime),
            'end_time' => $this->formatTime($endTime),
            'color' => $color,
            'description' => $description,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ]);
    }

    /**
     * Actualiza una plantilla de turno existente
     */
    public function updateTemplate(
        ShiftTemplate $template,
        string $name,
        string $startTime,
        string $endTime,
        string $color,
        ?string $description,
        int $sortOrder,
        bool $isActive
    ): ShiftTemplate {
        $template->update([
            'name' => $name,
            'start_time' => $this->formatTime($startTime),
            'end_time' => $this->formatTime($endTime),
            'color' => $color,
            'description' => $description,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ]);

        return $template->fresh();
    }

    /**
     * Elimina una plantilla de turno con validaciones de negocio
     *
     * @throws ShiftTemplateCannotBeDeletedException
     */
    public function deleteTemplate(ShiftTemplate $template): void
    {
        // Validación: verificar si hay turnos usando esta plantilla
        $shiftsCount = $template->shifts()->count();

        if ($shiftsCount > 0) {
            throw new ShiftTemplateCannotBeDeletedException(
                "No se puede eliminar: hay {$shiftsCount} turnos usando esta plantilla"
            );
        }

        $template->delete();
    }

    /**
     * Alterna el estado activo/inactivo de una plantilla
     */
    public function toggleStatus(ShiftTemplate $template): ShiftTemplate
    {
        $template->update(['is_active' => ! $template->is_active]);

        return $template->fresh();
    }

    /**
     * Formatea el tiempo para asegurar el formato correcto (HH:MM:SS)
     */
    private function formatTime(string $time): string
    {
        // Si ya tiene segundos, devolverlo tal cual
        if (substr_count($time, ':') === 2) {
            return $time;
        }

        // Si no tiene segundos, agregarlos
        return $time.':00';
    }
}
