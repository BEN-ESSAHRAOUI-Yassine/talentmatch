## Context

The app already has Layer 1 AI (structured output analysis via `AnalyseCvJob`). Layer 2 adds a conversational agent that HR agents can chat with to ask follow-up questions about candidate analyses. The `laravel/ai` SDK provides `RemembersConversations` trait, `ConversationStore` contract, and built-in migration for `agent_conversations` and `agent_conversation_messages` tables. Pre-existing tests (`AgentConversationTest`, `AgentConversationMessageTest`) define the model contracts. The `Analyse` model has no conversation relation yet.

## Goals / Non-Goals

**Goals:**
- Create `AgentConversation` and `AgentConversationMessage` Eloquent models that satisfy the pre-existing tests
- Add `analyse_id` to `agent_conversations` to link conversations to analyses
- Create `MessageRoleEnum` (user | assistant)
- Create factories for both models
- Create `AnalyseConversationAgent` using `RemembersConversations` trait
- Create three tools: `GetCandidateAnalysisTool`, `GetJobRequirementsTool`, `CompareCandidatesTool`
- Create `ConversationController` (store, show) and `MessageController` (store)
- Create policies (`ConversationPolicy`, `MessagePolicy`) scoped through analyse → offre → user
- Create Blade chat UI under `analyses/{analyse}/conversations/{conversation}`
- Make all pre-existing conversation tests pass

**Non-Goals:**
- No changes to the existing Layer 1 structured output flow
- No real-time broadcasting/websockets — messages are loaded on page reload
- No streaming agent responses — agent responds fully before rendering
- No editing or deleting messages

## Decisions

1. **App-level Eloquent models (not vendor models):** The tests import `App\Models\AgentConversation` and `App\Models\AgentConversationMessage`, not `Laravel\Ai\Models\Conversation`. We create app models that use the same tables (`agent_conversations`, `agent_conversation_messages`). The vendor's `DatabaseConversationStore` uses raw DB queries, so app models don't conflict.

2. **Migration for `analyse_id`:** The existing vendor migration doesn't include `analyse_id`. We add it via a new migration. This allows `AgentConversation` to `belongsTo(Analyse::class)` and `Analyse` to `hasOne(AgentConversation::class)`.

3. **Agent uses `RemembersConversations` trait:** Following config.yaml guidance, the agent trait handles message history automatically. Starting a conversation: `forUser($user)->prompt(...)`. Resuming: `continue($conversationId, as: $user)->prompt(...)`.

4. **Tools as PHPDoc-tagged classes:** Following `laravel/ai` convention, each tool is a class with a `handle()` method. The agent has a `tools()` method returning instances. The SDK auto-registers them via `#[Tool]` attribute or by returning from `tools()`.

5. **Conversation scoping via Analyse:** Conversations are nested under `/analyses/{analyse}`. `ConversationPolicy` verifies the analyse's offre belongs to `auth()->id()`. `MessagePolicy` verifies through the conversation's analyse chain.

6. **No separate `StoreConversationRequest`:** The store action only requires `analyse_id` (from route), no request body. A simple `authorize()` call in the controller suffices. `StoreMessageRequest` validates `content` (required string).

## Risks / Trade-offs

- **Vendor migration divergence:** The `laravel/ai` package may update its migration in future versions. Our `analyse_id` column on a vendor-managed table could conflict. Mitigation: keep our migration separate and clearly documented.
- **Agent hallucination:** The agent has tools to fetch real data but could fabricate if tools aren't invoked. Mitigation: forbidden rules in agent instructions explicitly prohibit fabrication without tool calls.
- **Queue timing:** If AnalyseCvJob is still processing when the user opens the conversation, `analyse_id` won't exist yet. Mitigation: conversations are only accessible after the analysis is complete (status=completed). The UI should disable chat until analysis is done.
