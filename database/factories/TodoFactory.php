<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\TodoStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'due_on' => now()->addDay(5)->format('Y-m-d H:i:s'),
            'user_id' => User::factory()->create(),
            'title' => $this->faker->sentence,
            'status' => TodoStatusEnum::PENDING
        ];
    }

    public function completed(): Factory
    {
        return $this->state([
            'status' => TodoStatusEnum::COMPLETED
        ]);
    }

    public function pending(): Factory
    {
        return $this->state([
            'status' => TodoStatusEnum::PENDING
        ]);
    }
}
