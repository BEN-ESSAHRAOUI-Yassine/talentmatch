<?php

namespace Database\Factories;

use App\Models\Offre;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OffreFactory extends Factory
{
    protected $model = Offre::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraphs(3, true),
            'required_skills' => fake()->randomElements(['PHP', 'Laravel', 'JavaScript', 'Vue.js', 'React', 'MySQL', 'Docker', 'Git', 'REST API', 'TypeScript'], random_int(3, 6)),
            'minimum_experience' => fake()->randomElement([1, 2, 3, 5]),
        ];
    }
}
