## ADDED Requirements

### Requirement: View all candidates ranked by matching score

An authenticated user SHALL be able to view all candidates for an offre, sorted by their AI matching score descending, with key metrics visible at a glance.

#### Scenario: Ranking shows completed analyses sorted by score
- **WHEN** an authenticated user visits `GET /offres/{offre}/candidats/classement` and the offre has candidates with completed analyses
- **THEN** the page SHALL display all candidates sorted by `matching_score` descending
- **AND** each row SHALL show: candidate name, matching score, recommendation badge (green/yellow/red), top 3 strengths, top 3 gaps
- **AND** incomplete analyses (pending/failed) SHALL be shown at the bottom, grouped by status

#### Scenario: Ranking displays pending indicator for in-progress analyses
- **WHEN** a candidate has an analyse with `status=pending`
- **THEN** the row SHALL show a "Analyse en cours…" indicator instead of score/stats

#### Scenario: Ranking displays failed state with retry link
- **WHEN** a candidate has an analyse with `status=failed`
- **THEN** the row SHALL show an error badge with the error message
- **AND** a "Réanalyser" link SHALL be shown pointing to `analyses.retry`

#### Scenario: Ranking returns 403 for another user's offre
- **WHEN** a user visits the ranking page for an offre owned by another user
- **THEN** the system SHALL return a 403 Forbidden response

#### Scenario: Ranking returns 404 for non-existent offre
- **WHEN** a user visits the ranking page for a non-existent offre
- **THEN** the system SHALL return a 404 Not Found response

#### Scenario: Ranking shows empty state when no candidates
- **WHEN** an offre has no candidates
- **THEN** the page SHALL display "Aucun candidat soumis" with a link to submit one

### Requirement: Access ranking from offre show page

An authenticated user SHALL be able to navigate to the ranking view from the offre detail page.

#### Scenario: Offre show links to ranking
- **WHEN** an authenticated user visits the offre detail page
- **THEN** the page SHALL display a "Voir le classement" button or link
- **AND** the link SHALL point to `GET /offres/{offre}/candidats/classement`

### Requirement: Compare action from ranking view

An authenticated user SHALL be able to select two candidates from the ranking and navigate to the comparison page.

#### Scenario: Ranking has checkbox selector and compare button
- **WHEN** an authenticated user views the ranking page
- **THEN** each completed candidate row SHALL have a checkbox
- **AND** a "Comparer" button SHALL be visible when exactly 2 candidates are selected
- **AND** clicking "Comparer" SHALL navigate to `GET /offres/{offre}/candidats/comparer?ids[]=X&ids[]=Y`
