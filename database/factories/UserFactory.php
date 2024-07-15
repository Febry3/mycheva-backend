<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'username' => fake()->userName(),
            'email' => fake()->email(),
            'password'=> fake()->password(),
            'divisi' => fake()->sentence(10),
            'fakultas' => fake()->sentence(10),
            'prodi' => fake()->sentence(10),
            'url_foto_profle' => fake()->sentence(10),
            'nim' => fake()->sentence(10),
            'remember_token' => fake()->sentence(10)
        ];
    }
}
