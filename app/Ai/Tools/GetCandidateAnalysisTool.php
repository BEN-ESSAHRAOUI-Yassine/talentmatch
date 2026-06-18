<?php

namespace App\Ai\Tools;

use App\Models\Analyse;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetCandidateAnalysisTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Analyser les candidatures — Récupère l\'analyse complète d\'un candidat à partir de son ID.';
    }

    public function handle(Request $request): Stringable|string
    {
        $analyse = Analyse::where('candidat_id', $request['candidatId'])->firstOrFail();

        return $analyse->toJson();
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'candidatId' => $schema->integer()->required()->description('L\'ID du candidat dont on veut récupérer l\'analyse'),
        ];
    }
}
