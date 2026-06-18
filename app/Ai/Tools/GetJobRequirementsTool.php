<?php

namespace App\Ai\Tools;

use App\Models\Offre;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetJobRequirementsTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Récupérer les exigences du poste — Récupère les détails d\'une offre d\'emploi, y compris les compétences requises, la description et l\'expérience minimale.';
    }

    public function handle(Request $request): Stringable|string
    {
        $offre = Offre::findOrFail($request['offreId']);

        return $offre->toJson();
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'offreId' => $schema->integer()->required()->description('L\'ID de l\'offre d\'emploi'),
        ];
    }
}
