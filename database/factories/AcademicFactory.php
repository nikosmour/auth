<?php

namespace Database\Factories;

use App\Enum\UserStatusEnum;
use App\Models\Academic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Academic>
 */
class AcademicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'academic_id' => $this->faker->unique()->creditCardNumber,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'status' => UserStatusEnum::UNDERGRADUATE,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'a_m' => $this->faker->unique()->numberBetween('1000000', '9999999'),
            'is_active' => $this->faker->boolean
        ];
    }
}
