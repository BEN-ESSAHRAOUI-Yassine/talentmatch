## Why

HR agents need a complete interface to manage job offers (offres) — create, browse, view, edit, and delete — before submitting CVs for AI analysis. Currently only the `offres` table schema and `Offre` model exist; there are no controllers, views, routes, form requests, or policies.

## What Changes

- **OffreController** — full 7-action resource controller (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`)
- **StoreOffreRequest** / **UpdateOffreRequest** — form requests with validation per config.yaml
- **OffrePolicy** — scopes all queries to `auth()->id()`, returns 403 for unauthorized access
- **Blade views** — `index` (list + search/pagination), `create` (form), `show` (detail with link to future candidats), `edit` (pre-filled form)
- **Navigation** — "Offres" nav link in sidebar (`route('offres.index')`)
- **Routes** — resourceful `offres` group in `routes/web.php` under `auth` + `verified` middleware
- **Dashboard** — quick-count (nombre d'offres) with link to `offres.index`
- **Feature tests** — every controller action + form request validation + policy authorization

## Capabilities

### New Capabilities
- `offres-crud`: Full CRUD for job offers — scoped to authenticated user, with form validation and policy authorization

### Modified Capabilities
- (none)

## Impact

| File | Action |
|---|---|
| `routes/web.php` | Add `Route::resource('offres', OffreController::class)` |
| `app/Http/Controllers/OffreController.php` | New (7 methods) |
| `app/Http/Requests/StoreOffreRequest.php` | New |
| `app/Http/Requests/UpdateOffreRequest.php` | New |
| `app/Policies/OffrePolicy.php` | New |
| `resources/views/offres/index.blade.php` | New |
| `resources/views/offres/create.blade.php` | New |
| `resources/views/offres/show.blade.php` | New |
| `resources/views/offres/edit.blade.php` | New |
| `resources/views/layouts/navigation.blade.php` | Add "Offres" link |
| `resources/views/dashboard.blade.php` | Add offres count + link |
| `tests/Feature/OffreControllerTest.php` | New |
| `tests/Feature/StoreOffreRequestTest.php` | New |
