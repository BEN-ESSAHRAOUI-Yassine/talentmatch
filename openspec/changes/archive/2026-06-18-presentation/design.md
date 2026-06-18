# Design — Presentation

## Format
- Single file: `presentation.html` at project root
- HTML5 boilerplate (`<!DOCTYPE html>`, `<meta charset="UTF-8">`, viewport meta)
- All CSS in `<style>` tag inside `<head>`
- All JS in `<script>` tag just before `</body>`

## Structure
```html
<div class="presentation">
  <div class="progress-bar"><div class="progress-fill"></div></div>
  <div class="counter">1 / 10</div>

  <div class="slide active" data-index="0">...</div>
  <div class="slide" data-index="1">...</div>
  ...
  <div class="slide" data-index="9">...</div>
</div>
```

## Layout
- Each slide is `100vw × 100vh`, centered content via flexbox
- Content area max-width: 900px, centered
- Slide counter fixed top-right
- Progress bar fixed top, full width, 4px height
- Code blocks inside slides with scrolling for long content

## Visual Theme

| Element | Style |
|---------|-------|
| Background | `#0f172a` (slate-900) |
| Slide card | `#1e293b` (slate-800), border-radius: 12px, padding: 3rem |
| Title text | `#f1f5f9` (slate-100), 2.5rem, bold |
| Body text | `#cbd5e1` (slate-300), 1.1rem |
| Accent | `#14b8a6` (teal-500) for highlights, borders, progress fill |
| Code bg | `#0f172a` with `#14b8a6` left border |
| Transitions | 400ms ease-in-out on opacity and transform |

## Typography
- System font stack: `-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`
- Mono for code: `"SF Mono", Monaco, "Cascadia Code", monospace`

## Navigation System
- `keydown` listener for ArrowLeft/ArrowRight
- `click` listener: `event.clientX < window.innerWidth / 2` = prev, else = next
- `goToSlide(index)` — removes `.active` from current, adds to new
- `updateProgress()` — sets `.progress-fill` width to `((index + 1) / total) * 100%`
- `updateCounter()` — sets text to `${index + 1} / ${total}`

## Content Sources
- `public/images/mcd.png`, `public/images/mld.png`
- `openspec/config.yaml` — stack versions, naming conventions
- `openspec/specs/ai-analysis/spec.md` — JSON contract details
- `openspec/specs/conversation-agent/spec.md` — tool signatures
- `openspec/config.yaml` layer_1_structured_output — JSON contract fields
- `README.md` — workflow description

## Non-Goals
- No animations beyond slide transitions
- No print styles
- No mobile-responsive breakpoints (assumes desktop/投影)
- No presenter notes
