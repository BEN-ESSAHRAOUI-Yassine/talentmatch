## Context

HR agents currently navigate through offres → candidate detail pages one at a time. There is no aggregate view of how all candidates rank against a job offer. The `CompareCandidatesTool` already exists in the conversational agent (Layer 2) but is only accessible via the chat interface, not as a standalone web page. This change builds two complementary web UIs on top of existing infrastructure.

All data is already in the `analyses` table: `matching_score`, `competences_extraites`, `points_forts`, `lacunes`, `competences_manquantes`, `recommandation`, `justification`. No new migrations or model changes are needed.

Scoping is already handled by existing policies — analyses are only accessible when the parent offre belongs to `auth()->id()`.

## Goals / Non-Goals

**Goals:**
- Provide a ranked list of all candidates for an offre sorted by `matching_score` descending
- Allow HR agents to select two candidates and see a side-by-side comparison of their analyses
- Leverage existing model relationships, policies, and routes patterns
- Keep each feature behind a single controller action with a dedicated view

**Non-Goals:**
- Creating a new AI agent or tool — the `CompareCandidatesTool` already exists and is reused
- Changing the data model — no migrations
- Adding sorting/filtering beyond score-based ranking
- Export or CSV download

## Decisions

### Decision: Dedicated comparison controller vs extending CandidatController

**Chosen:** A dedicated `CandidatComparisonController` with a single `__invoke` action.

The comparison view has distinctly different validation (requires exactly 2 candidate IDs, must belong to same offre, must have completed analyses) and rendering logic (side-by-side layout). A dedicated controller keeps `CandidatController` focused on single-candidate CRUD and avoids conditionals.

Alternatively, adding a `compare` method to `CandidatController` would work but would conflate two different resource representations.

### Decision: Route URL structure

**Chosen:** `GET /offres/{offre}/candidats/classement` for the ranking view.

This matches the French UI language ("classement" = ranking) used throughout the app. For the comparison, `GET /offres/{offre}/candidats/comparer?ids[]=1&ids[]=2` — query params keep the route RESTful since comparison is a filtered view of the candidate collection.

Following existing pattern: `offres.show` already has `candidats.store` nested under it.

### Decision: Comparison data source

**Chosen:** Direct Eloquent queries rather than `CompareCandidatesTool`.

The `CompareCandidatesTool` is designed for the conversational agent — it throws exceptions for cross-offre comparisons and returns a diff array. For the web UI, we need the raw analysis records to render side-by-side. Loading both analyses with `eagerLoad('candidat')` gives us everything we need: names, scores, all AI fields. Validation (same offre, both completed) is done in a FormRequest.

The tool is still available via the chat interface if users want a natural-language comparison.

### Decision: Ranking view displays all candidates regardless of status

**Chosen:** Show all candidates, grouped: completed analyses first (sorted by score), then pending, then failed. This gives HR agents full visibility. Pending items show a spinner, failed items show an error badge and retry link (using existing route `analyses.retry`).

## Risks / Trade-offs

- **Large candidate pools**: If an offre has 100+ candidates, the ranking page could be slow. Mitigation: Use `withCount` on analyses for now; pagination can be added later if needed.
- **Comparison on non-completed analyses**: If one candidate's analysis is still pending, the comparison page shows a clear message instead of partial data. Validation in the FormRequest prevents navigating to the comparison with non-completed analyses.
- **Race condition**: User opens ranking, a new candidate is submitted, scores shift. Mitigation: Acceptable trade-off; data is always read fresh from DB on each request. No caching is used.
- **Query param injection**: The `ids[]` query params are validated in a FormRequest (must be exactly 2, must be integers, must exist, must belong to the same offre).
