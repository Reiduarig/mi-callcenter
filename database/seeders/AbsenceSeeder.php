<?php

namespace Database\Seeders;

use App\Domains\Staff\Models\Absence;
use App\Models\User;
use Illuminate\Database\Seeder;

class AbsenceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('⚠️ No hay usuarios disponibles. Ejecuta UserSeeder primero.');

            return;
        }

        $tipos = ['vacation', 'sick', 'personal', 'other'];
        $estados = ['approved', 'pending', 'rejected'];
        $absenceCount = 0;

        // Crear ausencias aleatorias para algunos usuarios
        $usersWithAbsence = $users->random(min(4, $users->count()));

        foreach ($usersWithAbsence as $user) {
            // 1-2 ausencias por usuario
            $numAbsences = rand(1, 2);

            for ($i = 0; $i < $numAbsences; $i++) {
                // Fecha aleatoria en los próximos 60 días
                $startDate = now()->addDays(rand(-15, 45));

                // Duración de 1 a 5 días
                $duration = rand(1, 5);
                $endDate = $startDate->copy()->addDays($duration - 1);

                $tipo = $tipos[array_rand($tipos)];

                // Si es fecha pasada, aprobar. Si es futura, puede estar pendiente
                $status = $startDate->isPast() ? 'approved' : $estados[array_rand($estados)];

                Absence::create([
                    'user_id' => $user->id,
                    'type' => $tipo,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'status' => $status,
                ]);

                $absenceCount++;
            }
        }

        $this->command->info("✅ {$absenceCount} ausencias creadas exitosamente");
    }
}
