<?php

namespace App\Http\Requests;

use App\Enums\AbsenceStatus;
use App\Enums\AbsenceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAbsenceRequest extends FormRequest
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
            'userId' => 'required|exists:users,id',
            'type' => ['required', 'string', Rule::enum(AbsenceType::class)],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => ['required', Rule::enum(AbsenceStatus::class)],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'userId.required' => 'Debe seleccionar un empleado',
            'userId.exists' => 'El empleado seleccionado no existe',
            'type.required' => 'El tipo de ausencia es obligatorio',
            'start_date.required' => 'La fecha de inicio es obligatoria',
            'start_date.date' => 'La fecha de inicio debe ser una fecha válida',
            'end_date.required' => 'La fecha de fin es obligatoria',
            'end_date.date' => 'La fecha de fin debe ser una fecha válida',
            'end_date.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
            'status.required' => 'El estado es obligatorio',
        ];
    }
}
