<?php

use App\Enums\MessageRoleEnum;
use App\Models\AgentConversation;
use App\Models\AgentConversationMessage;

test('agent conversation message factory creates a valid message', function () {
    $message = AgentConversationMessage::factory()->create();

    expect($message)->toBeInstanceOf(AgentConversationMessage::class);
    expect($message->content)->not->toBeEmpty();
    expect($message->role)->toBeInstanceOf(MessageRoleEnum::class);
    expect($message->role)->toBe(MessageRoleEnum::User);
});

test('agent conversation message fromAssistant state works', function () {
    $message = AgentConversationMessage::factory()->fromAssistant()->create();

    expect($message->role)->toBe(MessageRoleEnum::Assistant);
});

test('agent conversation message belongs to a conversation', function () {
    $conversation = AgentConversation::factory()->create();
    $message = AgentConversationMessage::factory()->create(['agent_conversation_id' => $conversation->id]);

    expect($message->agentConversation)->toBeInstanceOf(AgentConversation::class);
    expect($message->agentConversation->id)->toBe($conversation->id);
});

test('agent conversation message casts role to enum', function () {
    $message = AgentConversationMessage::factory()->create();

    expect($message->role)->toBeInstanceOf(MessageRoleEnum::class);
});
