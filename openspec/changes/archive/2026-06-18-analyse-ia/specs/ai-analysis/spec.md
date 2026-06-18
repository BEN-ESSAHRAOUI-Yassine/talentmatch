## ADDED Requirements

### Requirement: AnalyseCvJob runs AI structured output asynchronously

When a candidat is submitted, the system SHALL dispatch an `AnalyseCvJob` to the `database` queue. The job SHALL use `laravel/ai` structured output agent to analyze the CV text against the offre's requirements and populate the analyse record.

#### Scenario: Job extracts structured data on success
- **WHEN** `AnalyseCvJob` runs with a valid offre, candidat, and analyse
- **THEN** the job SHALL create a structured output agent calling Groq via `laravel/ai`
- **AND** the agent SHALL receive the offre's description, required_skills, and minimum_experience along with the candidat's cv_text
- **AND** on success, the analyse SHALL be updated: `status=completed`, all AI fields populated (competences_extraites, annees_experience, niveau_etudes, langues, matching_score, points_forts, lacunes, competences_manquantes, recommandation, justification)

#### Scenario: Job marks analyse as failed on malformed AI response
- **WHEN** the AI returns malformed JSON or the agent throws an exception
- **THEN** the analyse SHALL be updated: `status=failed`, `error_message` populated with the error description
- **AND** the exception SHALL NOT propagate to the HTTP thread

#### Scenario: Job handles empty required_skills gracefully
- **WHEN** the offre has no required_skills
- **THEN** the agent SHALL still run the analysis
- **AND** the matching_score SHALL naturally be lower due to lack of matching criteria

#### Scenario: Job never runs synchronously
- **WHEN** AnalyseCvJob is dispatched
- **THEN** the job SHALL be queued (database connection)
- **AND** the HTTP response SHALL be immediate — no AI call on the HTTP thread

### Requirement: AI structured output contract

The structured output agent SHALL produce data matching the Analyse model schema.

#### Scenario: Structured output matches all fields
- **WHEN** the AI agent produces output
- **THEN** the output SHALL include: competences_extraites (array of strings), annees_experience (integer), niveau_etudes (string), langues (array of strings), matching_score (integer 0-100), points_forts (array of strings), lacunes (array of strings), competences_manquantes (array of strings), recommandation (convoquer|attente|rejeter), justification (string)
