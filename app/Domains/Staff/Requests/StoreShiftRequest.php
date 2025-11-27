<?php

namespace App\Domains\Staff\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShiftRequest extends FormRequest
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
        $rules = [
            'user_id' => 'required|exists:users,id',
            'shift_template_id' => 'nullable|exists:shift_templates,id',
            'is_custom' => 'boolean',
            'use_date_range' => 'boolean',
            'exclude_weekends' => 'boolean',
            'date' => 'required|date|after_or_equal:today',
        ];

        // En modo rango de fechas con plantilla, start_time y end_time no son necesarios
        // ya que se toman de la plantilla
        $useRange = $this->boolean('use_date_range');
        $isCustom = $this->boolean('is_custom');
        $hasTemplate = $this->filled('shift_template_id');

        if (! $useRange || $isCustom || ! $hasTemplate) {
            $rules['start_time'] = 'required|date_format:H:i';
            $rules['end_time'] = 'required|date_format:H:i|after:start_time';
        }

        if ($useRange) {
            $rules['end_date'] = 'required|date|after_or_equal:date';
            $rules['shift_template_id'] = 'required|exists:shift_templates,id';
        }

        return $rules;
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
            'date.after_or_equal' => 'La fecha no puede ser anterior a hoy',
            'start_time.required' => 'La hora de inicio es obligatoria',
            'start_time.date_format' => 'El formato de hora de inicio debe ser HH:MM',
            'end_time.required' => 'La hora de fin es obligatoria',
            'end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio',
            'end_date.required' => 'La fecha de fin es obligatoria en modo rango',
            'end_date.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
            'shift_template_id.required' => 'Debe seleccionar una plantilla en modo rango de fechas',
        ];
    }
}
