## ADDED Requirements

### Requirement: AnalyseConversationAgent with RemembersConversations

The system SHALL provide an `App\Ai\Agents\AnalyseConversationAgent` using the `RemembersConversations` trait from `laravel/ai` and implementing the `Agent` contract.

#### Scenario: Agent starts a new conversation
- **WHEN** `$agent->forUser($user)->prompt('Tell me about this candidate')` is called
- **THEN** the agent SHALL create a new conversation via the SDK's `ConversationStore`
- **AND** the agent SHALL receive the previous structured analysis context from its instructions
- **AND** the agent SHALL return a response based on real data

#### Scenario: Agent resumes an existing conversation
- **WHEN** `$agent->continue($conversationId, as: $user)->prompt('Follow-up question')` is called
- **THEN** the agent SHALL receive previous messages as context
- **AND** the agent SHALL respond in context of the conversation history

#### Scenario: Agent uses tools to fetch real data
- **WHEN** the agent is asked about candidate skills, job requirements, or comparisons
- **THEN** the agent SHALL invoke the appropriate tool to retrieve data
- **AND** the agent SHALL NOT fabricate scores, skills, or candidate data

### Requirement: GetCandidateAnalysisTool

The system SHALL provide a `GetCandidateAnalysisTool` that fetches a full analysis record from the database.

#### Scenario: Tool returns analyse for a valid candidat
- **WHEN** the tool's `handle(int $candidatId)` is called with a valid candidat ID
- **THEN** it SHALL return the associated `Analyse` model with all fields

#### Scenario: Tool returns 404 for invalid candidat
- **WHEN** the tool's `handle(int $candidatId)` is called with a non-existent ID
- **THEN** it SHALL throw a `ModelNotFoundException`

### Requirement: GetJobRequirementsTool

The system SHALL provide a `GetJobRequirementsTool` that fetches the job offer criteria.

#### Scenario: Tool returns offre for a valid ID
- **WHEN** the tool's `handle(int $offreId)` is called with a valid offre ID
- **THEN** it SHALL return the `Offre` model with required_skills, description, minimum_experience

#### Scenario: Tool returns 404 for invalid offre
- **WHEN** the tool's `handle(int $offreId)` is called with a non-existent ID
- **THEN** it SHALL throw a `ModelNotFoundException`

### Requirement: CompareCandidatesTool

The system SHALL provide a `CompareCandidatesTool` that compares two analysis records on the same offer.

#### Scenario: Tool compares two candidates on same offre
- **WHEN** `handle(int $id1, int $id2)` is called with two candidat IDs belonging to the same offre
- **THEN** it SHALL return a structured diff with scores, strengths, gaps, and recommendation

#### Scenario: Tool returns 422 for candidates on different offres
- **WHEN** `handle(int $id1, int $id2)` is called with IDs from different offres
- **THEN** it SHALL return a 422 error with an appropriate message

### Requirement: Agent instructions forbid fabrication

The agent SHALL have instructions that explicitly forbid fabricating data and require tool usage.

#### Scenario: Agent never answers without calling tools
- **WHEN** the agent is asked "What is candidate X's score?"
- **THEN** the agent SHALL call `GetCandidateAnalysisTool` before responding
- **AND** the agent SHALL NOT guess or fabricate the score
