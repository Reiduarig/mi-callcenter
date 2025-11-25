<?php

namespace App\Domains\Staff\Actions;

use App\Domains\Staff\Models\Shift;

class DeleteShift
{
    public function execute(Shift $shift): bool
    {
        return $shift->delete();
    }
}
