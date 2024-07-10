<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Paste;


class PasteFactory extends Factory
{
    protected $model = Paste::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'visibility' => $this->faker->randomElement(['public', 'private', 'unlisted']),
            'expires_at' => $this->faker->dateTimeBetween('+1 day', '+1 year'),
            'password' => null,
            'file' => null,
        ];
    }
}
