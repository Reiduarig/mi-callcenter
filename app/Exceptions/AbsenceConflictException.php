<?php

namespace App\Exceptions;

use Exception;

class AbsenceConflictException extends Exception
{
    /**
     * Create a new absence conflict exception.
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
            'overlapping_absence' => 'Ya existe una ausencia aprobada en este periodo.',
            'shift_conflict' => 'El empleado tiene turnos programados durante este periodo.',
            'invalid_dates' => 'La fecha de inicio debe ser anterior a la fecha de fin.',
            default => 'Conflicto de ausencia detectado.',
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
