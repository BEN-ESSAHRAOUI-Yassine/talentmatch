# Presentation — TA-10

## What Will Be Built

Single self-contained `presentation.html` file with all CSS and JS inline. Dark professional theme, slide-based navigation (keyboard arrows + click), progress bar, slide counter.

## Slides

### 1. Titre
- TalentMatch — Assistant IA de Présélection RH
- Nom, date, formation

### 2. Problématique
- 50 à 200 CVs par offre reçus par email
- Lecture manuelle, répétitive, subjective, chronophage
- Aucune standardisation du scoring
- Le RH passe des heures sur une tâche que l'IA peut absorber

### 3. Solution — TalentMatch
- Soumettre une offre + un CV → analyse structurée en quelques secondes
- Matching score justifié (0-100)
- Points forts, lacunes, compétences manquantes, recommandation typée
- Assistant conversationnel avec mémoire pour approfondir l'analyse

### 4. MCD
- Image: `public/images/mcd.png`
- Entités: USER, OFFRE, CANDIDAT, ANALYSE, AGENT_CONVERSATION, AGENT_CONVERSATION_MESSAGE
- ANALYSE résout le Many-to-Many entre OFFRE et CANDIDAT

### 5. MLD
- Image: `public/images/mld.png`
- Tables, colonnes, types, clés primaires et étrangères
- Colonnes JSON (arrays) et ENUMs en évidence
- `agent_conversations` et `agent_conversation_messages` gérées par laravel/ai

### 6. Architecture & Stack
- Laravel 13, PHP 8.3, MySQL, Groq API
- laravel/ai : deux couches (structured output + agent)
- Queue database pour AnalyseCvJob (async)
- RemembersConversations trait pour la mémoire
- Tools: GetCandidateAnalysis, GetJobRequirements, CompareCandidates

### 7. Workflow AI-Assisted
- OpenSpec cycle: propose → apply → verify → archive
- Laravel Boost: contexte schema + routes pour meilleur code généré
- GitHub MCP + Jira MCP intégrés dans OpenCode
- Branches featureAI/* — main protégé, merge manuel
- Commits avec mention [AI-assisted]

### 8. Démo — Structured Output
- Contrat JSON de l'analyse
- Pourquoi le score ne peut pas être calculé par un if/else
- status: pending → completed via queue job

### 9. Démo — Agent & Tools
- Tool en action: getCandidateAnalysis
- Comparaison: réponse sans tool (hallucination) vs avec tool (données réelles)
- Mémoire: deux questions sur le même candidat

### 10. Conclusion
- Ce que TalentMatch automatise
- Ce que le RH garde en contrôle (décision finale, merge, entretien)
- Stack moderne, workflow professionnel, code défendable

## Requirements

### Requirement: All CSS and JS must be inline
The presentation SHALL be a single HTML file with zero external dependencies. No CDN, no external images (except local `public/images/`), fully offline-capable.

### Requirement: Navigation
- Keyboard: ArrowLeft (previous), ArrowRight (next)
- Click: left half of slide → previous, right half → next
- Progress bar: width percentage = currentSlide / totalSlides
- Slide counter: "N / 10" displayed on each slide

### Requirement: Transitions
- Smooth CSS transitions (opacity + vertical transform)
- No JavaScript animation libraries
- Active slide opacity: 1, inactive: 0

### Requirement: Visual theme
- Dark background: slate-900 (`#0f172a`)
- Card/slide background: slate-800 (`#1e293b`)
- Accent color: teal-500 (`#14b8a6`)
- Text: slate-100 (`#f1f5f9`)
- Code blocks: dark background with monospace font

### Requirement: Code blocks
- JSON contract displayed in a styled `<pre><code>` block with dark background
- Tool signature displayed in a styled code block

### Requirement: Images
- MCD and MLD displayed with full-width `<img>` tags
- Images already exist at `public/images/mcd.png` and `public/images/mld.png`

## Edge Cases
| Cas | Comportement |
|-----|-------------|
| Slide 1, user presses ArrowLeft | Stay on slide 1 (no wrap-around) |
| Slide 10, user presses ArrowRight | Stay on slide 10 (no wrap-around) |
| Images fail to load | Alt text describes the diagram content |
| Window resize | Images scale with max-width: 100%, slides use viewport-relative sizing |

## Tests
- Open `presentation.html` in a browser
- Navigate with keyboard arrows through all 10 slides
- Click left and right halves to navigate
- Verify progress bar updates
- Verify slide counter shows correct values
- Verify MCD and MLD images render
- Verify JSON code block renders correctly
- Verify no external requests are made (offline mode)

## Dépendances
- Dépend de : TA-1 (Database Setup), TA-2 (CV Submission), TA-3 (Authentication), TA-4 (AI Analysis), TA-5 (Offres CRUD), TA-6 (Conversational Agent), TA-9 (README.md)
- Ne bloque aucun ticket
