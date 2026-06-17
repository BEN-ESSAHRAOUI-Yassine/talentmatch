# TalentMatch — OpenCode Prompt Guide
# Real OpenSpec commands for OpenCode (skill-based invocations).
# Copy-paste each prompt into OpenCode as-is.
# Follows the Phase 2 cycle defined in ruleset.md.

---

## BEFORE ANYTHING — PROJECT INITIALIZATION (Run once)

```
Read ruleset.md and openspec/config.yaml in full.
Then read AGENTS.md.

Once done, create all initial Jira tickets in project TA using the
initial_tickets list defined in openspec/config.yaml, in order.
For each ticket:
- type: Tâche (id: 10360)
- status: À faire
- summary: exactly as written in config.yaml
- include user_stories references in the description

Do not start any code. Confirm each ticket was created with its TA-X key.
```

---

## STEP 1 — PROPOSE (generate spec + planning artifacts)

```
/opsx:propose [change-name]
```

**Example for TA-3:**
```
/opsx:propose offres-crud
```

OpenSpec will create `openspec/changes/offres-crud/` with:
- `proposal.md` — why we're building this, what's changing
- `specs/` — requirements and scenarios
- `design.md` — technical approach (routes, models, casts, jobs)
- `tasks.md` — implementation checklist

**After OpenSpec generates the artifacts, add this context:**
```
This change corresponds to Jira ticket TA-X.
Read openspec/config.yaml for naming conventions, routing, casts,
validation rules, forbidden actions, and edge cases that apply to this feature.
Ensure the tasks.md is granular — every task will become a Jira Sous-tâche.
Present the artifacts for my review before proceeding.
```

Review all four artifacts. Request changes if needed before approving.

---

## STEP 2 — JIRA + BRANCH SETUP (after you approve the artifacts)

```
I approve the artifacts for [change-name] / TA-X.

Now do the following in this exact order:

1. Update Jira ticket TA-X:
   - Add proposal.md content to the ticket description
   - Create one Sous-tâche (id: 10359) for EVERY task in tasks.md
     ⚠️ Every task = one subtask, no exceptions
     ⚠️ Subtask title must match the tasks.md task title exactly
   - Transition TA-X to En cours (transition id: 21)

2. Create GitHub branch:
   featureAI/{change-name} from main
   Verify with: git branch --show-current
```

---

## STEP 3 — APPLY (implement the tasks)

```
/opsx:apply [change-name]
```

OpenSpec will work through `tasks.md` one task at a time, checking off
each item as it completes it.

**Add this after the command:**
```
While implementing, follow all rules in openspec/config.yaml and ruleset.md:
- All queries scoped to auth()->id() — use policy classes, no inline checks
- Named routes everywhere — never hardcoded URLs
- Enums and array casts exactly as defined in config.yaml
- php artisan make: for all new files — never create manually
- Queue::fake() in tests, mock all AI calls
- Run php artisan test --compact after each major task
- Run vendor/bin/pint --dirty --format agent before marking tasks done
```

---

## STEP 4 — VERIFY (before archiving)

```
/opsx:verify [change-name]
```

OpenSpec checks three dimensions:
- **Completeness** — all tasks done, all requirements implemented
- **Correctness** — implementation matches spec, edge cases handled
- **Coherence** — patterns consistent with design.md

**After verify output, also run:**
```
Run the TalentMatch Definition of Done checklist from openspec/config.yaml:
1. php artisan test --compact — all tests pass
2. vendor/bin/pint --dirty --format agent — zero issues
3. Confirm no N+1 on index and show routes (check with Debugbar)
4. Confirm all AI calls are mocked in tests
5. Confirm all queries scoped to auth()->id()
6. Confirm policy classes used — no inline auth checks
7. Confirm every task in tasks.md has a matching Jira Sous-tâche

Report each check. Fix any failures before archiving.
```

---

## STEP 5 — ARCHIVE (after all checks pass)

```
/opsx:archive [change-name]
```

