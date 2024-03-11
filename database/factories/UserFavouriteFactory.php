<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Favourite>
 */
class UserFavouriteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'url' => fake()->text(),
            'author' => fake()->name(),
            'description' => fake()->text(),
            'imageUrl' => fake()->text(),
            'userId' => ''
        ];
    }
}
