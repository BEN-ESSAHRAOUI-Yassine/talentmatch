## Why

The project needs a self-contained presentation for demo/soutenance. A single HTML file with no external dependencies ensures it works offline on any machine — no network, no CDN, no setup required.

## What Changes

- Create root `presentation.html` with all CSS and JS inline
- 10 slides covering problem, solution, MCD/MLD, architecture, workflow, and demo
- Dark professional theme matching the application's brand
- Slide navigation via keyboard arrows and click zones
- Progress bar and slide counter
- MCD and MLD displayed via `<img>` tags pointing to `public/images/mcd.png` and `public/images/mld.png`
- Code blocks for JSON contract and tool signatures
- No modification to existing files

## Capabilities

### New Capabilities
- `presentation`: Self-contained HTML presentation covering the full TalentMatch project — problem, solution, data model, architecture, workflow, and demos.

### Modified Capabilities

None.

## Impact

- Single new file: `presentation.html` at project root
- References existing images: `public/images/mcd.png`, `public/images/mld.png`
- No API, database, or dependency changes
