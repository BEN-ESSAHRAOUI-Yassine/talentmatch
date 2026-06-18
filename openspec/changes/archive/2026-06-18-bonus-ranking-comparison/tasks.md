## 1. Routing

- [x] 1.1 Add `GET /offres/{offre}/candidats/classement` named `candidats.classement` in `routes/web.php` pointing to `CandidatComparisonController@classement`
- [x] 1.2 Add `GET /offres/{offre}/candidats/comparer` named `candidats.comparer` in `routes/web.php` pointing to `CandidatComparisonController@comparer`

## 2. Form Request

- [x] 2.1 Create `CompareCandidatesRequest` extending `FormRequest`: authorize() checks `$this->route('offre')->user_id === auth()->id()`, rules() validates `ids` is required, array, size:2, each `ids.*` is integer|exists:candidats,id. Add `withValidator()` for custom rules: both candidats belong to the same offre, both analyses have status=completed. Add `messages()` with French error messages.

## 3. Controller

- [x] 3.1 Create `CandidatComparisonController` with `classement(Offre $offre)` action: authorize via Gate/OffrePolicy, load analyses with candidat, sort completed by matching_score desc then pending then failed, return view `candidats.classement` with `offre` and `analyses`
- [x] 3.2 Add `comparer(Offre $offre, CompareCandidatesRequest $request)` action: authorize via Gate/OffrePolicy, load both analyses with eager-loaded candidat, return view `candidats.comparer` with `offre`, `candidat1`/`analyse1`, `candidat2`/`analyse2`, `bestScore` (which ID has higher matching_score)

## 4. Views

- [x] 4.1 Create `candidats/classement.blade.php` extending `layouts/app`: table with columns — checkbox, candidate name, matching score (rounded badge 0-100 with color: green >=70, yellow >=40, red <40), recommendation badge (color-coded), top 3 points_forts, top 3 lacunes. Sort rows: completed first (by score desc), then pending (with "Analyse en cours…" spinner), then failed (error badge + "Réanalyser" link to `analyses.retry`). Empty state: "Aucun candidat soumis" with link to `candidats.create`. Breadcrumb: Offre → Classement.
- [x] 4.2 Add Alpine.js data (`x-data`) on the table: `selectedIds` as array, `toggle(id)` method, `isSelected(id)` getter. Add checkbox inputs on each completed row. Add floating "Comparer" button (`x-show="selectedIds.length === 2"`) that navigates to `route('candidats.comparer', [...ids])` via Livewire/standard link with query params.
- [x] 4.3 Create `candidats/comparer.blade.php` extending `layouts/app`: two-column card layout. Left = candidate 1, Right = candidate 2. Each card shows: name (large), matching score (big number + colored progress bar), recommendation badge, "Meilleur score" badge on the higher scorer, all AI fields in `dl/dt/dd` pairs: competences_extraites (comma list), annees_experience, niveau_etudes, langues (comma list), points_forts (ul), lacunes (ul), competences_manquantes (ul), justification. Breadcrumb: Offre → Classement → Comparaison.
- [x] 4.4 Add "Voir le classement" link in `offres/show.blade.php` inside the "Candidats soumis" section header, next to "Soumettre un CV" button. Point to `route('candidats.classement', $offre)`.

## 5. Tests

- [x] 5.1 Create `tests/Feature/CandidatRankingTest.php` with scenarios: guest redirected to login, ranking shows completed sorted by score, ranking shows pending with spinner text, ranking shows failed with error and retry link, ranking shows empty state when no candidates, ranking returns 403 for another user's offre, ranking returns 404 for non-existent offre, ranking shows compare button when 2 selected, ranking hides compare button when <2 or >2 selected
- [x] 5.2 Create `tests/Feature/CandidatComparisonTest.php` with scenarios: guest redirected to login, comparison displays two analyses side-by-side (assertSee both names), comparison shows "Meilleur score" badge on higher scorer, comparison rejects non-numeric IDs (validation error), comparison rejects wrong count of IDs (validation error), comparison rejects candidates from different offres (422), comparison rejects non-completed analyses (422), comparison returns 403 for another user's offre, comparison returns 404 for non-existent candidate

## 6. DoD Checklist

- [x] 6.1 Run `php artisan test --compact` — all tests pass (143/143)
- [x] 6.2 Run `vendor/bin/pint --format agent` — no style issues
- [x] 6.3 Debugbar check — no N+1 queries on classement or comparer pages (verify `with('candidat')` eager loading on all analysis queries)
- [x] 6.4 Archive spec via `/opsx-archive`
- [x] 6.5 Transition TA-8 and all Sous-tâches to TERMINE (transition id 41)
- [ ] 6.6 Commit with message `feat(bonus): implement candidate ranking and comparison views [AI-assisted]` and push to `featureAI/bonus-ranking-comparison`
