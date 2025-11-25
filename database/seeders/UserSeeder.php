<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Usuario Administrador
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@callcenter.com'],
            [
                'name' => 'Admin Sistema',
                'password' => Hash::make('password'),
                'is_active' => true,
                'hired_at' => now()->subYears(2),
                'annual_vacation_days' => 20,
                'used_vacation_days' => 0,
            ]
        );
        if (! $adminUser->hasRole('administrador')) {
            $adminUser->assignRole('administrador');
        }

        // 2. Usuario Gerente
        $gerenteUser = User::firstOrCreate(
            ['email' => 'gerente@callcenter.com'],
            [
                'name' => 'María Gerente',
                'password' => Hash::make('password'),
                'is_active' => true,
                'hired_at' => now()->subYears(3),
                'annual_vacation_days' => 25,
                'used_vacation_days' => 5,
            ]
        );
        if (! $gerenteUser->hasRole('gerente')) {
            $gerenteUser->assignRole('gerente');
        }

        // 3. Usuario Coordinador (supervisor de agentes)
        $coordinadorUser = User::firstOrCreate(
            ['email' => 'coordinador@callcenter.com'],
            [
                'name' => 'Carlos Coordinador',
                'password' => Hash::make('password'),
                'is_active' => true,
                'hired_at' => now()->subYear(),
                'annual_vacation_days' => 22,
                'used_vacation_days' => 3,
            ]
        );
        if (! $coordinadorUser->hasRole('coordinador')) {
            $coordinadorUser->assignRole('coordinador');
        }

        // 4. Usuarios Agentes (3 agentes)
        $agentes = [
            [
                'name' => 'Ana López',
                'email' => 'ana.lopez@callcenter.com',
            ],
            [
                'name' => 'Juan Pérez',
                'email' => 'juan.perez@callcenter.com',
            ],
            [
                'name' => 'Laura Martínez',
                'email' => 'laura.martinez@callcenter.com',
            ],
        ];

        foreach ($agentes as $agenteData) {
            $agenteUser = User::firstOrCreate(
                ['email' => $agenteData['email']],
                [
                    'name' => $agenteData['name'],
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'supervisor_id' => $coordinadorUser->id, // Coordinador es supervisor
                    'hired_at' => now()->subMonths(rand(3, 11)),
                    'annual_vacation_days' => 20,
                    'used_vacation_days' => rand(0, 5),
                ]
            );
            if (! $agenteUser->hasRole('agente')) {
                $agenteUser->assignRole('agente');
            }
        }

        $this->command->info('✅ Usuarios de prueba creados exitosamente:');
        $this->command->info('   👑 Admin: admin@callcenter.com / password');
        $this->command->info('   👔 Gerente: gerente@callcenter.com / password');
        $this->command->info('   👨‍💼 Coordinador: coordinador@callcenter.com / password');
        $this->command->info('   🎧 Agentes:');
        $this->command->info('      - ana.lopez@callcenter.com / password');
        $this->command->info('      - juan.perez@callcenter.com / password');
        $this->command->info('      - laura.martinez@callcenter.com / password');
    }
}
