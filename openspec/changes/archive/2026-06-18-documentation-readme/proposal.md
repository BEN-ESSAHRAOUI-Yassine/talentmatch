## Why

The project lacks a root `README.md`, making it difficult for new developers, stakeholders, and reviewers to understand the project's purpose, architecture, setup instructions, and contribution model. A well-structured README reduces onboarding friction and documents key decisions (tech stack, conventions, deployment). This addresses Jira issue **TA-9**.

## What Changes

- Create root `README.md` with project overview, badges, and table of contents
- Document the tech stack (PHP 8.3, Laravel 13, Tailwind CSS 3, Alpine.js 3)
- Add prerequisites and local setup (clone, composer install, npm, .env, database, queue, build)
- Document MCP/OpenCode configuration for contributors
- Explain AI architecture (structured output + conversational agent layers)
- Document the OpenSpec workflow and branch strategy (featureAI/*, no-push-to-main)
- Add testing instructions (pest, Agent::fake() pattern)
- Outline project directory structure (app/Ai/Agents/, app/Ai/Tools/, openspec/)
- Include environment variables reference table
- Add a changelog section referencing `openspec/changes/`

No existing files are modified — this is a pure addition.

## Capabilities

### New Capabilities
- `project-readme`: Root project README covering overview, setup, architecture, features, conventions, environment, changelog, and contribution guide.

### Modified Capabilities

None.

## Impact

- Single new file: `README.md` at project root.
- No API, database, or dependency changes.
- Follows existing naming conventions (English, concise).
