<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SongFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(2, 3),
            'genre_id' => rand(1, 9),
            'name' => $this->faker->name(),
            'preview_image' => 'songs/default.png',
            'featuring_with' => $this->faker->name(),
            'producer' => $this->faker->name(),
            'text_written_by' => $this->faker->name(),
            'music_written_by' => $this->faker->name(),
            'mixed_by' => $this->faker->name(),
            'text' => $this->faker->sentences(rand(10, 15), true),
        ];
    }
}
