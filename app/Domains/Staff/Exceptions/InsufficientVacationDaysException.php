<?php

namespace App\Domains\Staff\Exceptions;

use App\Models\User;
use Exception;

class InsufficientVacationDaysException extends Exception
{
    /**
     * Create a new insufficient vacation days exception.
     */
    public function __construct(
        public readonly User $user,
        public readonly int $requestedDays,
        public readonly int $availableDays
    ) {
        $message = sprintf(
            'El empleado no tiene suficientes días de vacaciones disponibles. Disponibles: %d, Solicitados: %d',
            $this->availableDays,
            $this->requestedDays
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

    /**
     * Get available vacation days.
     */
    public function getAvailableDays(): int
    {
        return $this->availableDays;
    }

    /**
     * Get requested vacation days.
     */
    public function getRequestedDays(): int
    {
        return $this->requestedDays;
    }
}
