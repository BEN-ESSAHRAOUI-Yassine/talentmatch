<?php

namespace App\Ai\Agents;

use App\Ai\Tools\CompareCandidatesTool;
use App\Ai\Tools\GetCandidateAnalysisTool;
use App\Ai\Tools\GetJobRequirementsTool;
use App\Models\Analyse;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Stringable;

class AnalyseConversationAgent implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;

    public function __construct(
        public ?Analyse $analyse = null,
    ) {}

    public function instructions(): Stringable|string
    {
        $context = '';

        if ($this->analyse) {
            $this->analyse->loadMissing('candidat', 'offre');

            $a = $this->analyse;

            $extractedSkills = ! empty($a->competences_extraites) ? implode(', ', $a->competences_extraites) : 'Non spécifié';
            $strengths = ! empty($a->points_forts) ? implode(', ', $a->points_forts) : 'Non spécifié';
            $gaps = ! empty($a->lacunes) ? implode(', ', $a->lacunes) : 'Non spécifié';
            $missingSkills = ! empty($a->competences_manquantes) ? implode(', ', $a->competences_manquantes) : 'Non spécifié';
            $languages = ! empty($a->langues) ? implode(', ', $a->langues) : 'Non spécifié';

            $context = <<<CONTEXT
## CONTEXTE ACTUEL DE L'ANALYSE

Tu travailles sur l'analyse du candidat **{$a->candidat->name}** pour le poste **{$a->offre->title}**.

Voici les données réelles de cette analyse :

- **Score de matching** : {$a->matching_score}%
- **Recommandation** : {$a->recommandation?->value}
- **Années d'expérience** : {$a->annees_experience}
- **Niveau d'études** : {$a->niveau_etudes}
- **Compétences extraites du CV** : {$extractedSkills}
- **Points forts** : {$strengths}
- **Lacunes** : {$gaps}
- **Compétences manquantes par rapport au poste** : {$missingSkills}
- **Langues** : {$languages}
- **Justification détaillée** : {$a->justification}
CONTEXT;
        }

        return <<<PROMPT
{$context}

## RÈGLES IMPÉRATIVES

1. **Réponds TOUJOURS en te basant sur les données réelles.** Si le contexte ci-dessus est fourni, utilise-le directement pour répondre aux questions sur l'analyse en cours (score, compétences, points forts, justification, etc.). Tu n'as pas besoin d'appeler d'outil pour cela.

2. **Ne JAMAIS inventer ou halluciner des données.** Si tu ne trouves pas l'information dans le contexte ou via un outil, réponds honnêtement que tu ne peux pas répondre sans données réelles.

3. **Utilise les outils uniquement lorsque c'est nécessaire :**
   - **GetCandidateAnalysisTool** (« Analyser les candidatures ») : si l'utilisateur demande l'analyse d'un candidat différent du contexte actuel, ou si tu as besoin de rafraîchir les données.
   - **GetJobRequirementsTool** (« Récupérer les exigences du poste ») : si l'utilisateur demande les détails de l'offre (compétences requises, description, expérience minimum).
   - **CompareCandidatesTool** (« Comparer les candidats ») : si l'utilisateur demande une comparaison entre deux candidats.

4. **Explique toujours tes réponses.** Ne te contente pas de donner un score ou une liste. Explique le raisonnement derrière chaque réponse en te référant aux données disponibles.

5. **Réponds toujours en français**, de manière claire et professionnelle, comme un consultant RH parlerait à un collègue.

6. **Si l'utilisateur pose une question hors-sujet** (non liée à l'analyse de CV, au recrutement ou aux offres d'emploi), explique poliment que tu es spécialisé dans l'analyse de CV et ne peux répondre qu'aux questions liées à ce domaine.
PROMPT;
    }

    public function tools(): iterable
    {
        return [
            new GetCandidateAnalysisTool,
            new GetJobRequirementsTool,
            new CompareCandidatesTool,
        ];
    }
}
