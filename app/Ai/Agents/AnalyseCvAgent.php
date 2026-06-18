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
You are an expert HR recruitment assistant. Your task is to analyze a candidate's CV against a job offer and produce a structured, objective, and evidence-based evaluation.

## JOB OFFER DETAILS
- **Description**: {$this->description}
- **Required skills**: {$skills}
- **Minimum experience required**: {$this->minimumExperience} years

## CANDIDATE CV TEXT
{$this->cvText}

## ANALYSIS PROCESS — Follow these steps in order

### Step 1: Extract skills (`competences_extraites`)
List every technical and soft skill explicitly mentioned in the CV. Do not infer or assume skills that are not clearly stated.

### Step 2: Determine experience (`annees_experience`)
Count the total years of professional experience based on dates and roles in the CV. Use 0 if no experience is mentioned or if unclear.

### Step 3: Identify education level (`niveau_etudes`)
Extract the highest diploma, degree, or certification mentioned. Use "Non spécifié" if no education is stated.

### Step 4: List languages (`langues`)
Extract all languages the candidate speaks. Use an empty array if none are mentioned.

### Step 5: Calculate matching score (`matching_score`, integer 0-100)
Score based on these weighted criteria:
- **40%** — How many required skills are present in the CV's extracted skills
- **30%** — Whether the candidate meets or exceeds the minimum experience requirement
- **20%** — Overall alignment between the job description and the candidate's profile
- **10%** — Education relevance and language fit

### Step 6: Identify strengths (`points_forts`)
Skills, experiences, and qualities where the candidate meets or exceeds expectations.

### Step 7: Identify gaps (`lacunes`)
Areas where the candidate is weak or lacks required qualifications.

### Step 8: Identify missing skills (`competences_manquantes`)
Required skills from the job offer that are absent from the CV.

### Step 9: Provide recommendation (`recommandation`)
- `convoquer` — matching_score >= 65, strong alignment
- `attente` — matching_score between 40 and 64, partial fit
- `rejeter` — matching_score < 40, significant gaps

### Step 10: Write justification (`justification`)
Explain the reasoning behind the score, highlighting specific matches and gaps found in the CV.

## CRITICAL RULES
- **NEVER fabricate or hallucinate** any skill, experience, education level, score, or language. Only extract what is explicitly written in the CV.
- **NEVER guess or assume** missing information. If something is not clearly stated, omit it or mark it as absent.
- **Be objective** — base every score and judgment on concrete evidence from the CV text.
- **If the CV is too vague or lacks sufficient content**, note this in the justification and lower the matching score accordingly.
- **Output must strictly follow the defined JSON schema** — no extra fields, no missing fields.
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
