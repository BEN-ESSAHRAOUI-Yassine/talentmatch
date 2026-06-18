## Why

HR agents currently receive a one-shot structured analysis (Layer 1) when a CV is submitted. They cannot ask follow-up questions, probe deeper into specific skills, or get comparative insights without re-submitting CVs. Adding a conversational agent (Layer 2) with persistent memory lets HR agents interact naturally with the AI — maintaining context across turns, retrieving real analysis data via tools, and comparing candidates — all within a chat interface.

## What Changes

- Create `App\Models\AgentConversation` and `App\Models\AgentConversationMessage` app-level Eloquent models backed by the tables published by `laravel/ai`
- Add `analyse_id` column to `agent_conversations` via a new migration
- Create `App\Enums\MessageRoleEnum` (user | assistant)
- Create factories for both models, matching the pre-existing test expectations
- Create a Layer 2 conversational agent (`App\Ai\Agents\AnalyseConversationAgent`) using the `RemembersConversations` trait from `laravel/ai`
- Create three tools (`GetCandidateAnalysisTool`, `GetJobRequirementsTool`, `CompareCandidatesTool`) the agent can invoke
- Create `ConversationController` and `MessageController` with routes nested under analyses
- Create Blade views for the conversation UI (show conversation with message list + send form)
- Create policies and form requests
- Add `agentConversation()` HasOne relation to `Analyse` model
- Write feature tests for all new actions and agent behavior

## Capabilities

### New Capabilities
- `conversation-memory`: AgentConversation and AgentConversationMessage models, factories, migration for analyse_id, MessageRoleEnum, Eloquent relations
- `conversation-agent`: The AnalyseConversationAgent with RemembersConversations trait, three tools (GetCandidateAnalysis, GetJobRequirements, CompareCandidates), RememberConversation middleware
- `conversation-ui`: Routes, controllers (ConversationController, MessageController), policies, form requests, Blade views for the chat interface

### Modified Capabilities
- (no existing spec-level requirement changes)

## Impact

- **Models**: New `AgentConversation`, `AgentConversationMessage` models; `HasOne` relation added to `Analyse`; `HasConversations` trait on `User`
- **Controllers**: New `ConversationController` (store, show), `MessageController` (store)
- **Routes**: Three new routes under `/analyses/{analyse}/conversations/` as defined in config.yaml
- **AI**: New `AnalyseConversationAgent` with `RemembersConversations` trait; three new tools; no breaking changes to existing Layer 1 flow
- **Views**: New chat interface in `conversations/show.blade.php`
- **Database**: Add `analyse_id` to `agent_conversations` via migration (tables already exist from vendor)
- **Tests**: Pre-existing `AgentConversationTest` and `AgentConversationMessageTest` will start passing; new `ConversationControllerTest`, `MessageControllerTest`, `AnalyseConversationAgentTest`
