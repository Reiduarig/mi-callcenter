<?php

namespace App\Exceptions;

use Exception;

class UserCannotBeDeletedException extends Exception
{
    public function __construct(string $message = 'El usuario no puede ser eliminado')
    {
        parent::__construct($message);
    }

    public function getUserMessage(): string
    {
        return $this->getMessage();
    }
}
