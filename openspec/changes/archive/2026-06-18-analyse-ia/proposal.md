## Why

HR agents currently have no way to submit CVs against a job offer for automated pre-screening. Without this, every CV must be read manually — negating the platform's core value proposition. TA-6 delivers the first AI interaction layer: CV submission, queued structured analysis, and result display.

## What Changes

- **Candidat submission**: HR agent visits an offre, submits a candidate name + CV text via a form. No `user_id` field — scoped through the offre in the route.
- **AI analysis (queued)**: After submission, `AnalyseCvJob` is dispatched to Groq via `laravel/ai` structured output agent. The job extracts competencies, experience, education, languages, matching score, strengths, gaps, and a recommendation. Runs async — never on the HTTP thread.
- **Analysis display**: HR agent can view the structured analysis result for each submitted candidate, including status (pending / completed / failed) and error messages on failure.
- **Policy authorization**: `AnalysePolicy` and `CandidatPolicy` enforce ownership through the parent offre chain. No inline auth checks.
- **Routes**: `POST /offres/{offre}/candidats`, `GET /offres/{offre}/candidats/{candidat}` added inside the auth+verified group.

## Capabilities

### New Capabilities
- `cv-submission`: Submit a candidate (name + CV text) against a job offer with validation, ownership scoping, and error handling
- `ai-analysis`: Queued AI structured output via `AnalyseCvJob` — extracts competencies, experience, score, and recommendation from CV text against offer requirements
- `analyse-display`: View the structured analysis result for a candidate, handling all three statuses (pending, completed, failed)

### Modified Capabilities
<!-- No existing capabilities are changing — this is the first AI interaction layer -->

## Impact

- **New controller**: `CandidatController` (store + show)
- **New form request**: `StoreCandidatRequest` (name required, cv_text required min 50 chars)
- **New job**: `AnalyseCvJob` (queued on `database` connection, receives offre_id + candidat_id + analyse_id)
- **New policies**: `CandidatPolicy`, `AnalysePolicy`
- **New views**: `candidats/create.blade.php` (form on offre show page), `candidats/show.blade.php` (analysis results)
- **New routes**: Nested under `offres/{offre}/candidats`
- **New tests**: `CandidatControllerTest`, `AnalyseCvJobTest`, `StoreCandidatRequestTest`
- **Modified files**: `routes/web.php` (add candidat routes), existing offre views (add "submit CV" button on show page)
- **AI dependency**: `GROQ_API_KEY` in `.env` — Groq is configured as the AI provider
