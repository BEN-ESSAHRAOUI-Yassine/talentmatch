## 1. Conversation Memory — Models, Migration, Enum, Factories

- [x] 1.1 Create `App\Enums\MessageRoleEnum` with `User` and `Assistant` cases using `php artisan make:enum MessageRoleEnum`
- [x] 1.2 Create migration to add `analyse_id` (foreign ID, nullable) to `agent_conversations` table using `php artisan make:migration`
- [x] 1.3 Create `App\Models\AgentConversation` model with `HasFactory`, `$fillable`, `$casts`, `belongsTo(Analyse::class)` relation, `hasMany(AgentConversationMessage::class)` relation
- [x] 1.4 Create `App\Models\AgentConversationMessage` model with `HasFactory`, `$fillable`, `$casts = ['role' => MessageRoleEnum::class]`, `belongsTo(AgentConversation::class)` relation
- [x] 1.5 Create `AgentConversationFactory` — auto-creates a related `Analyse` via `Offre` + `Candidat`
- [x] 1.6 Create `AgentConversationMessageFactory` with default state (role=User) and `fromAssistant()` state
- [x] 1.7 Add `agentConversation()` HasOne relation to `App\Models\Analyse`

## 2. Conversation Agent — Agent Class and Tools

- [x] 2.1 Create `Analyser les candidatures` tool using `php artisan make:tool GetCandidateAnalysisTool` — `handle(int $candidatId): Analyse`
- [x] 2.2 Create `récupérer les exigences du poste` tool using `php artisan make:tool GetJobRequirementsTool` — `handle(int $offreId): Offre`
- [x] 2.3 Create `comparer les candidats` tool using `php artisan make:tool CompareCandidatesTool` — `handle(int $id1, int $id2): array`, returns 422 if different offres
- [x] 2.4 Create `AnalyseConversationAgent` with `php artisan make:agent AnalyseConversationAgent` — implements `Agent` contract, uses `RemembersConversations` trait, defines `tools()` returning three tools, defines `instructions()` with forbidden-fabrication rules

## 3. Conversation UI — Routes, Controllers, Policies, Views

- [x] 3.1 Add conversation routes in `routes/web.php` inside the auth+verified group: `conversations.store`, `conversations.show`, `messages.store`
- [x] 3.2 Create `ConversationPolicy` — `create` (analyse ownership), `view` (analyse ownership chain)
- [x] 3.3 Create `MessagePolicy` — `create` (conversation analyse ownership)
- [x] 3.4 Create `ConversationController` with `store` (create conversation linked to analyse, redirect to show) and `show` (load conversation with messages)
- [x] 3.5 Create `MessageController` with `store` (create user message, invoke agent, store assistant response, redirect back to conversation)
- [x] 3.6 Create `StoreMessageRequest` — validate `content` (required, string, min:1)
- [x] 3.7 Create `conversations/show.blade.php` — display messages chronologically + send message form
- [x] 3.8 Update `analyses/show.blade.php` to add "Démarrer une conversation" button or link to existing conversation

## 4. Testing

- [x] 4.1 Run `php artisan test --compact` — pre-existing `AgentConversationTest` and `AgentConversationMessageTest` now passing
- [x] 4.2 Create `ConversationControllerTest` with pest tests for: guest redirect, store creates conversation, store 403, store 404, show displays messages, show empty state, show 403, show 404
- [x] 4.3 Create `MessageControllerTest` with pest tests for: store creates user + assistant messages, store rejects empty content, store 403, store 404
- [x] 4.4 Create `AnalyseConversationAgentTest` with pest tests for: agent starts conversation, agent uses tools via fake

## 5. Finalization

- [x] 5.1 Run `php artisan test --compact` — all tests passing
- [x] 5.2 Run `vendor/bin/pint --dirty --format agent`
- [x] 5.3 Verify no N+1 queries (eager load messages when showing conversation)
