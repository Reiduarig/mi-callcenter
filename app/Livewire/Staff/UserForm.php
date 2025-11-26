<?php

namespace App\Livewire\Staff;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
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

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->userId)],
            'is_active' => 'boolean',
            'supervisor_id' => 'nullable|exists:users,id',
            'hired_at' => 'nullable|date',
            'annual_vacation_days' => 'required|integer|min:0|max:30',
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'exists:roles,name',
        ];

        if (! $this->userId || $this->updatePassword) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }

    #[On('editUser')]
    public function loadUser(int $id): void
    {
        $user = User::with('roles')->findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_active = $user->is_active;
        $this->supervisor_id = $user->supervisor_id;
        $this->hired_at = $user->hired_at?->format('Y-m-d');
        $this->annual_vacation_days = $user->annual_vacation_days ?? 20;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->updatePassword = false;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->userId) {
                // Update existing user
                $user = User::findOrFail($this->userId);

                $data = [
                    'name' => $this->name,
                    'email' => $this->email,
                    'is_active' => $this->is_active,
                    'supervisor_id' => $this->supervisor_id,
                    'hired_at' => $this->hired_at,
                    'annual_vacation_days' => $this->annual_vacation_days,
                ];

                if ($this->updatePassword && $this->password) {
                    $data['password'] = Hash::make($this->password);
                }

                $user->update($data);
                $user->syncRoles($this->selectedRoles);

                $message = 'Usuario actualizado exitosamente';
            } else {
                // Create new user
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'is_active' => $this->is_active,
                    'supervisor_id' => $this->supervisor_id,
                    'hired_at' => $this->hired_at,
                    'annual_vacation_days' => $this->annual_vacation_days,
                    'used_vacation_days' => 0,
                ]);

                $user->assignRole($this->selectedRoles);

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
