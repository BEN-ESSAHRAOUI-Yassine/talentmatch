# Authentication — TA-3

## What Was Built

Laravel Breeze (Blade + Alpine stack) pré-installé. Branding par défaut remplacé par TalentMatch sur toutes les vues auth et la page d'accueil.

### Changements effectués

- **`resources/views/layouts/guest.blade.php`** — Logo Laravel remplacé par texte "TalentMatch"
- **`resources/views/layouts/navigation.blade.php`** — Logo Laravel remplacé par texte "TalentMatch" dans la barre de navigation
- **`resources/views/welcome.blade.php`** — Page d'accueil entièrement réécrite avec présentation TalentMatch (3 cartes: Offres d'emploi, Analyse IA, Agent conversationnel), boutons Login/Register, footer

## Routes

Routes inchangées — toutes définies dans `routes/auth.php` par Breeze :

| Méthode | URI | Nom | Middleware |
|---------|-----|-----|------------|
| GET/POST | `/register` | `register` | `guest` |
| GET/POST | `/login` | `login` | `guest` |
| GET | `/forgot-password` | `password.request` | `guest` |
| POST | `/forgot-password` | `password.email` | `guest` |
| GET | `/reset-password/{token}` | `password.reset` | `guest` |
| POST | `/reset-password` | `password.store` | `guest` |
| GET | `/verify-email` | `verification.notice` | `auth` |
| GET | `/verify-email/{id}/{hash}` | `verification.verify` | `auth, signed, throttle:6,1` |
| POST | `/email/verification-notification` | `verification.send` | `auth, throttle:6,1` |
| GET/POST | `/confirm-password` | `password.confirm` | `auth` |
| PUT | `/password` | `password.update` | `auth` |
| POST | `/logout` | `logout` | `auth` |
| GET | `/` | — | `web` |
| GET | `/dashboard` | `dashboard` | `auth, verified` |
| GET/PATCH/DELETE | `/profile` | `profile.edit/update/destroy` | `auth` |

## Modèles

- **User** — inchangé. Pas de `MustVerifyEmail` (décision : emails fictifs en test).

## Eloquent Casts

Aucun cast ajouté (hors périmètre).

## Form Requests

- `LoginRequest` — inchangé (rate limiting: 5 tentatives, lockout).

## Contrôleurs

Aucun contrôleur nouveau ou modifié. Les contrôleurs Breeze existants sont utilisés tels quels.

## Vues

| Vue | Statut |
|-----|--------|
| `layouts/guest.blade.php` | Modifiée — branding |
| `layouts/app.blade.php` | Inchangée |
| `layouts/navigation.blade.php` | Modifiée — branding |
| `welcome.blade.php` | Réécrite — landing page TalentMatch |
| `auth/*.blade.php` (6 vues) | Inchangées |

## Edge Cases

| Cas | Comportement |
|-----|-------------|
| Utilisateur non vérifié accède à `/dashboard` | Redirigé vers `/verify-email` (middleware `verified`) |
| Lien de vérification expiré | Page d'erreur 401 (signed route Laravel) |
| Login rate limit | 5 tentatives, message de lockout |

## Tests

18 tests existants inchangés, tous verts :

| Fichier | Tests |
|---------|-------|
| `tests/Feature/Auth/AuthenticationTest.php` | 4 |
| `tests/Feature/Auth/RegistrationTest.php` | 2 |
| `tests/Feature/Auth/PasswordUpdateTest.php` | 2 |
| `tests/Feature/Auth/PasswordResetTest.php` | 4 |
| `tests/Feature/Auth/PasswordConfirmationTest.php` | 3 |
| `tests/Feature/Auth/EmailVerificationTest.php` | 3 |
| `tests/Feature/ProfileTest.php` | 5 |
| Plus `ExampleTest` (Feature + Unit) | 2 |

**Total : 25 passed, 61 assertions.**

## Dépendances

- Bloquée pour : TA-5 (Offres CRUD), TA-9 (Documentation), TA-10 (Présentation)
