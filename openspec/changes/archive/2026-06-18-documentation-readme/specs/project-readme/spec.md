# Project README — TA-9

## What Will Be Documented

Root `README.md` covering the full project lifecycle: onboarding, architecture, workflow, and contribution model. The README serves as the single entry point for any developer or reviewer approaching the codebase.

## Sections

### 1. Project Overview
- What TalentMatch is: un assistant IA de présélection RH
- Problem it solves: analyse structurée de CV avec agents conversationnels
- Key features: structured output analysis (AnalyseCvJob), conversational agent with memory, job/candidate management

### 2. Tech Stack
Package versions sourced from `openspec/config.yaml` and `composer.json`:

| Layer | Package | Version |
|-------|---------|---------|
| Langage | PHP | 8.3 |
| Framework | Laravel | 13 |
| AI SDK | Laravel AI | 0.x |
| Agent Framework | Laravel Boost | 2.x |
| Auth | Laravel Breeze (Blade+Alpine) | 2.x |
| Frontend | Tailwind CSS | 3.x |
| Frontend | Alpine.js | 3.x |
| Testing | Pest / PHPUnit | 4.x / 12.x |
| Debug | Laravel Telescope | 5.x |
| Logs | Laravel Pail | 1.x |
| Linter | Laravel Pint | 1.x |

### 3. Prerequisites
- PHP 8.3 (XAMPP)
- Composer
- Node.js & npm
- MySQL (via XAMPP)
- Git

### 4. Local Setup
Step-by-step:
```
git clone <repo>
cd talentmatch
composer install
npm install && npm run build
cp .env.example .env
```
Required `.env` keys:
- `GROQ_API_KEY` — API key for LLM provider
- `DB_*` — MySQL connection (default: `talentmatch`, user `root`, no password)
- `QUEUE_CONNECTION=database` — async job processing
- `MAIL_*` — dummy values (MAIL_MAILER=log for dev)

Post-config:
```
php artisan key:generate
php artisan vendor:publish --provider="Laravel\Ai\AiServiceProvider"
php artisan migrate
php artisan queue:work
```

Archive config, assets, etc: `php artisan storage:link`

### 5. Database Structure
- Conceptual Data Model (MCD) and Logical Data Model (MLD) available as images:
  - `public/images/mcd.png`
  - `public/images/mld.png`
- Referenced in the README via relative URL: `public/images/mcd.png` / `public/images/mld.png`
- Key tables: `users`, `jobs`, `candidates`, `analyses`, `conversations`, `messages`, `job_user` (pivot)
- RDV (`rendez-vous`) for interview scheduling

### 6. AI Architecture
Two-layer architecture:

**Layer 1 — Structured Output**
- `app/Ai/Providers/AnalyseCv` — JSON schema / prompt
- `app/Jobs/AnalyseCvJob` — queued async job
- Queue worker picks up jobs, calls LLM, stores structured result
- Uses `Laravel\Ai\AiServiceProvider`

**Layer 2 — Conversational Agent**
- `app/Ai/Agents/` — agent classes
- `RemembersConversations` trait — conversation memory
- `forUser()` scope — user-scoped context
- `continue()` method — multi-turn conversation
- Tools in `app/Ai/Tools/`

### 7. Workflow
**OpenSpec Cycle:**
1. `/opsx:propose <name>` — create change with proposal → specs → design → tasks
2. Apply — implement tasks
3. Verify — run tests
4. Archive — `openspec-archive-change` skill

**Branch Strategy:**
- Branches: `featureAI/{kebab-case-title}`
- NEVER push to main. User manages main.
- PRs merge to main via GitHub interface.

**Commit Messages:**
- Concise, imperative mood
- Reference TA issue when applicable

### 8. Running Tests
```
php artisan test --compact
```
**Mocking pattern — no real API calls:**
```php
Agent::fake();
```
All AI tests use `Agent::fake()` to avoid hitting real LLM endpoints.

### 9. Project Structure
```
app/
  Ai/
    Agents/          — conversational agent classes
    Providers/       — LLM providers (AnalyseCv, etc.)
    Tools/           — agent tool definitions
  Jobs/
    AnalyseCvJob.php — async structured analysis
config/
  ai.php            — AI SDK configuration
openspec/
  changes/          — archived change directories
  config.yaml       — central project config (naming, routing, permissions)
  specs/            — feature specifications
resources/views/    — Blade templates
routes/             — web routes
tests/              — Pest feature/unit tests
```

### 10. Environment Variables Reference

| Variable | Required | Default | Description |
|----------|----------|---------|-------------|
| `GROQ_API_KEY` | Yes | — | LLM provider API key |
| `DB_DATABASE` | No | talentmatch | MySQL database name |
| `DB_USERNAME` | No | root | MySQL user |
| `DB_PASSWORD` | No | — | MySQL password |
| `QUEUE_CONNECTION` | No | database | Queue driver |
| `MAIL_MAILER` | No | log | Mail driver for dev |
| `JIRA_TOKEN` | Yes (contributors) | — | Jira API token for OpenCode |

### 11. Changelog
- Reference to `openspec/changes/` in lieu of a separate `CHANGELOG.md`
- Each change directory documents what was built, why, and what files changed
- Navigate by date or feature name

## Edge Cases
| Cas | Comportement |
|-----|-------------|
| `.env` is missing `GROQ_API_KEY` | README warns that AI features will be degraded |
| Queue worker not running | README documents async nature; jobs sit pending until worker starts |
| XAMPP MySQL not running | README covers XAMPP prerequisites |
| Contributor has no Jira access | README documents that OpenSpec is file-based and doesn't require Jira |

## Tests
- No tests needed for README.md (documentation only)
- Verify with: `cat README.md` and manual review of all sections
- No test file changes required

## Dépendances
- Dépend de : TA-1 (Database Setup), TA-2 (CV Submission), TA-3 (Authentication), TA-4 (AI Analysis), TA-5 (Offres CRUD), TA-6 (Conversational Agent) — toutes les fonctionnalités principales sont terminées avant que la doc puisse les référencer
- Bloque : TA-10 (Présentation) — la README sert de base pour la présentation
