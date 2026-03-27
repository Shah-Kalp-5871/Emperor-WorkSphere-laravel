<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CalendarEvent>
 */
class CalendarEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'event_type' => fake()->randomElement(['holiday', 'office_off', 'meeting', 'deadline', 'announcement']),
            'start_date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'is_all_day' => true,
            'visible_to' => 'all',
            'created_by' => Admin::factory(),
        ];
    }
}
