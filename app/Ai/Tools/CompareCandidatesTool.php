<?php

namespace App\Ai\Tools;

use App\Models\Analyse;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class CompareCandidatesTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Comparer les candidats — Compare deux analyses de candidats pour une même offre et retourne un diff structuré.';
    }

    public function handle(Request $request): Stringable|string
    {
        $id1 = $request['candidatId1'];
        $id2 = $request['candidatId2'];

        $analyse1 = Analyse::where('candidat_id', $id1)->firstOrFail();
        $analyse2 = Analyse::where('candidat_id', $id2)->firstOrFail();

        if ($analyse1->offre_id !== $analyse2->offre_id) {
            throw new \InvalidArgumentException('Les deux candidats doivent appartenir à la même offre.');
        }

        $comparison = [
            'offre_id' => $analyse1->offre_id,
            'candidat_1' => [
                'candidat_id' => $id1,
                'matching_score' => $analyse1->matching_score,
                'points_forts' => $analyse1->points_forts,
                'lacunes' => $analyse1->lacunes,
                'competences_manquantes' => $analyse1->competences_manquantes,
                'annees_experience' => $analyse1->annees_experience,
                'niveau_etudes' => $analyse1->niveau_etudes,
                'recommandation' => $analyse1->recommandation?->value,
            ],
            'candidat_2' => [
                'candidat_id' => $id2,
                'matching_score' => $analyse2->matching_score,
                'points_forts' => $analyse2->points_forts,
                'lacunes' => $analyse2->lacunes,
                'competences_manquantes' => $analyse2->competences_manquantes,
                'annees_experience' => $analyse2->annees_experience,
                'niveau_etudes' => $analyse2->niveau_etudes,
                'recommandation' => $analyse2->recommandation?->value,
            ],
            'score_difference' => $analyse1->matching_score - $analyse2->matching_score,
        ];

        return json_encode($comparison);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'candidatId1' => $schema->integer()->required()->description('L\'ID du premier candidat'),
            'candidatId2' => $schema->integer()->required()->description('L\'ID du second candidat'),
        ];
    }
}
