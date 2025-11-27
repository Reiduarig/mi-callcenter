<?php

namespace App\Domains\Staff\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
        $roleId = $this->input('roleId');

        return [
            'name' => 'required|string|max:255|unique:roles,name,'.$roleId,
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
