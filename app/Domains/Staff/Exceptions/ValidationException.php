<?php

namespace App\Domains\Staff\Exceptions;

use Exception;

class ValidationException extends Exception
{
    /**
     * Create a new validation exception instance.
     *
     * @param  array<string>  $errors
     */
    public function __construct(array $errors)
    {
        parent::__construct(implode(' ', $errors));
    }

    /**
     * Get the exception message for user display.
     */
    public function getUserMessage(): string
    {
        return $this->getMessage();
    }
}
