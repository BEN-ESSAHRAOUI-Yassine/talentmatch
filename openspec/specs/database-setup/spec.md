# database-setup Specification

## Purpose
TBD - created by archiving change database-setup. Update Purpose after archive.
## Requirements
### Requirement: Migration create_offres_table

The system MUST have an `offres` table with columns: id (PK), user_id (FK→users), title, description, required_skills (JSON), minimum_experience (integer), created_at, updated_at.

#### Scenario: Offres table has correct schema
- **WHEN** running `php artisan migrate`
- **THEN** the `offres` table SHALL exist with the specified columns and foreign key to `users`

### Requirement: Migration create_candidats_table

The system MUST have a `candidats` table with columns: id (PK), offre_id (FK→offres), name, cv_text (text), created_at, updated_at.

#### Scenario: Candidats table has correct schema
- **WHEN** running `php artisan migrate`
- **THEN** the `candidats` table SHALL exist with the specified columns and foreign key to `offres`

### Requirement: Migration create_analyses_table

The system MUST have an `analyses` table with columns: id (PK), offre_id (FK→offres), candidat_id (FK→candidats, unique), status (string enum), matching_score (integer, nullable), competences_extraites (JSON, nullable), annees_experience (integer, nullable), niveau_etudes (string, nullable), langues (JSON, nullable), points_forts (JSON, nullable), lacunes (JSON, nullable), competences_manquantes (JSON, nullable), recommandation (string enum, nullable), justification (text, nullable), error_message (text, nullable), created_at, updated_at.

#### Scenario: Analyses table has correct schema
- **WHEN** running `php artisan migrate`
- **THEN** the `analyses` table SHALL exist with the specified columns and foreign keys

#### Scenario: candidat_id is unique in analyses
- **WHEN** inserting two analyses with the same candidat_id
- **THEN** the database SHALL reject the second insert

### Requirement: Migration create_agent_conversations_table

The system MUST have an `agent_conversations` table with columns: id (PK), analyse_id (FK→analyses, unique), created_at, updated_at.

#### Scenario: AgentConversations table has correct schema
- **WHEN** running `php artisan migrate`
- **THEN** the `agent_conversations` table SHALL exist with the specified columns and foreign key

### Requirement: Migration create_agent_conversation_messages_table

The system MUST have an `agent_conversation_messages` table with columns: id (PK), agent_conversation_id (FK→agent_conversations), role (string enum), content (text), created_at, updated_at.

#### Scenario: AgentConversationMessages table has correct schema
- **WHEN** running `php artisan migrate`
- **THEN** the `agent_conversation_messages` table SHALL exist with the specified columns and foreign key

### Requirement: Enums defined

The system MUST define three backed string enums: RecommandationEnum (convoquer/attente/rejeter), AnalyseStatusEnum (pending/completed/failed), MessageRoleEnum (user/assistant).

#### Scenario: Enums have correct values
- **WHEN** referencing each enum case
- **THEN** the values SHALL match the specification

### Requirement: Eloquent models with relationships

Each entity MUST have an Eloquent model with correct fillable/hidden attributes, casts, and relationships as specified in config.yaml.

#### Scenario: Offre model has correct relationships
- **WHEN** calling `$offre->candidats`
- **THEN** it SHALL return the related candidats through analyses

#### Scenario: Analyse model has correct casts
- **WHEN** accessing analyse attributes
- **THEN** JSON columns SHALL be cast to array and enum columns to their enum types

### Requirement: Factories for all models

Each model MUST have a factory with sensible defaults and custom states for testing.

#### Scenario: Analyse factory has completed and failed states
- **WHEN** creating an analyse with `completed` state
- **THEN** status SHALL be 'completed' and AI fields SHALL be populated

### Requirement: Seeders for development data

A DatabaseSeeder SHALL call all individual model seeders, each creating sample records.

#### Scenario: Seeders run without error
- **WHEN** running `php artisan db:seed`
- **THEN** sample records SHALL be inserted for all models

