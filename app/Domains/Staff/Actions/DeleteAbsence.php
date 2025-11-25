<?php

namespace App\Domains\Staff\Actions;

use App\Domains\Staff\Models\Absence;

class DeleteAbsence
{
    public function execute(Absence $absence): bool
    {
        return $absence->delete();
    }
}
