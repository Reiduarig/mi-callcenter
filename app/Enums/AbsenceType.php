<?php

namespace App\Enums;

enum AbsenceType: string
{
    case Vacation = 'vacation';
    case Sick = 'sick';
    case Personal = 'personal';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Vacation => 'Vacaciones',
            self::Sick => 'Enfermedad',
            self::Personal => 'Personal',
            self::Other => 'Otro',
        };
    }

    public function consumesVacationDays(): bool
    {
        return $this === self::Vacation;
    }
}
