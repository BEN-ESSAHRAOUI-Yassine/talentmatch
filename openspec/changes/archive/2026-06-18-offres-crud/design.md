## Context

Offres CRUD is the first feature controller in TalentMatch. The `Offre` model, `offres` migration, factory, and seeder already exist from TA-2 (database setup). The app currently has only Breeze auth scaffolding. This change adds the first real feature interface.

All offres must be scoped to `auth()->id()` — no user can see another user's offres.

## Goals / Non-Goals

**Goals:**
- Full 7-action resource controller for Offre
- Form-level validation (StoreOffreRequest, UpdateOffreRequest)
- Policy-based authorization (OffrePolicy)
- Blade views with Tailwind CSS + Alpine.js (consistent with Breeze components)
- Feature tests for every action
- Dashboard integration with offre count

**Non-Goals:**
- Candidat management (future TA-6)
- AI analysis (future TA-6)
- Agent conversation (future TA-7)
- Soft deletes (not in config.yaml)
- API endpoints (web-only)

## Decisions

### 1. Resourceful route with all 7 actions
Use `Route::resource('offres', OffreController::class)` without restriction — all 7 actions are needed.

### 2. Form request inheritance
`UpdateOffreRequest` extends `StoreOffreRequest` since validation rules are identical per config.yaml.

### 3. View structure
Place all offre views under `resources/views/offres/` — matching resource convention. Use Breeze's existing `x-app-layout`, `x-nav-link`, `x-input-label`, `x-text-input`, `x-input-error` components.

### 4. Tag input for required_skills
Use a simple Alpine.js-powered text input where skills are comma-separated and rendered as removable badges. Alpine is already loaded in the app layout.

### 5. Eager loading
No eager loading needed for Offre CRUD alone — the model's `analyses` relation is only relevant for show view (future). The `offre.user` relation is implicitly handled by policy.

### 6. Pagination
Use `Offre::latest()->paginate(10)` — Laravel's default pagination with Tailwind-styled links.

### 7. Flash messages
Use `->with('success', ...)` in controller and display via a Blade partial.

## Risks / Trade-offs

| Risk | Mitigation |
|---|---|
| N+1 queries when candidats added to show view | Add `->with('analyses.candidat')` when that feature lands |
| User removes all skills during edit | Validation: `min:1` on `required_skills` in UpdateOffreRequest |
| Offre deletion silently removes analyses (cascade) | Mention in UI: "Cette action supprimera également les analyses associées." |
| Pagination breaks if view uses wrong CSS classes | Use `links()` with Tailwind-compatible paginator view |
