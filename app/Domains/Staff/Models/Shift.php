<?php

namespace App\Domains\Staff\Models;

use App\Domains\Staff\Traits\Auditable;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{
    use Auditable, HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\ShiftFactory::new();
    }

    protected $fillable = [
        'user_id',
        'shift_template_id',
        'is_custom',
        'date',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'date' => 'date',
        'is_custom' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ShiftTemplate::class, 'shift_template_id');
    }

    /**
     * Obtener horario de inicio (de plantilla o personalizado)
     */
    public function getEffectiveStartTime(): string
    {
        if ($this->is_custom || ! $this->template) {
            return $this->start_time;
        }

        return $this->template->start_time;
    }

    /**
     * Obtener horario de fin (de plantilla o personalizado)
     */
    public function getEffectiveEndTime(): string
    {
        if ($this->is_custom || ! $this->template) {
            return $this->end_time;
        }

        return $this->template->end_time;
    }

    /**
     * Obtener color (de plantilla o default)
     */
    public function getColor(): string
    {
        if ($this->template) {
            return $this->template->color;
        }

        return '#6B7280'; // Gray por defecto para turnos sin plantilla
    }

    /**
     * Marcar turno como personalizado y actualizar horarios
     */
    public function customize(string $startTime, string $endTime): void
    {
        $this->update([
            'start_time' => $startTime,
            'end_time' => $endTime,
            'is_custom' => true,
        ]);
    }
}
