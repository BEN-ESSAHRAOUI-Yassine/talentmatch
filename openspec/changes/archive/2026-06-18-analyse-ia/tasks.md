## 1. Routes & Scaffolding

- [x] 1.1 Add candidat resource routes (store + show) nested under offres in `routes/web.php` inside the auth+verified group
- [x] 1.2 Create `CandidatController` with `store` and `show` methods using `php artisan make:controller`

## 2. Form Request

- [x] 2.1 Create `StoreCandidatRequest` with `php artisan make:request StoreCandidatRequest` — validate `name` (required, string, max:255) and `cv_text` (required, string, min:50)

## 3. Policy Classes

- [x] 3.1 Create `CandidatPolicy` with `create` (offre ownership) and `view` (offre ownership through analyse) methods
- [x] 3.2 Create `AnalysePolicy` with `view` method (offre ownership chain)
- [x] 3.3 Register both policies in `AuthServiceProvider` or via model guessing (auto-discovered in Laravel 11)

## 4. Controller Implementation

- [x] 4.1 Implement `store` in `CandidatController`: create candidat + analyse with `status=pending`, dispatch `AnalyseCvJob`, redirect to `candidats.show`
- [x] 4.2 Implement `show` in `CandidatController`: load candidat with analyse, pass to view
- [x] 4.3 Add `analyse()` relation to `Candidat` model (HasOne) if not already present (already existed)
- [x] 4.4 Add `candidats()` relation to `Offre` model (HasMany through analyses)

## 5. Views

- [x] 5.1 Create `candidats/show.blade.php` — display candidate info + analysis results with three status states (pending spinner, completed results with color-coded recommendation badge, failed error message)
- [x] 5.2 Add "Soumettre un CV" section to `offres/show.blade.php` — link or inline form pointing to candidat store
- [x] 5.3 Create `candidats/create.blade.php` (or inline form on offre.show) — name + cv_text textarea + submit button

## 6. AI Job — AnalyseCvJob

- [x] 6.1 Create `AnalyseCvJob` with `php artisan make:job AnalyseCvJob` — receives `offre_id`, `candidat_id`, `analyse_id`
- [x] 6.2 Create the structured output agent with `php artisan make:agent AnalyseCvAgent --structured` using `laravel/ai`
- [x] 6.3 Implement `handle()` in AnalyseCvJob: load offre + candidat, call structured output agent with prompt containing offre requirements and CV text, update analyse with results
- [x] 6.4 Implement error handling in AnalyseCvJob: catch exceptions, set `status=failed` with `error_message`
- [x] 6.5 Add `$onQueue` or `implements ShouldQueue` to ensure async execution

## 7. Navigation & Offre Show Update

- [x] 7.1 Update `offres/show.blade.php` to list submitted candidates with links to their analysis pages
- [x] 7.2 Add "Soumettre un CV" button/link on the offre show page

## 8. Testing

- [x] 8.1 Create `CandidatControllerTest` with pest tests for: guest redirect, store valid submission, store validation errors (missing name, short cv_text), store 403 for other user's offre, store 404 for non-existent offre, show completed analysis, show pending state, show failed state, show 403, show 404
- [x] 8.2 Create `AnalyseCvJobTest` with pest tests for: job processes successfully, job marks failed on exception, job is queued (not synchronous)
- [x] 8.3 Create `StoreCandidatRequestTest` with pest tests for validation rules (name required, cv_text required, cv_text min:50)
- [x] 8.4 Add `candidats` factory state or ensure existing factories cover test data (covered by existing factories)

## 9. Finalization

- [ ] 9.1 Run `php artisan test --compact` — all tests passing
- [ ] 9.2 Run `vendor/bin/pint --dirty --format agent`
- [ ] 9.3 Verify no N+1 queries (eager load analyse when showing candidat)
- [ ] 9.4 Create Jira Sous-tâches for every task above under TA-6
- [ ] 9.5 Transition TA-6 to En cours
