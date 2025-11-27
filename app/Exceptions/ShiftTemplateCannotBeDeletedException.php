<?php

namespace App\Exceptions;

use Exception;

class ShiftTemplateCannotBeDeletedException extends Exception
{
    public function __construct(string $message = 'La plantilla de turno no puede ser eliminada')
    {
        parent::__construct($message);
    }

    public function getUserMessage(): string
    {
        return $this->getMessage();
    }
}
