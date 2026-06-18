<?php

use App\Enums\MessageRoleEnum;
use App\Models\AgentConversation;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();
    $this->offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $this->candidat = Candidat::factory()->create();
    $this->analyse = Analyse::factory()->completed()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $this->candidat->id,
    ]);
});

test('guest is redirected to login for store', function () {
    $this->post(route('conversations.store', $this->analyse))->assertRedirect(route('login'));
});

test('guest is redirected to login for show', function () {
    $conversation = AgentConversation::factory()->create(['analyse_id' => $this->analyse->id]);

    $this->get(route('conversations.show', [$this->analyse, $conversation]))->assertRedirect(route('login'));
});

test('store creates a conversation', function () {
    $analyse = Analyse::factory()->completed()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $this->candidat->id,
    ]);

    expect($analyse->offre->user_id)->toBe($this->user->id);

    $response = $this->actingAs($this->user)
        ->post(route('conversations.store', $analyse));

    $response->assertStatus(302);

    $this->assertDatabaseHas('agent_conversations', [
        'analyse_id' => $analyse->id,
        'user_id' => $this->user->id,
    ]);

    $conversation = AgentConversation::where('analyse_id', $analyse->id)->first();
    $response->assertRedirect(route('conversations.show', [$analyse, $conversation]));
});

test('store returns 403 for another users analyse', function () {
    $this->actingAs($this->otherUser)
        ->post(route('conversations.store', $this->analyse))
        ->assertForbidden();
});

test('store returns 404 for non-existent analyse', function () {
    $this->actingAs($this->user)
        ->post('/analyses/99999/conversations')
        ->assertStatus(404);
});

test('show displays conversation with messages', function () {
    $conversation = AgentConversation::factory()->create(['analyse_id' => $this->analyse->id]);
    $conversation->messages()->create([
        'agent' => 'App\Ai\Agents\AnalyseConversationAgent',
        'content' => 'Bonjour, je peux vous aider ?',
        'role' => MessageRoleEnum::Assistant,
        'attachments' => '[]',
        'tool_calls' => '[]',
        'tool_results' => '[]',
        'usage' => '[]',
        'meta' => '[]',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('conversations.show', [$this->analyse, $conversation]));

    $response->assertStatus(200);
    $response->assertSee('Bonjour, je peux vous aider ?');
});

test('show displays empty state when no messages', function () {
    $conversation = AgentConversation::factory()->create(['analyse_id' => $this->analyse->id]);

    $response = $this->actingAs($this->user)
        ->get(route('conversations.show', [$this->analyse, $conversation]));

    $response->assertStatus(200);
    $response->assertSee('Aucun message');
});

test('show returns 403 for another users conversation', function () {
    $conversation = AgentConversation::factory()->create(['analyse_id' => $this->analyse->id]);

    $this->actingAs($this->otherUser)
        ->get(route('conversations.show', [$this->analyse, $conversation]))
        ->assertForbidden();
});

test('show returns 404 for non-existent conversation', function () {
    $this->actingAs($this->user)
        ->get("/analyses/{$this->analyse->id}/conversations/non-existent-id")
        ->assertStatus(404);
});
