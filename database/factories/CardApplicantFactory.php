<?php

namespace Database\Factories;

use App\Models\CardApplicant;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends Factory<CardApplicant>
 */
class CardApplicantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['department' => "string", 'first_year' => "string", 'cellphone' => "int"])]
    public function definition(): array
    {
        return [
            'first_year' => $this->faker->dateTimeBetween('-10 years', 'now')->format('Y'),
//            'cellphone' => $this->faker->e164PhoneNumber(),
        ];
    }
}
