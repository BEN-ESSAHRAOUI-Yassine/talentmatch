# Database Setup — Design

## Schema

### Table: offres

| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint AI | PK |
| user_id | bigint | FK→users, cascade |
| title | varchar(255) | NOT NULL |
| description | text | NOT NULL |
| required_skills | json | NOT NULL |
| minimum_experience | int | NOT NULL, default 0 |
| created_at | timestamp | |
| updated_at | timestamp | |

### Table: candidats

| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint AI | PK |
| offre_id | bigint | FK→offres, cascade |
| name | varchar(255) | NOT NULL |
| cv_text | text | NOT NULL |
| created_at | timestamp | |
| updated_at | timestamp | |

### Table: analyses

| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint AI | PK |
| offre_id | bigint | FK→offres, cascade |
| candidat_id | bigint | FK→candidats, cascade, UNIQUE |
| status | varchar(255) | NOT NULL, AnalyseStatusEnum |
| matching_score | int | nullable |
| competences_extraites | json | nullable |
| annees_experience | int | nullable |
| niveau_etudes | varchar(255) | nullable |
| langues | json | nullable |
| points_forts | json | nullable |
| lacunes | json | nullable |
| competences_manquantes | json | nullable |
| recommandation | varchar(255) | nullable, RecommandationEnum |
| justification | text | nullable |
| error_message | text | nullable |
| created_at | timestamp | |
| updated_at | timestamp | |

### Table: agent_conversations

| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint AI | PK |
| analyse_id | bigint | FK→analyses, cascade, UNIQUE |
| created_at | timestamp | |
| updated_at | timestamp | |

### Table: agent_conversation_messages

| Column | Type | Constraints |
|--------|------|-------------|
| id | bigint AI | PK |
| agent_conversation_id | bigint | FK→agent_conversations, cascade |
| role | varchar(255) | NOT NULL, MessageRoleEnum |
| content | text | NOT NULL |
| created_at | timestamp | |
| updated_at | timestamp | |

## Eloquent Casts

- Offre: required_skills → array
- Analyse: competences_extraites, langues, points_forts, lacunes, competences_manquantes → array; recommandation → RecommandationEnum; status → AnalyseStatusEnum
- AgentConversationMessage: role → MessageRoleEnum
