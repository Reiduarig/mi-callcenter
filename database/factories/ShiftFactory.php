<?php

namespace Database\Factories;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domains\Staff\Models\Shift>
 */
class ShiftFactory extends Factory
{
    protected $model = Shift::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'shift_template_id' => null,
            'is_custom' => false,
            'date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
        ];
    }
}
