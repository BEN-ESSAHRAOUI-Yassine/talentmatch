<?php

use App\Models\AgentConversation;
use App\Models\AgentConversationMessage;
use App\Models\Analyse;

test('agent conversation factory creates a valid conversation', function () {
    $conversation = AgentConversation::factory()->create();

    expect($conversation)->toBeInstanceOf(AgentConversation::class);
});

test('agent conversation belongs to an analyse', function () {
    $analyse = Analyse::factory()->create();
    $conversation = AgentConversation::factory()->create(['analyse_id' => $analyse->id]);

    expect($conversation->analyse)->toBeInstanceOf(Analyse::class);
    expect($conversation->analyse->id)->toBe($analyse->id);
});

test('agent conversation has many messages', function () {
    $conversation = AgentConversation::factory()
        ->has(AgentConversationMessage::factory()->count(3), 'messages')
        ->create();

    expect($conversation->messages)->toHaveCount(3);
    expect($conversation->messages->first())->toBeInstanceOf(AgentConversationMessage::class);
});

test('agent conversation factory creates unique analyse per conversation', function () {
    $conversation1 = AgentConversation::factory()->create();
    $conversation2 = AgentConversation::factory()->create();

    expect($conversation1->analyse_id)->not->toBe($conversation2->analyse_id);
});
