# Design — Project README

## Tone & Audience
- **Audience**: New developers, contributors, stakeholders, reviewers
- **Tone**: Professional, clear, concise. Bilingual (English for code/documentation, French for project concept)
- **Format**: GitHub-flavored Markdown

## Structure
- Badges row at top (Laravel, PHP, License, etc.)
- Table of Contents (linked anchors)
- Sections in logical order: overview → tech → setup → architecture → workflow → project map → reference

## Visual Decisions
- No HTML in README — pure Markdown with safe GFM extensions (tables, code blocks, task lists)
- Tables for structured data (env vars, stack, routes)
- Backtick inline code for files, commands, classes
- Fenced code blocks with language hints for shell, PHP, JSON, Blade
- `tree`-style structure for directory layout (using nested list or fenced block)

## Content Sources
- `openspec/config.yaml` — tech stack versions, naming conventions, routing rules
- `composer.json` — precise package versions
- `app/Ai/` — architecture details, file listings
- `openspec/changes/` — changelog references
- `public/images/mcd.png`, `public/images/mld.png` — database diagrams

## Non-Goals
- No screenshots or diagrams (Markdown only, no image hosting)
- No inline API docs (those live in-code)
- No code contribution guide beyond the workflow section (simple enough for a single-developer project)
