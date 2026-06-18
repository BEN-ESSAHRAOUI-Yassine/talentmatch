# Presentation

## Purpose

Single self-contained `presentation.html` file with all CSS and JS inline. Dark professional theme, slide-based navigation (keyboard arrows + click), progress bar, slide counter. Covers 10 slides: problem, solution, MCD/MLD, architecture, workflow, and demos.

## Requirements

### Requirement: Self-contained single file

The presentation SHALL be a single HTML file with zero external dependencies. No CDN, no external images (except local `public/images/`), fully offline-capable. All CSS SHALL be in a `<style>` tag inside `<head>`, all JS in a `<script>` tag before `</body>`.

### Requirement: Slide navigation

- Keyboard: ArrowLeft (previous), ArrowRight (next)
- Click: left half of slide → previous, right half → next
- No wrap-around at first or last slide (boundary clamp)

### Requirement: Progress indicator

- Progress bar fixed at top, 4px height, full width
- Fill width = `(currentSlide + 1) / totalSlides * 100%`
- Slide counter fixed top-right displaying "N / 10"
- Both update on every slide change

### Requirement: Transitions

- Smooth CSS transitions only (no JavaScript animation libraries)
- Opacity: active slide 1, inactive 0
- Vertical transform: active translateY(0), inactive translateY(1.5rem)
- Duration: 400ms ease-in-out

### Requirement: Visual theme

- Background: `#0f172a` (slate-900)
- Card/slide background: `#1e293b` (slate-800), border-radius 12px, padding 3rem
- Accent color: `#14b8a6` (teal-500)
- Body text: `#cbd5e1` (slate-300)
- Title text: `#f1f5f9` (slate-100), 2.5rem, bold
- Code blocks: dark background with monospace font, teal left border

### Requirement: Slides content

The presentation SHALL include exactly 10 slides in this order:

1. **Titre** — TalentMatch, nom, date, formation
2. **Problématique** — volume de CVs, lecture manuelle, scoring subjectif
3. **Solution** — analyse structurée, matching score, recommandation, agent conversationnel
4. **MCD** — image `public/images/mcd.png`, entités et cardinalités
5. **MLD** — image `public/images/mld.png`, tables, JSON columns, ENUMs
6. **Architecture & Stack** — Laravel 13, PHP 8.3, deux couches IA, tools
7. **Workflow AI-Assisted** — OpenSpec, Boost, MCP, branches, commits
8. **Démo Structured Output** — JSON contract, pourquoi pas if/else, cycle pending→completed
9. **Démo Agent & Tools** — tool signature, comparaison hallucination vs réel, mémoire
10. **Conclusion** — ce qui est automatisé vs sous contrôle RH

### Requirement: Code blocks

- JSON contract from the AI analysis displayed in a `<pre><code>` block
- Tool signature (e.g., `GetCandidateAnalysisTool.handle(int $candidatId): Analyse`) displayed in a `<pre><code>` block
- Both with dark background (`#0f172a`), teal left border, monospace font

### Requirement: Images

- MCD displayed with `<img src="public/images/mcd.png">` — full width, rounded corners
- MLD displayed with `<img src="public/images/mld.png">` — full width, rounded corners
- Alt text describing the diagram content (fallback if image fails to load)
- Images scale with `max-width: 100%` on window resize

#### Scenario: Slide 1 ArrowLeft does not wrap
- **WHEN** user is on slide 1 and presses ArrowLeft
- **THEN** the presentation SHALL stay on slide 1

#### Scenario: Slide 10 ArrowRight does not wrap
- **WHEN** user is on slide 10 and presses ArrowRight
- **THEN** the presentation SHALL stay on slide 10

#### Scenario: Images scale on resize
- **WHEN** the browser window is resized
- **THEN** images SHALL scale with `max-width: 100%` and slides SHALL use viewport-relative sizing

## Dépendances

- Dépend de : TA-1 (Database Setup), TA-2 (CV Submission), TA-3 (Authentication), TA-4 (AI Analysis), TA-5 (Offres CRUD), TA-6 (Conversational Agent), TA-9 (README.md)
- Ne bloque aucun ticket
