## ADDED Requirements

### Requirement: View analysis results for a candidate

An authenticated user SHALL be able to view the full analysis results for a candidate submitted against their offre.

#### Scenario: Show displays completed analysis
- **WHEN** an authenticated user visits `/offres/{offre}/candidats/{candidat}` and the analyse has status=completed
- **THEN** the page SHALL display: candidate name, all AI-extracted fields, matching score, strengths, gaps, missing skills, and recommendation badge (color-coded: green for convoquer, yellow for attente, red for rejeter)

#### Scenario: Show displays pending state
- **WHEN** a user visits a candidat page and the analyse has status=pending
- **THEN** the page SHALL display a loading/pending indicator with the message "Analyse en cours..."

#### Scenario: Show displays failed state with error
- **WHEN** a user visits a candidat page and the analyse has status=failed
- **THEN** the page SHALL display an error state with the error_message content
- **AND** a retry hint or action SHALL be available

#### Scenario: Show returns 403 for another user's candidate
- **WHEN** a user visits `/offres/{offre}/candidats/{candidat}` where the offre belongs to another user
- **THEN** the system SHALL return a 403 Forbidden response

#### Scenario: Show returns 404 for non-existent candidate
- **WHEN** a user visits `/offres/{offre}/candidats/{id}` where no candidat exists
- **THEN** the system SHALL return a 404 Not Found response
