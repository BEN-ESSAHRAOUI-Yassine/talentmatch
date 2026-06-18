## Why

HR agents currently review candidates one at a time via the candidate detail page. There is no way to get a bird's-eye view of all candidates for a given job offer sorted by AI matching score, nor a dedicated side-by-side comparison UI. Adding these two features gives HR agents immediate visibility into who the best candidates are and a structured way to compare two profiles before making a decision.

## What Changes

- **Candidate ranking view**: Add a ranked list of all candidates for an offre, sorted by `matching_score` descending, with key metrics (name, score, recommendation badge, top strengths, top gaps) in a compact table layout
- **Candidate comparison page**: Add a dedicated page that loads two selected candidates' analyses side-by-side for structured comparison — scores, skills, strengths, gaps, missing skills, and recommendation
- **Compare action**: Add a route to start a comparison from the ranking view (select two candidates and submit)
- **New spec**: `candidate-ranking` — ranking view logic
- **New spec**: `candidate-comparison` — comparison page logic

## Capabilities

### New Capabilities
- `candidate-ranking`: Displays all candidates for an offre ranked by AI matching_score descending, with recommendation badges and quick-action link to compare
- `candidate-comparison`: Dedicated comparison page showing two candidates' analyses side-by-side with all AI fields and a recommendation comparison

### Modified Capabilities

(No existing spec-level behavior changes — `CompareCandidatesTool` already exists in the conversational agent spec, but we are adding a web UI wrapper around it)

## Impact

- **New controller**: `CandidatComparisonController` or extend `CandidatController` with a `compare` method
- **New route**: `GET /offres/{offre}/candidats/compare?candidats[]=1&candidats[]=2`
- **New view**: `candidats/compare.blade.php` for side-by-side comparison
- **Modified view**: `offres/show.blade.php` — add a "Voir le classement" link
- **Existing `CompareCandidatesTool`**: Already exists, will be called from controller to get comparison data
- **Tests**: `CandidatRankingTest` and `CandidatComparisonTest` feature tests
