## Context

- `Analyse` and `Candidat` models already exist from TA-2 (migrations, enums, factories)
- `Offre` resource is fully implemented (TA-3 completed) with policy, controller, views, tests
- `Offre` has `analyses()` HasMany and cascade delete — removing an offre removes its analyses
- `Candidat` belongs to `Analyse` via `candidat_id` (unique constrained — one analysis per candidate)
- `laravel/ai` SDK installed with Groq provider and structured output agent support
- Queue connection configured as `database`

## Goals / Non-Goals

**Goals:**
- Allow HR agent to submit a candidate (name + CV text) against a specific offre
- Run AI structured output analysis asynchronously via queued job
- Display analysis results with status-aware UI (pending / completed / failed)
- Enforce ownership through `offre → user` chain via policies

**Non-Goals:**
- Conversation assistant (Layer 2) — this is TA-7, separate change
- Ranking or comparison between candidates — this is TA-8
- CV file uploads — only plain text submission via textarea
- Multiple analyses per candidate — schema enforces 1:1 via unique constraint

## Decisions

| Decision | Choice | Rationale |
|---|---|---|
| **Candidat store route** | Nested `POST /offres/{offre}/candidats` | Ownership is inherited from the offre in the URL; no `user_id` field needed on candidat |
| **Candidat show route** | Nested `GET /offres/{offre}/candidats/{candidat}` | Consistent nesting; policy checks offre ownership first |
| **Analyse display** | Shown on `candidats.show` page alongside candidate info | The analyse is fundamentally tied to the candidate; no separate analyse route needed at this stage |
| **AI layer** | `laravel/ai` structured output agent via `Agent::fake()` in tests | Already installed in the stack; `Agent::fake()` auto-generates valid data matching the schema — no real API calls in tests |
| **Queue** | `AnalyseCvJob` dispatched to `database` queue | Async by mandate (config.yaml: "NEVER run AI calls synchronously"); existing `database` connection |
| **Job data** | Receives `offre_id`, `candidat_id`, `analyse_id` | Sufficient to load both the offre (for requirements) and candidat (for CV text) inside the job |
| **Status flow** | `pending` → dispatch → `completed` or `failed` | User sees `pending` immediately after form submission; status updates on queue worker run |
| **No Analyse controller** | CandidatController handles both store and show | No separate analyse CRUD operations needed at this stage — analyse lifecycle is fully async |
| **Policies** | `CandidatPolicy` + `AnalysePolicy` | Both check `$user->id === $analyse->offre->user_id` traversal; consistent with OffrePolicy pattern |
| **Form request** | `StoreCandidatRequest` | Validates name (required, string, max:255) and cv_text (required, string, min:50) |

## Risks / Trade-offs

| Risk | Mitigation |
|---|---|
| **AI returns malformed JSON** | AnalyseCvJob catches exceptions, sets `status=failed`, stores `error_message` for UI display |
| **Queue worker not running** | `status=pending` persists until worker picks it up; UI shows clear "pending" state with auto-refresh hint |
| **Groq API key missing** | Job will fail with clear error message; `laravel/ai` surfaces configuration errors as exceptions |
| **N+1 on candidats list** | Not applicable yet — no index listing of candidats; existing `offre.show` can eager-load if needed later |
| **Unique constraint on candidat_id** | Second submission for same candidate would fail at DB level; handle with validation in store method or unique rule |
