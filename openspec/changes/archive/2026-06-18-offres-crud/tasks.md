## 1. Routes

- [x] 1.1 Register `Route::resource('offres', OffreController::class)` in `routes/web.php` inside the `auth` + `verified` middleware group

## 2. Controller

- [x] 2.1 Create `OffreController` with `index()` — fetch paginated, searchable offres scoped to `auth()->id()`
- [x] 2.2 Implement `create()` — return the create view
- [x] 2.3 Implement `store(StoreOffreRequest $request)` — create offre, redirect to show with flash
- [x] 2.4 Implement `show(Offre $offre)` — authorize via policy, return show view
- [x] 2.5 Implement `edit(Offre $offre)` — authorize, return edit view with pre-filled data
- [x] 2.6 Implement `update(UpdateOffreRequest $request, Offre $offre)` — authorize, update, redirect to show with flash
- [x] 2.7 Implement `destroy(Offre $offre)` — authorize, delete, redirect to index with flash

## 3. Form Requests

- [x] 3.1 Create `StoreOffreRequest` with rules: title (required|string|max:255), description (required|string), required_skills (required|array|min:1), required_skills.* (string), minimum_experience (required|integer|min:0)
- [x] 3.2 Create `UpdateOffreRequest` extending StoreOffreRequest (same rules)

## 4. Policy

- [x] 4.1 Create `OffrePolicy` with `view()`, `create()`, `update()`, `delete()` methods — all check `$user->id === $offre->user_id`
- [x] 4.2 Register policy in `AppServiceProvider::boot()` or `AuthServiceProvider`

## 5. Views

- [x] 5.1 Create `offres/index.blade.php` — table listing with search, pagination, action buttons
- [x] 5.2 Create `offres/create.blade.php` — form with title, description, required_skills (Alpine tag input), minimum_experience
- [x] 5.3 Create `offres/show.blade.php` — full detail view with edit/delete buttons
- [x] 5.4 Create `offres/edit.blade.php` — same form as create, pre-filled

## 6. Navigation & Dashboard

- [x] 6.1 Add "Offres" nav link in `navigation.blade.php`
- [x] 6.2 Update `dashboard.blade.php` with offre count

## 7. Tests

- [x] 7.1 Create `OffreControllerTest` — test index (guest redirect, lists own offres, pagination, search)
- [x] 7.2 Add store tests — validation errors, successful creation, skills persistence
- [x] 7.3 Add show tests — displays offre, 403 for other user, 404 for missing
- [x] 7.4 Add edit/update tests — pre-filled form, successful update, 403 for other user
- [x] 7.5 Add destroy tests — successful deletion, cascade, 403 for other user
- [x] 7.6 Create `StoreOffreRequestTest` — test each validation rule
- [x] 7.7 Run `php artisan test --compact` — all offres CRUD tests green (35 passed)

## 8. Code Quality

- [x] 8.1 Run `vendor/bin/pint --dirty --format agent`

## 9. Jira

- [x] 9.1 Créer les Sous-tâches Jira pour chaque tâche ci-dessus
- [x] 9.2 Transitionner les Sous-tâches vers En cours (id: 21)
- [ ] 9.3 Transitionner les Sous-tâches vers TERMINE (id: 41) après validation
