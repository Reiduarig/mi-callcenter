<?php

namespace App\Domains\Staff\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:roles,name',
            'selectedPermissions' => 'array',
            'selectedPermissions.*' => 'exists:permissions,name',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del rol es obligatorio',
            'name.max' => 'El nombre del rol no puede exceder 255 caracteres',
            'name.unique' => 'Ya existe un rol con este nombre',
            'selectedPermissions.array' => 'Los permisos deben ser un array',
        ];
    }
}
