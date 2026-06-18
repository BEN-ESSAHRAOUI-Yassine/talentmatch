<?php

use App\Ai\Agents\AnalyseConversationAgent;
use App\Models\AgentConversation;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use App\Models\User;

beforeEach(function () {
    AnalyseConversationAgent::fake();

    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();
    $this->offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $this->candidat = Candidat::factory()->create();
    $this->analyse = Analyse::factory()->completed()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $this->candidat->id,
    ]);
    $this->conversation = AgentConversation::factory()->create([
        'analyse_id' => $this->analyse->id,
        'user_id' => $this->user->id,
    ]);
});

test('store creates user and assistant messages', function () {
    $response = $this->actingAs($this->user)
        ->post(route('messages.store', [$this->analyse, $this->conversation]), [
            'content' => 'Quel est le score de matching ?',
        ]);

    $response->assertRedirect(route('conversations.show', [$this->analyse, $this->conversation]));

    $this->assertDatabaseHas('agent_conversation_messages', [
        'conversation_id' => $this->conversation->id,
        'role' => 'user',
        'content' => 'Quel est le score de matching ?',
    ]);

    $this->assertDatabaseHas('agent_conversation_messages', [
        'conversation_id' => $this->conversation->id,
        'role' => 'assistant',
    ]);
});

test('store rejects empty content', function () {
    $this->actingAs($this->user)
        ->post(route('messages.store', [$this->analyse, $this->conversation]), [
            'content' => '',
        ])
        ->assertSessionHasErrors('content');
});

test('store returns 403 for another users conversation', function () {
    $this->actingAs($this->otherUser)
        ->post(route('messages.store', [$this->analyse, $this->conversation]), [
            'content' => 'Bonjour',
        ])
        ->assertForbidden();
});

test('store returns 404 for non-existent conversation', function () {
    $this->actingAs($this->user)
        ->post("/analyses/{$this->analyse->id}/conversations/non-existent-id/messages", [
            'content' => 'Bonjour',
        ])
        ->assertStatus(404);
});
