<?php

namespace Database\Seeders;

use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('agente')->get();

        if ($users->isEmpty()) {
            $this->command->warn('⚠️ No hay agentes disponibles. Ejecuta UserSeeder primero.');

            return;
        }

        $turnos = [
            ['start' => '08:00', 'end' => '16:00'], // Mañana
            ['start' => '14:00', 'end' => '22:00'], // Tarde
            ['start' => '16:00', 'end' => '00:00'], // Noche
        ];

        // Crear turnos para el mes actual y el próximo
        $startDate = now()->startOfMonth();
        $endDate = now()->addMonth()->endOfMonth();

        $currentDate = $startDate->copy();
        $shiftCount = 0;

        while ($currentDate <= $endDate) {
            // Saltar domingos (día de descanso)
            if ($currentDate->dayOfWeek === Carbon::SUNDAY) {
                $currentDate->addDay();

                continue;
            }

            // Asignar turnos a cada agente (rotativo)
            foreach ($users as $index => $user) {
                // Algunos días aleatorios sin turno (descanso)
                if (rand(1, 10) > 8) {
                    continue;
                }

                // Rotar turnos entre usuarios
                $turno = $turnos[$index % count($turnos)];

                Shift::create([
                    'user_id' => $user->id,
                    'date' => $currentDate->format('Y-m-d'),
                    'start_time' => $turno['start'],
                    'end_time' => $turno['end'],
                ]);

                $shiftCount++;
            }

            $currentDate->addDay();
        }

        $this->command->info("✅ {$shiftCount} turnos creados exitosamente");
    }
}
