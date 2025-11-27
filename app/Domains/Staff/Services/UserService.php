<?php

namespace App\Domains\Staff\Services;

use App\Domains\Staff\Exceptions\UserCannotBeDeletedException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Crea un nuevo usuario con validaciones de negocio
     */
    public function createUser(
        string $name,
        string $email,
        string $password,
        bool $isActive,
        ?int $supervisorId,
        ?string $hiredAt,
        int $annualVacationDays,
        array $roleIds
    ): User {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_active' => $isActive,
            'supervisor_id' => $supervisorId,
            'hired_at' => $hiredAt,
            'annual_vacation_days' => $annualVacationDays,
            'used_vacation_days' => 0,
        ]);

        $user->assignRole($roleIds);

        return $user;
    }

    /**
     * Actualiza un usuario existente con validaciones de negocio
     */
    public function updateUser(
        User $user,
        string $name,
        string $email,
        bool $isActive,
        ?int $supervisorId,
        ?string $hiredAt,
        int $annualVacationDays,
        array $roleIds,
        ?string $password = null
    ): User {
        $data = [
            'name' => $name,
            'email' => $email,
            'is_active' => $isActive,
            'supervisor_id' => $supervisorId,
            'hired_at' => $hiredAt,
            'annual_vacation_days' => $annualVacationDays,
        ];

        // Solo actualizar contraseña si se proporciona
        if ($password) {
            $data['password'] = Hash::make($password);
        }

        $user->update($data);
        $user->syncRoles($roleIds);

        return $user->fresh();
    }

    /**
     * Elimina un usuario con validaciones de negocio
     *
     * @throws UserCannotBeDeletedException
     */
    public function deleteUser(User $user, User $currentUser): void
    {
        // Validación: no puede eliminar su propio usuario
        if ($user->id === $currentUser->id) {
            throw new UserCannotBeDeletedException('No puedes eliminar tu propio usuario');
        }

        $user->delete();
    }
}
