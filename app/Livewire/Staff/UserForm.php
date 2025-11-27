<?php

namespace App\Livewire\Staff;

use App\Domains\Staff\Requests\StoreUserRequest;
use App\Domains\Staff\Requests\UpdateUserRequest;
use App\Domains\Staff\Services\UserService;
use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserForm extends Component
{
    public ?int $userId = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public bool $is_active = true;

    public ?int $supervisor_id = null;

    public ?string $hired_at = null;

    public int $annual_vacation_days = 20;

    public array $selectedRoles = [];

    public bool $updatePassword = false;

    public function mount(?int $userId = null): void
    {
        if ($userId) {
            $this->loadUser($userId);
        } else {
            $this->hired_at = now()->format('Y-m-d');
        }
    }

    private function loadUser(int $userId): void
    {
        $user = User::with('roles')->findOrFail($userId);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_active = $user->is_active;
        $this->supervisor_id = $user->supervisor_id;
        $this->hired_at = $user->hired_at?->format('Y-m-d');
        $this->annual_vacation_days = $user->annual_vacation_days;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
    }

    public function save()
    {
        // Obtener reglas del Form Request apropiado
        $requestClass = $this->userId ? UpdateUserRequest::class : StoreUserRequest::class;
        
        // Crear el request con los datos del componente para que las reglas condicionales funcionen
        $request = $requestClass::createFrom(request());
        $request->replace([
            'userId' => $this->userId,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'is_active' => $this->is_active,
            'supervisor_id' => $this->supervisor_id,
            'hired_at' => $this->hired_at,
            'annual_vacation_days' => $this->annual_vacation_days,
            'selectedRoles' => $this->selectedRoles,
            'updatePassword' => $this->updatePassword,
        ]);
        
        $rules = $request->rules();
        $messages = $request->messages();

        $this->validate($rules, $messages);

        try {
            $service = app(UserService::class);

            if ($this->userId) {
                // Update existing user
                $user = User::findOrFail($this->userId);
                $password = ($this->updatePassword && $this->password) ? $this->password : null;

                $service->updateUser(
                    user: $user,
                    name: $this->name,
                    email: $this->email,
                    isActive: $this->is_active,
                    supervisorId: $this->supervisor_id,
                    hiredAt: $this->hired_at,
                    annualVacationDays: $this->annual_vacation_days,
                    roleIds: $this->selectedRoles,
                    password: $password
                );

                $message = 'Usuario actualizado exitosamente';
            } else {
                // Create new user
                $service->createUser(
                    name: $this->name,
                    email: $this->email,
                    password: $this->password,
                    isActive: $this->is_active,
                    supervisorId: $this->supervisor_id,
                    hiredAt: $this->hired_at,
                    annualVacationDays: $this->annual_vacation_days,
                    roleIds: $this->selectedRoles
                );

                $message = 'Usuario creado exitosamente';
            }

            $this->resetForm();
            $this->dispatch('userSaved');
            session()->flash('success', $message);

            return $this->redirect(route('staff.users.index'), navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Error: '.$e->getMessage(), type: 'error');
        }
    }

    public function resetForm(): void
    {
        $this->reset();
        $this->hired_at = now()->format('Y-m-d');
        $this->annual_vacation_days = 20;
    }

    public function render()
    {
        $roles = Role::all();
        $supervisors = User::where('id', '!=', $this->userId)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['administrador', 'gerente', 'coordinador']);
            })
            ->orderBy('name')
            ->get();

        return view('livewire.staff.user-form', [
            'roles' => $roles,
            'supervisors' => $supervisors,
        ])
            ->layout('layouts.app-sidebar');
    }
}
