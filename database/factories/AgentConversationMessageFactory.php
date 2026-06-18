<?php

namespace Database\Factories;

use App\Enums\MessageRoleEnum;
use App\Models\AgentConversation;
use App\Models\AgentConversationMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgentConversationMessageFactory extends Factory
{
    protected $model = AgentConversationMessage::class;

    public function definition(): array
    {
        return [
            'agent_conversation_id' => AgentConversation::factory(),
            'agent' => 'App\Ai\Agents\AnalyseConversationAgent',
            'content' => fake()->paragraph(),
            'role' => MessageRoleEnum::User,
            'attachments' => '[]',
            'tool_calls' => '[]',
            'tool_results' => '[]',
            'usage' => '[]',
            'meta' => '[]',
        ];
    }

    public function fromAssistant(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => MessageRoleEnum::Assistant,
        ]);
    }
}
