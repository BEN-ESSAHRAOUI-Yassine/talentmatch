# Conversation Memory

## Purpose

The system SHALL provide Eloquent models, a migration, an enum, and factories for the `agent_conversations` and `agent_conversation_messages` tables (published by `laravel/ai`), enabling persistent conversation memory with role-tagged messages.

## Requirements

### Requirement: MessageRoleEnum

The system SHALL provide `App\Enums\MessageRoleEnum` with cases `User` and `Assistant`.

#### Scenario: Enum has user case
- **WHEN** `MessageRoleEnum::User` is referenced
- **THEN** it SHALL exist and be usable as a cast target

#### Scenario: Enum has assistant case
- **WHEN** `MessageRoleEnum::Assistant` is referenced
- **THEN** it SHALL exist and be usable as a cast target

### Requirement: AgentConversation model

The system SHALL provide an `App\Models\AgentConversation` Eloquent model using the `agent_conversations` table (published by `laravel/ai`).

#### Scenario: Factory creates a valid conversation
- **WHEN** `AgentConversation::factory()->create()` is called
- **THEN** the result SHALL be an instance of `AgentConversation`

#### Scenario: Belongs to an analyse
- **WHEN** a conversation is created with an `analyse_id`
- **THEN** `$conversation->analyse` SHALL return the related `Analyse` instance

#### Scenario: Has many messages
- **WHEN** a conversation has related messages
- **THEN** `$conversation->messages` SHALL return a collection of `AgentConversationMessage` instances

#### Scenario: Factory creates unique analyse per conversation
- **WHEN** two conversations are created
- **THEN** their `analyse_id` values SHALL be different

### Requirement: AgentConversationMessage model

The system SHALL provide an `App\Models\AgentConversationMessage` Eloquent model using the `agent_conversation_messages` table (published by `laravel/ai`).

#### Scenario: Factory creates a valid message
- **WHEN** `AgentConversationMessage::factory()->create()` is called
- **THEN** the result SHALL be an instance of `AgentConversationMessage`
- **AND** the `content` SHALL not be empty
- **AND** the `role` SHALL be an instance of `MessageRoleEnum`

#### Scenario: fromAssistant factory state
- **WHEN** `AgentConversationMessage::factory()->fromAssistant()->create()` is called
- **THEN** the `role` SHALL be `MessageRoleEnum::Assistant`

#### Scenario: Belongs to a conversation
- **WHEN** a message is created with a `agent_conversation_id`
- **THEN** `$message->agentConversation` SHALL return the related `AgentConversation` instance

#### Scenario: Role cast to enum
- **WHEN** a message is retrieved from the database
- **THEN** the `role` attribute SHALL be cast to `MessageRoleEnum`

### Requirement: Analyse has one agent conversation

The system SHALL add a `agentConversation()` HasOne relation to the `Analyse` model.

#### Scenario: Analyse has agent conversation relation
- **WHEN** an analyse is created with a related conversation
- **THEN** `$analyse->agentConversation` SHALL return the related `AgentConversation` instance
