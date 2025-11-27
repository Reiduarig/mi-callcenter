<?php

namespace App\Exceptions;

use App\Enums\AbsenceStatus;
use App\Models\Absence;
use Exception;

class InvalidAbsenceStatusException extends Exception
{
    /**
     * Create a new invalid absence status exception.
     */
    public function __construct(
        public readonly Absence $absence,
        public readonly AbsenceStatus $expectedStatus,
        public readonly string $action
    ) {
        $message = sprintf(
            'Solo se pueden %s ausencias con estado %s. Estado actual: %s',
            $this->action,
            $this->expectedStatus->label(),
            $this->absence->status->label()
        );

        parent::__construct($message);
    }

    /**
     * Get the exception message for user display.
     */
    public function getUserMessage(): string
    {
        return $this->getMessage();
    }
}
