<?php

namespace Database\Factories;

use App\Enums\AnalyseStatusEnum;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnalyseFactory extends Factory
{
    protected $model = Analyse::class;

    public function definition(): array
    {
        return [
            'offre_id' => Offre::factory(),
            'candidat_id' => Candidat::factory(),
            'status' => AnalyseStatusEnum::Pending,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AnalyseStatusEnum::Completed,
            'competences_extraites' => fake()->randomElements(['PHP', 'Laravel', 'MySQL', 'JavaScript', 'Git'], random_int(2, 5)),
            'annees_experience' => fake()->numberBetween(1, 10),
            'niveau_etudes' => fake()->randomElement(['Bac+2', 'Bac+3', 'Bac+5', 'Ingénieur', 'Master']),
            'langues' => fake()->randomElements(['Français', 'Anglais', 'Arabe', 'Espagnol'], random_int(1, 3)),
            'matching_score' => fake()->numberBetween(30, 100),
            'points_forts' => fake()->randomElements(['Expérience en équipe', 'Autonomie', 'Proactivité', 'Leadership', 'Rigueur'], random_int(2, 4)),
            'lacunes' => fake()->randomElements(['Manque d\'expérience en gestion', 'Compétences techniques à renforcer', 'Absence de certification'], random_int(1, 2)),
            'competences_manquantes' => fake()->randomElements(['Docker', 'Kubernetes', 'React', 'TypeScript', 'AWS'], random_int(1, 3)),
            'recommandation' => fake()->randomElement(['convoquer', 'attente', 'rejeter']),
            'justification' => fake()->paragraph(),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AnalyseStatusEnum::Failed,
            'error_message' => fake()->sentence(),
        ]);
    }
}
