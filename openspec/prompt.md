# TalentMatch — OpenCode Prompt Guide
# One prompt per step, copy-paste into OpenCode as-is.
# Steps follow the Phase 2 cycle defined in ruleset.md.

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

## STEP 1 — PROPOSE (generate the spec for a ticket)

```
Work on [TA-X — ticket summary].

Read ruleset.md and openspec/config.yaml.
Run: skill openspec-propose

Generate a detailed spec for this feature including:
- Goal and context
- Exact routes (named, with middleware)
- Models and relationships involved
- Eloquent casts required
- Form Request rules
- Controller methods and their logic
- Blade views needed
- Queue jobs if applicable (with async requirement)
- AI contract if applicable (structured output or agent tools)
- Edge cases to handle
- Task list (every task must be explicit — each will become a Jira Sous-tâche)
- Tests to write (feature tests, validation tests, mocked AI)

Do not write any code yet. Present the spec for my review.
```

---

## STEP 2 — APPLY (after you approve the spec)

```
I approve the spec for [TA-X].

Now execute the full implementation cycle from ruleset.md in this exact order:

1. Update Jira ticket TA-X:
   - Add the full spec content to the ticket description
   - Create one Sous-tâche (id: 10359) for EVERY task listed in the spec
     ⚠️ Every task = one subtask, no exceptions
     ⚠️ Subtask title must match the spec task title exactly
   - Transition TA-X to En cours (transition id: 21)

2. Create GitHub branch:
   featureAI/{kebab-case-title} from main
   Verify with: git branch --show-current before any file change

3. Implement the feature:
   - Run php artisan make: commands for all new files
   - Follow all conventions in openspec/config.yaml and AGENTS.md
   - Scope all queries to auth()->id()
   - Use policy classes for authorization — never inline auth checks
   - Use named routes everywhere — never hardcoded URLs
   - Cast enums and arrays exactly as defined in config.yaml

4. Write tests:
   - Feature tests for every controller action
   - Validation tests for every Form Request
   - Mock all AI calls — never hit the real API in tests
   - Use Queue::fake() for job dispatch tests

5. Run tests: php artisan test --compact
   Fix any failures before continuing.

6. Run formatter: vendor/bin/pint --dirty --format agent

Confirm each numbered step as you complete it before moving to the next.
```

---

## STEP 3 — VERIFY (before archiving)

```
Before archiving TA-X, run the full Definition of Done checklist
from openspec/config.yaml:

1. Run php artisan test --compact — all tests must pass
2. Run vendor/bin/pint --dirty --format agent — zero issues
3. Check Debugbar for N+1 on every index and show route
4. Confirm all AI calls are mocked in tests
5. Confirm all queries are scoped to auth()->id()
6. Confirm policy classes are used — no inline auth checks
7. Confirm every spec task has a corresponding Jira Sous-tâche

Report the result of each check. Fix anything that fails.
Do not proceed to archive until all 7 checks pass.
```

---

## STEP 4 — ARCHIVE (after DoD passes)

```
All checks pass for TA-X.

Run: skill openspec-archive

Archive the spec for this feature:
- Write the final spec file to openspec/specs/{feature-name}.md
- Include: what was built, routes, models, casts, edge cases handled, tests written

Then run: skill openspec-sync
Sync any delta changes to openspec/config.yaml if new patterns
were established during implementation.

Confirm the spec file was written.
```

---

## STEP 5 — CLOSE (after archive)

```
Archive complete for TA-X.

Now close the cycle in this exact order:

1. Transition Jira ticket TA-X to TERMINE (transition id: 41)

2. Commit all remaining changes to featureAI/{branch-name}:
   - Include the archived spec file in this commit
   - Message format: "feat(scope): description [AI-assisted]"

3. Push featureAI/{branch-name} to GitHub

4. Create a Pull Request:
   - head: featureAI/{branch-name}
   - base: main
   - title: exactly matching TA-X Jira ticket summary
   - body: what was implemented, tests written, edge cases handled

Confirm the PR URL. I will review and merge.
```

---

## BONUS — QUICK COMMANDS

### Check current state before starting work
```
What is the current status of all TA tickets in Jira?
What branches exist in BEN-ESSAHRAOUI-Yassine/talentmatch on GitHub?
What is the current local git branch?
```

### If something breaks mid-cycle
```
Something failed during implementation of TA-X.
Do not push anything to GitHub.
Report exactly what failed and propose a fix.
Wait for my approval before continuing.
```

### Verify subtask coverage after spec approval
```
List every task from the spec for TA-X.
List every Sous-tâche currently on TA-X in Jira.
Identify any spec tasks that do not have a matching Sous-tâche.
Create the missing ones now.
```

### Check for N+1 before archiving
```
Run php artisan route:list and identify all index routes.
For each index route, confirm the controller uses eager loading with with().
Report any missing eager loading — fix before archiving.
```

---

## REFERENCE — Ticket order from config.yaml

| Order | Key  | Feature                          | Branch                              |
|-------|------|----------------------------------|-------------------------------------|
| 1     | TA-3 | Authentication                   | featureAI/authentication            |
| 2     | TA-4 | Migrations & Modèles             | featureAI/database-setup            |
| 3     | TA-5 | Offres CRUD                      | featureAI/offres-crud               |
| 4     | TA-6 | Analyse IA                       | featureAI/analyse-ia                |
| 5     | TA-7 | Agent Conversationnel            | featureAI/agent-conversationnel     |
| 6     | TA-8 | Bonus — Classement & Comparaison | featureAI/bonus-ranking-comparison  |
| 7     | TA-9 | Documentation — README.md        | featureAI/documentation-readme      |
| 8     | TA-10 | Présentation — presentation.html | featureAI/presentation              |

> TA-X keys are estimates — confirm actual keys after Jira ticket creation in the init step.
