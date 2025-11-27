<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShiftRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by middleware/policies
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'shift_template_id' => 'nullable|exists:shift_templates,id',
            'is_custom' => 'boolean',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Debe seleccionar un empleado',
            'user_id.exists' => 'El empleado seleccionado no existe',
            'date.required' => 'La fecha es obligatoria',
            'start_time.required' => 'La hora de inicio es obligatoria',
            'start_time.date_format' => 'El formato de hora de inicio debe ser HH:MM',
            'end_time.required' => 'La hora de fin es obligatoria',
            'end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio',
        ];
    }
}
