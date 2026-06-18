<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;

class AnalyseCvAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        public string $description,
        public array $requiredSkills,
        public int $minimumExperience,
        public string $cvText,
    ) {}

    public function instructions(): string
    {
        $skills = implode(', ', $this->requiredSkills);

        return <<<PROMPT
You are an expert HR recruitment assistant. Analyse the following CV against the job offer criteria.

## Job Offer
Description: {$this->description}
Required skills: {$skills}
Minimum experience: {$this->minimumExperience} years

## CV Text
{$this->cvText}

Extract the candidate's information and provide a matching analysis. Be objective and base your analysis strictly on the CV content provided.
PROMPT;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'competences_extraites' => $schema->array()
                ->items($schema->string())
                ->required(),
            'annees_experience' => $schema->integer()->min(0)->required(),
            'niveau_etudes' => $schema->string()->required(),
            'langues' => $schema->array()
                ->items($schema->string())
                ->required(),
            'matching_score' => $schema->integer()->min(0)->max(100)->required(),
            'points_forts' => $schema->array()
                ->items($schema->string())
                ->required(),
            'lacunes' => $schema->array()
                ->items($schema->string())
                ->required(),
            'competences_manquantes' => $schema->array()
                ->items($schema->string())
                ->required(),
            'recommandation' => $schema->string()
                ->enum(['convoquer', 'attente', 'rejeter'])
                ->required(),
            'justification' => $schema->string()->required(),
        ];
    }
}
