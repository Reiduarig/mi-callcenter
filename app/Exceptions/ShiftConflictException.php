<?php

namespace App\Exceptions;

use Exception;

class ShiftConflictException extends Exception
{
    /**
     * Create a new shift conflict exception.
     */
    public function __construct(
        public readonly string $conflictType,
        public readonly array $conflictDetails = []
    ) {
        parent::__construct($this->buildMessage());
    }

    /**
     * Build the exception message based on conflict type and details.
     */
    private function buildMessage(): string
    {
        return match ($this->conflictType) {
            'duplicate_shift' => 'El empleado ya tiene un turno programado en esta fecha.',
            'time_overlap' => 'El turno solapa con otro turno existente del empleado.',
            'absence_conflict' => 'El empleado tiene una ausencia aprobada en esta fecha.',
            'invalid_time' => 'La hora de inicio debe ser anterior a la hora de fin.',
            default => 'Conflicto de turno detectado.',
        };
    }

    /**
     * Get the exception message for user display.
     */
    public function getUserMessage(): string
    {
        return $this->getMessage();
    }

    /**
     * Get conflict details.
     */
    public function getConflictDetails(): array
    {
        return $this->conflictDetails;
    }

    /**
     * Get conflict type.
     */
    public function getConflictType(): string
    {
        return $this->conflictType;
    }
}
