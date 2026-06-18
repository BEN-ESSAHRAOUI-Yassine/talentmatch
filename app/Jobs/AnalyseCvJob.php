<?php

namespace App\Jobs;

use App\Ai\Agents\AnalyseCvAgent;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AnalyseCvJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $offreId,
        public int $candidatId,
        public int $analyseId,
    ) {}

    public function handle(): void
    {
        $analyse = Analyse::findOrFail($this->analyseId);
        $offre = Offre::findOrFail($this->offreId);
        $candidat = Candidat::findOrFail($this->candidatId);

        try {
            $agent = new AnalyseCvAgent(
                description: $offre->description,
                requiredSkills: $offre->required_skills ?? [],
                minimumExperience: $offre->minimum_experience,
                cvText: $candidat->cv_text,
            );

            $response = $agent->prompt('Analyse this CV against the job offer and return the structured analysis.');

            $analyse->update([
                'status' => 'completed',
                'competences_extraites' => $response['competences_extraites'],
                'annees_experience' => $response['annees_experience'],
                'niveau_etudes' => $response['niveau_etudes'],
                'langues' => $response['langues'],
                'matching_score' => $response['matching_score'],
                'points_forts' => $response['points_forts'],
                'lacunes' => $response['lacunes'],
                'competences_manquantes' => $response['competences_manquantes'],
                'recommandation' => $response['recommandation'],
                'justification' => $response['justification'],
            ]);
        } catch (\Throwable $e) {
            $analyse->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
