<?php

namespace App\Domains\Staff\Actions;

use App\Domains\Staff\Models\Shift;
use App\Domains\Staff\Services\ShiftValidationService;

class CreateShift
{
    public function __construct(
        private ShiftValidationService $validationService
    ) {}

    public function execute(array $data): Shift
    {
        // Si hay plantilla y no es personalizado, usar horarios de plantilla
        if (isset($data['shift_template_id']) && ! ($data['is_custom'] ?? false)) {
            $template = \App\Domains\Staff\Models\ShiftTemplate::findOrFail($data['shift_template_id']);
            $data['start_time'] = $template->start_time;
            $data['end_time'] = $template->end_time;
        }

        // Validar el turno antes de crearlo
        $errors = $this->validationService->validateShift(
            employeeId: $data['user_id'],
            date: $data['date'],
            startTime: $data['start_time'],
            endTime: $data['end_time']
        );

        if (! empty($errors)) {
            throw new \Exception(implode(' ', $errors));
        }

        return Shift::create([
            'user_id' => $data['user_id'],
            'shift_template_id' => $data['shift_template_id'] ?? null,
            'is_custom' => $data['is_custom'] ?? false,
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time']
        ]);
    }
}
