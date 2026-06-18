<?php

use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();
    $this->offre = Offre::factory()->create(['user_id' => $this->user->id]);
});

test('guest is redirected to login for classement', function () {
    $this->get(route('candidats.classement', $this->offre))->assertRedirect(route('login'));
});

test('classement shows completed analyses sorted by score descending', function () {
    $candidat1 = Candidat::factory()->create(['name' => 'Alice']);
    $candidat2 = Candidat::factory()->create(['name' => 'Bob']);
    Analyse::factory()->completed()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $candidat1->id,
        'matching_score' => 80,
    ]);
    Analyse::factory()->completed()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $candidat2->id,
        'matching_score' => 60,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('candidats.classement', $this->offre));

    $response->assertStatus(200);
    $response->assertSeeInOrder(['80%', '60%']);
});

test('classement shows pending analyses with spinner text', function () {
    $candidat = Candidat::factory()->create();
    Analyse::factory()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $candidat->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('candidats.classement', $this->offre));

    $response->assertStatus(200);
    $response->assertSee('Analyse en cours');
});

test('classement shows failed analyses with error and retry link', function () {
    $candidat = Candidat::factory()->create();
    Analyse::factory()->failed()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $candidat->id,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('candidats.classement', $this->offre));

    $response->assertStatus(200);
    $response->assertSee('Réanalyser');
});

test('classement shows empty state when no candidates', function () {
    $response = $this->actingAs($this->user)
        ->get(route('candidats.classement', $this->offre));

    $response->assertStatus(200);
    $response->assertSee('Aucun candidat soumis');
});

test('classement returns 403 for another users offre', function () {
    $this->actingAs($this->otherUser)
        ->get(route('candidats.classement', $this->offre))
        ->assertForbidden();
});

test('classement returns 404 for non-existent offre', function () {
    $this->actingAs($this->user)
        ->get('/offres/99999/candidats/classement')
        ->assertStatus(404);
});

test('classement shows compare button when exactly 2 completed candidates selected', function () {
    $candidat1 = Candidat::factory()->create();
    $candidat2 = Candidat::factory()->create();
    Analyse::factory()->completed()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $candidat1->id,
    ]);
    Analyse::factory()->completed()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $candidat2->id,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('candidats.classement', $this->offre));

    $response->assertStatus(200);
    $response->assertSee('Comparer les 2 candidats sélectionnés');
});

test('classement hides compare button when less than 2 completed candidates', function () {
    $candidat = Candidat::factory()->create();
    Analyse::factory()->completed()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $candidat->id,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('candidats.classement', $this->offre));

    $response->assertStatus(200);
    $response->assertDontSee('Comparer les 2 candidats sélectionnés');
});
