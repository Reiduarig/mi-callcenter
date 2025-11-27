<?php

namespace App\Actions;

use App\Models\Shift;

class DeleteShift
{
    public function execute(Shift $shift): bool
    {
        return $shift->delete();
    }
}
