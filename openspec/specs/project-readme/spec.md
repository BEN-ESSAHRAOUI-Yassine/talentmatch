# Project README

## Purpose

The root `README.md` SHALL serve as the single entry point for any developer or reviewer approaching the codebase — covering onboarding, architecture, workflow, and contribution model.

## Requirements

### Requirement: Project overview section

The README SHALL describe what TalentMatch is (assistant IA de présélection RH), the problem it solves, and its key features (structured output analysis, conversational agent with memory, job/candidate management).

### Requirement: Tech stack section

The README SHALL list the full tech stack with package names and versions sourced from `openspec/config.yaml` and `composer.json`:

| Layer | Package | Version |
|-------|---------|---------|
| Langage | PHP | 8.3 |
| Framework | Laravel | 13 |
| AI SDK | laravel/ai | 0.8 |
| Agents | laravel/boost | 2.4 |
| Auth | laravel/breeze | 2.4 |
| CSS | Tailwind CSS | 3 |
| JS | Alpine.js | 3 |
| Tests | pestphp/pest | 4.7 |
| Debug | Laravel Telescope | 5.20 |
| Logs | Laravel Pail | 1.2 |
| Linter | Laravel Pint | 1.27 |

### Requirement: Prerequisites section

The README SHALL list prerequisites: PHP 8.3 (XAMPP), Composer, Node.js & npm, MySQL (via XAMPP), Git.

### Requirement: Local setup section

The README SHALL provide step-by-step setup instructions:

- Clone the repo, `composer install`, `npm install && npm run build`
- Copy `.env.example` to `.env` with required keys: `GROQ_API_KEY`, `DB_*`, `QUEUE_CONNECTION=database`, `MAIL_*`
- Post-config: `key:generate`, `vendor:publish AiServiceProvider`, `migrate`, `storage:link`
- `php artisan queue:work` — explain async nature

### Requirement: Database structure section

The README SHALL include MCD and MLD diagrams (`public/images/mcd.png`, `public/images/mld.png`) and list key tables with descriptions: `users`, `offres`, `candidats`, `analyses`, `agent_conversations`, `agent_conversation_messages`, `rdvs`.

### Requirement: AI architecture section

The README SHALL document the two-layer AI architecture:

**Layer 1 — Structured output**: `AnalyseCvJob`, JSON contract, async queue, error handling.

**Layer 2 — Conversational agent**: `RemembersConversations` trait, `forUser`/`continue` pattern, available tools (`GetCandidateAnalysisTool`, `GetJobRequirementsTool`, `CompareCandidatesTool`), rules (never fabricate data).

### Requirement: Workflow section

The README SHALL document the OpenSpec development workflow:

- **Cycle**: `/opsx:propose` → `/opsx:apply` → verify → `/opsx:archive`
- **Branch strategy**: `featureAI/{kebab-case-title}`, never push to main
- **Commit format**: `type(scope): description [AI-assisted]`

### Requirement: Running tests section

The README SHALL document how to run tests (`php artisan test --compact`) and the mocking pattern (`Agent::fake()` — no real API calls, `Queue::fake()` for jobs, model factories for test data).

### Requirement: Project structure section

The README SHALL show the key directory structure including `app/Ai/Agents/`, `app/Ai/Tools/`, `app/Jobs/AnalyseCvJob.php`, `openspec/changes/`, `openspec/config.yaml`, `resources/views/`, `routes/`, `tests/`.

### Requirement: Environment variables reference

The README SHALL include a table of all environment variables: `GROQ_API_KEY`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `QUEUE_CONNECTION`, `MAIL_MAILER` — with required/default/description columns.

### Requirement: Changelog section

The README SHALL reference `openspec/changes/` in lieu of a separate `CHANGELOG.md`, explaining each change directory documents what was built, why, and what files changed.

#### Scenario: README warns when GROQ_API_KEY is missing
- **WHEN** `.env` is missing `GROQ_API_KEY`
- **THEN** the README SHALL note that AI features will be degraded

#### Scenario: README documents async queue requirement
- **WHEN** the queue worker is not running
- **THEN** the README SHALL explain that submitted CVs will not be analyzed until the worker starts

#### Scenario: README documents XAMPP requirement
- **WHEN** MySQL is not running
- **THEN** the README SHALL cover XAMPP prerequisites for database setup

## Dépendances

- Dépend de : TA-1 (Database Setup), TA-2 (CV Submission), TA-3 (Authentication), TA-4 (AI Analysis), TA-5 (Offres CRUD), TA-6 (Conversational Agent)
- Bloque : TA-10 (Présentation)
