## ADDED Requirements

### Requirement: Start a conversation

An authenticated user SHALL be able to start a conversation about an analyse linked to one of their own offres.

#### Scenario: Store creates a conversation
- **WHEN** an authenticated user sends `POST /analyses/{analyse}/conversations`
- **THEN** a new conversation SHALL be created linked to the analyse
- **AND** the user SHALL be redirected to `conversations.show`

#### Scenario: Store returns 403 for another user's analyse
- **WHEN** a user posts to an analyse whose offre belongs to another user
- **THEN** the system SHALL return a 403 Forbidden response

#### Scenario: Store returns 404 for non-existent analyse
- **WHEN** a user posts to `/analyses/{id}/conversations` where no analyse exists
- **THEN** the system SHALL return a 404 Not Found response

#### Scenario: Unauthenticated access is redirected to login
- **WHEN** a guest visits any conversation route
- **THEN** they SHALL be redirected to the login page

### Requirement: View a conversation

An authenticated user SHALL be able to view a conversation and its messages.

#### Scenario: Show displays conversation with messages
- **WHEN** an authenticated user visits `/analyses/{analyse}/conversations/{conversation}`
- **THEN** the page SHALL display the conversation's messages in chronological order
- **AND** a message input form SHALL be displayed to send a new message

#### Scenario: Show displays empty state
- **WHEN** a conversation has no messages yet
- **THEN** the page SHALL display an empty state, not an error

#### Scenario: Show returns 403 for another user's conversation
- **WHEN** a user views a conversation belonging to another user's analyse
- **THEN** the system SHALL return a 403 Forbidden response

#### Scenario: Show returns 404 for non-existent conversation
- **WHEN** a user visits `/analyses/{analyse}/conversations/{id}` where no conversation exists
- **THEN** the system SHALL return a 404 Not Found response

### Requirement: Send a message in a conversation

An authenticated user SHALL be able to send a message in their conversation. The message SHALL be processed by the AnalyseConversationAgent and the agent's response SHALL be stored as an assistant message.

#### Scenario: Store creates user message and agent response
- **WHEN** an authenticated user submits valid content to `POST /analyses/{analyse}/conversations/{conversation}/messages`
- **THEN** a new message SHALL be created with `role=User` and the submitted content
- **AND** the AnalyseConversationAgent SHALL be invoked with the conversation context
- **AND** the agent's response SHALL be stored as a new message with `role=Assistant`
- **AND** the user SHALL be redirected back to `conversations.show`

#### Scenario: Store rejects empty content
- **WHEN** a user submits without content
- **THEN** the system SHALL return a validation error for `content`

#### Scenario: Store returns 403 for another user's conversation
- **WHEN** a user sends a message to another user's conversation
- **THEN** the system SHALL return a 403 Forbidden response

#### Scenario: Store returns 404 for non-existent conversation
- **WHEN** a user sends a message to a non-existent conversation
- **THEN** the system SHALL return a 404 Not Found response

### Requirement: Conversation prompt from analyse show

An authenticated user SHALL be able to access or start a conversation from the analyse detail page.

#### Scenario: Analyse show links to conversation
- **WHEN** an authenticated user visits an analyse page that has an existing conversation
- **THEN** the page SHALL display a link to the conversation
- **AND** if no conversation exists, a "Démarrer une conversation" button SHALL be shown
