<?php

namespace Database\Seeders;

use App\Models\ShiftTemplate;
use Illuminate\Database\Seeder;

class ShiftTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Mañana',
                'start_time' => '08:00',
                'end_time' => '16:00',
                'color' => '#3B82F6', // Blue
                'description' => 'Turno matutino estándar',
                'sort_order' => 1,
            ],
            [
                'name' => 'Tarde',
                'start_time' => '14:00',
                'end_time' => '22:00',
                'color' => '#F59E0B', // Amber
                'description' => 'Turno vespertino',
                'sort_order' => 2,
            ],
            [
                'name' => 'Noche',
                'start_time' => '22:00',
                'end_time' => '06:00',
                'color' => '#6366F1', // Indigo
                'description' => 'Turno nocturno',
                'sort_order' => 3,
            ],
            [
                'name' => 'Partido',
                'start_time' => '16:00',
                'end_time' => '00:00',
                'color' => '#10B981', // Green
                'description' => 'Turno partido (tarde-noche)',
                'sort_order' => 4,
            ],
            [
                'name' => 'Intensivo',
                'start_time' => '09:00',
                'end_time' => '15:00',
                'color' => '#EC4899', // Pink
                'description' => 'Jornada intensiva (6 horas)',
                'sort_order' => 5,
            ],
            [
                'name' => 'Refuerzo',
                'start_time' => '10:00',
                'end_time' => '14:00',
                'color' => '#8B5CF6', // Purple
                'description' => 'Turno de refuerzo en horas pico',
                'sort_order' => 6,
            ],
        ];

        foreach ($templates as $template) {
            ShiftTemplate::create($template);
        }
    }
}
