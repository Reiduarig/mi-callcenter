<?php

namespace App\Domains\Staff\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'boolean',
            'supervisor_id' => 'nullable|exists:users,id',
            'hired_at' => 'nullable|date',
            'annual_vacation_days' => 'required|integer|min:0|max:30',
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'exists:roles,name',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El nombre no puede exceder 255 caracteres',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser válido',
            'email.unique' => 'Ya existe un usuario con este email',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'La confirmación de contraseña no coincide',
            'supervisor_id.exists' => 'El supervisor seleccionado no existe',
            'hired_at.date' => 'La fecha de contratación debe ser válida',
            'annual_vacation_days.required' => 'Los días de vacaciones son obligatorios',
            'annual_vacation_days.integer' => 'Los días de vacaciones deben ser un número entero',
            'annual_vacation_days.min' => 'Los días de vacaciones no pueden ser negativos',
            'annual_vacation_days.max' => 'Los días de vacaciones no pueden exceder 30',
            'selectedRoles.required' => 'Debe asignar al menos un rol',
            'selectedRoles.min' => 'Debe asignar al menos un rol',
        ];
    }
}
