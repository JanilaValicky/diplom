<?php

namespace Database\Factories;

use App\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Form>
 */
class FormFactory extends Factory
{
    protected $model = Form::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start_date = now()->subSeconds(rand(0, 86400 * 5));

        return [
            'name' => implode(' ', fake()->words(rand(1, 3))),
            'created_at' => now()->subSeconds(rand(86400 * 5, 86400 * 10)),
            'start_date' => $start_date,
            'slug' => fake()->unique()->slug(),
            'end_date' => now()->addSeconds(rand(0, 86400 * 30)),
            'deleted_at' => rand(0, 1) == 1 ? now()->subSeconds(rand(0, now()->second - $start_date->second)) : null,
        ];
    }
}
