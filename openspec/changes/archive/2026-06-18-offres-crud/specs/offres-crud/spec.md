# Offres CRUD — TA-5

## ADDED Requirements

### Requirement: List offres (index)

The system SHALL display a paginated list of offres belonging to the authenticated user, sorted by most recent first, with a search input filtering by title.

#### Scenario: Index shows only user's offres
- **WHEN** an authenticated user visits `/offres`
- **THEN** the page SHALL list only offres where `user_id` equals the authenticated user's ID

#### Scenario: Index is paginated
- **WHEN** a user has more than 10 offres
- **THEN** the page SHALL display pagination links

#### Scenario: Index search filters by title
- **WHEN** a user types a search term and submits
- **THEN** the list SHALL be filtered to offres whose `title` contains the search term

#### Scenario: Unauthenticated access is redirected to login
- **WHEN** a guest visits `/offres`
- **THEN** they SHALL be redirected to the login page

### Requirement: Create offre (create/store)

The system SHALL allow an authenticated user to create a new offre via a form with title, description, required_skills (tag input), and minimum_experience fields.

#### Scenario: Create form is accessible
- **WHEN** an authenticated user visits `/offres/create`
- **THEN** the page SHALL display a form with title, description, required_skills, and minimum_experience

#### Scenario: Store creates an offre for the user
- **WHEN** a user submits valid data to `POST /offres`
- **THEN** a new offre SHALL be created with `user_id` set to the authenticated user's ID
- **AND** the user SHALL be redirected to `offres.show` with a success flash message

#### Scenario: Store rejects missing title
- **WHEN** a user submits without a title
- **THEN** the system SHALL return a validation error for `title`

#### Scenario: Store rejects empty required_skills
- **WHEN** a user submits without at least one required skill
- **THEN** the system SHALL return a validation error for `required_skills`

#### Scenario: Store rejects negative minimum_experience
- **WHEN** a user submits a negative value for `minimum_experience`
- **THEN** the system SHALL return a validation error for `minimum_experience`

### Requirement: View offre (show)

The system SHALL display a single offre's full details and a link back to the list.

#### Scenario: Show displays offre details
- **WHEN** an authenticated user visits `/offres/{offre}`
- **THEN** the page SHALL display the offre's title, description, required_skills, and minimum_experience

#### Scenario: Show returns 403 for another user's offre
- **WHEN** a user visits `/offres/{offre}` where `offre.user_id` differs from the authenticated user
- **THEN** the system SHALL return a 403 Forbidden response

#### Scenario: Show returns 404 for non-existent offre
- **WHEN** a user visits `/offres/{id}` where no offre exists
- **THEN** the system SHALL return a 404 Not Found response

### Requirement: Edit offre (edit/update)

The system SHALL allow an authenticated user to edit their own offre via a pre-filled form with the same validation as creation.

#### Scenario: Edit form is pre-filled
- **WHEN** an authenticated user visits `/offres/{offre}/edit` for their own offre
- **THEN** the form SHALL be pre-filled with the offre's current values

#### Scenario: Update modifies the offre
- **WHEN** a user submits valid changes to `PUT /offres/{offre}`
- **THEN** the offre SHALL be updated with the new values
- **AND** the user SHALL be redirected to `offres.show` with a success flash message

#### Scenario: Update returns 403 for another user's offre
- **WHEN** a user submits `PUT /offres/{offre}` for an offre owned by another user
- **THEN** the system SHALL return a 403 Forbidden response

### Requirement: Delete offre (destroy)

The system SHALL allow an authenticated user to delete their own offre.

#### Scenario: Destroy deletes the offre
- **WHEN** an authenticated user sends `DELETE /offres/{offre}` for their own offre
- **THEN** the offre SHALL be deleted from the database
- **AND** the user SHALL be redirected to `offres.index` with a success flash message

#### Scenario: Destroy cascades to related analyses
- **WHEN** an offre with associated analyses is deleted
- **THEN** the related analyses SHALL also be deleted

#### Scenario: Destroy returns 403 for another user's offre
- **WHEN** a user tries to delete an offre owned by another user
- **THEN** the system SHALL return a 403 Forbidden response

### Requirement: Authorization via policy (no inline checks)

The system SHALL enforce ownership via an `OffrePolicy` class — inline `if` checks in controllers are forbidden.

#### Scenario: Policy gates all offre actions
- **WHEN** any controller action accesses an offre instance
- **THEN** the OffrePolicy SHALL verify the authenticated user owns that offre

### Requirement: Validation rules

The system SHALL enforce these rules for store and update:

| Field | Rules |
|---|---|
| `title` | required, string, max:255 |
| `description` | required, string |
| `required_skills` | required, array, min:1 |
| `required_skills.*` | string |
| `minimum_experience` | required, integer, min:0 |

#### Scenario: StoreOffreRequest enforces all rules
- **WHEN** submitted data violates any rule
- **THEN** the system SHALL return validation errors for the violating fields

### Requirement: Navigation link

The system SHALL display an "Offres" link in the main navigation for authenticated users.

#### Scenario: Nav link appears for authenticated users
- **WHEN** an authenticated user views any page
- **THEN** the navigation SHALL contain a link to `route('offres.index')`

### Requirement: Dashboard summary

The system SHALL show the authenticated user's offre count on the dashboard.

#### Scenario: Dashboard shows offre count with link
- **WHEN** an authenticated user visits `/dashboard`
- **THEN** the page SHALL display the number of offres they own
- **AND** a link to `route('offres.index')`
