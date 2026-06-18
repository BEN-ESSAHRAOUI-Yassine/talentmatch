<?php

namespace Database\Factories;

use App\Models\AgentConversation;
use App\Models\Analyse;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgentConversationFactory extends Factory
{
    protected $model = AgentConversation::class;

    public function definition(): array
    {
        return [
            'analyse_id' => Analyse::factory()->completed(),
            'title' => fake()->sentence(3),
            'user_id' => fn (array $attributes) => Analyse::find($attributes['analyse_id'])->offre->user_id,
        ];
    }
}
