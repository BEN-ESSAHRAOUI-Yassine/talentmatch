<?php

use App\Ai\Agents\AnalyseConversationAgent;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use App\Models\User;

beforeEach(function () {
    AnalyseConversationAgent::fake();

    $this->user = User::factory()->create();
    $this->offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $this->candidat = Candidat::factory()->create();
    $this->analyse = Analyse::factory()->completed()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $this->candidat->id,
    ]);
});

test('agent starts a conversation and returns a response', function () {
    $agent = new AnalyseConversationAgent($this->analyse);
    $response = $agent->forUser($this->user)->prompt('Parle-moi de ce candidat');

    expect($response->text)->not->toBeEmpty();
    expect($response->conversationId)->not->toBeNull();
});

test('agent can be prompted and records conversation', function () {
    $agent = new AnalyseConversationAgent($this->analyse);
    $response = $agent->forUser($this->user)->prompt('Quel est le score de matching ?');

    expect($response->text)->toBeString();
    expect($response->text)->not->toBeEmpty();

    AnalyseConversationAgent::assertPrompted(function ($prompt) {
        return true;
    });
});
