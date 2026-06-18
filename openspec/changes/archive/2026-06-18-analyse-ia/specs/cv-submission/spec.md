## ADDED Requirements

### Requirement: Submit a candidate CV against an offre

An authenticated user SHALL be able to submit a candidate (name + CV text) against one of their own offres via a form accessed from the offre detail page.

#### Scenario: Store creates a candidat and analyse
- **WHEN** an authenticated user submits valid data (name, cv_text) to `POST /offres/{offre}/candidats`
- **THEN** a new candidat SHALL be created with the submitted name and cv_text
- **AND** a new analyse SHALL be created with `status=pending`, linked to the offre and candidat
- **AND** the `AnalyseCvJob` SHALL be dispatched with the offre_id, candidat_id, and analyse_id
- **AND** the user SHALL be redirected to `candidats.show` with a success flash message

#### Scenario: Submit form is accessible from offre show
- **WHEN** an authenticated user visits `/offres/{offre}`
- **THEN** the page SHALL display a "Soumettre un CV" button or section linking to the submission form

#### Scenario: Store rejects missing name
- **WHEN** a user submits without a name
- **THEN** the system SHALL return a validation error for `name`

#### Scenario: Store rejects cv_text under 50 characters
- **WHEN** a user submits with cv_text shorter than 50 characters
- **THEN** the system SHALL return a validation error for `cv_text`

#### Scenario: Store returns 403 for another user's offre
- **WHEN** a user submits `POST /offres/{offre}/candidats` for an offre owned by another user
- **THEN** the system SHALL return a 403 Forbidden response

#### Scenario: Store returns 404 for non-existent offre
- **WHEN** a user submits `POST /offres/{offre}/candidats` for a non-existent offre
- **THEN** the system SHALL return a 404 Not Found response

#### Scenario: Unauthenticated access is redirected to login
- **WHEN** a guest visits the candidat submission form
- **THEN** they SHALL be redirected to the login page
