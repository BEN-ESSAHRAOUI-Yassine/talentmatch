## ADDED Requirements

### Requirement: View side-by-side comparison of two candidates

An authenticated user SHALL be able to compare two candidates' AI analyses side-by-side on a dedicated page, viewing all structured fields from both analyses.

#### Scenario: Comparison page displays two analyses side-by-side
- **WHEN** an authenticated user visits `GET /offres/{offre}/candidats/comparer?ids[]=1&ids[]=2` with valid candidate IDs belonging to the same offre with completed analyses
- **THEN** the page SHALL display both candidates' names as column headers
- **AND** the page SHALL show for each candidate: matching score, recommendation badge, extracted skills, experience, education, languages, strengths, gaps, missing skills, and justification
- **AND** scores SHALL be visually highlighted — higher score gets a green accent

#### Scenario: Comparison shows "meilleur score" indicator
- **WHEN** two completed analyses are displayed
- **THEN** the candidate with the higher matching score SHALL be visually marked as "Meilleur score"

#### Scenario: Comparison rejects non-numeric IDs
- **WHEN** a user provides non-numeric IDs in the query string
- **THEN** the system SHALL return a validation error

#### Scenario: Comparison rejects more or fewer than 2 candidate IDs
- **WHEN** a user provides fewer or more than 2 candidate IDs
- **THEN** the system SHALL return a validation error

#### Scenario: Comparison rejects candidates from different offres
- **WHEN** a user provides candidate IDs that belong to different offres
- **THEN** the system SHALL return a 422 Unprocessable Entity response

#### Scenario: Comparison rejects candidates with non-completed analyses
- **WHEN** one or both candidates have an analyse without `status=completed`
- **THEN** the system SHALL return a 422 Unprocessable Entity response

#### Scenario: Comparison returns 403 for another user's offre
- **WHEN** a user accesses the comparison page for an offre owned by another user
- **THEN** the system SHALL return a 403 Forbidden response

#### Scenario: Comparison returns 404 for non-existent candidate
- **WHEN** a user provides a candidate ID that does not exist
- **THEN** the system SHALL return a 404 Not Found response