OpenSpec will:
- Confirm all artifacts exist and tasks are complete
- Offer to sync delta specs → say **Yes**
- Move change to `openspec/changes/archive/YYYY-MM-DD-{change-name}/`

---

## STEP 6 — CLOSE (after archive)

```
Archive complete for [change-name] / TA-X.

Now close the cycle in this exact order:

1. Transition Jira ticket TA-X to TERMINE (transition id: 41)

2. Commit all changes to featureAI/{change-name}:
   - Include the archived openspec change folder in this commit
   - Message format: "feat(scope): description [AI-assisted]"

3. Push featureAI/{change-name} to GitHub

4. Create a Pull Request:
   - head: featureAI/{change-name}
   - base: main
   - title: exactly matching TA-X Jira ticket summary
   - body: what was built, tests written, edge cases handled

Confirm the PR URL. I will review and merge.
```

---

## BONUS — QUICK COMMANDS

### Explore before proposing (when requirements are unclear)
```
/opsx:explore [topic]
```
Use this before `/opsx:propose` when you want to think through
an approach first. No artifacts are created during exploration.

### Fast-forward all artifacts at once (for straightforward features)
```
/opsx:ff [change-name]
```
Creates proposal, specs, design, and tasks in one shot.
Use instead of `/opsx:propose` for simple features you understand fully.

### Step through artifacts one by one (for complex features)
```
/opsx:continue [change-name]
```
Creates one artifact at a time so you can review and edit
before the next one is generated. Use for TA-4 and TA-5.

### Check current state before starting work
```
What is the current status of all TA tickets in Jira?
What branches exist in BEN-ESSAHRAOUI-Yassine/talentmatch on GitHub?
What is the current local git branch?
```

### If something breaks mid-cycle
```
Something failed during implementation of [change-name] / TA-X.
Do not push anything to GitHub.
Report exactly what failed and propose a fix.
Wait for my approval before continuing.
```

### Audit subtask coverage
```
List every task from tasks.md for [change-name].
List every Sous-tâche currently on TA-X in Jira.
Identify any tasks without a matching Sous-tâche and create them now.
```

### Sync delta specs manually (optional)
```
/opsx:sync [change-name]
```
Only needed if you want specs merged into main openspec/specs/
before archiving. Archive handles this automatically if skipped.

---

## REFERENCE — Ticket order & change names

| Order | Key  | Feature                          | Change Name                    | Branch                              |
|-------|------|----------------------------------|--------------------------------|-------------------------------------|
| 1     | TA-1 | Authentication                   | authentication                 | featureAI/authentication            |
| 2     | TA-2 | Migrations & Modèles             | database-setup                 | featureAI/database-setup            |
| 3     | TA-3 | Offres CRUD                      | offres-crud                    | featureAI/offres-crud               |
| 4     | TA-4 | Analyse IA                       | analyse-ia                     | featureAI/analyse-ia                |
| 5     | TA-5 | Agent Conversationnel            | agent-conversationnel          | featureAI/agent-conversationnel     |
| 6     | TA-6 | Bonus — Classement & Comparaison | bonus-ranking-comparison       | featureAI/bonus-ranking-comparison  |
| 7     | TA-7 | Documentation — README.md        | documentation-readme           | featureAI/documentation-readme      |
| 8     | TA-8 | Présentation — presentation.html | presentation                   | featureAI/presentation              |

> TA-X keys are estimates — confirm actual keys after Jira ticket creation in the init step.

---

## FULL CYCLE SUMMARY

```
BEFORE ANYTHING  → create 8 Jira tickets
STEP 1           → /opsx:propose {change-name}     + config.yaml context
STEP 2           → Jira subtasks + En cours + GitHub branch
STEP 3           → /opsx:apply {change-name}        + config.yaml rules
STEP 4           → /opsx:verify {change-name}       + DoD checklist
STEP 5           → /opsx:archive {change-name}      → sync Yes
STEP 6           → Jira TERMINE + commit + push + PR
YOU              → review PR → merge to main
```
