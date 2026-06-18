<?php

use App\Jobs\AnalyseCvJob;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();
    $this->offre = Offre::factory()->create(['user_id' => $this->user->id]);
});

test('guest is redirected to login for create', function () {
    $this->get(route('candidats.create', $this->offre))->assertRedirect(route('login'));
});

test('guest is redirected to login for store', function () {
    $this->post(route('candidats.store', $this->offre), [])->assertRedirect(route('login'));
});

test('guest is redirected to login for show', function () {
    $candidat = Candidat::factory()->create();

    $this->get(route('candidats.show', [$this->offre, $candidat]))->assertRedirect(route('login'));
});

test('store creates candidat and analyse and dispatches job', function () {
    Queue::fake();

    $response = $this->actingAs($this->user)->post(route('candidats.store', $this->offre), [
        'name' => 'Jean Dupont',
        'cv_text' => str_repeat('Experienced PHP developer. ', 20),
    ]);

    $this->assertDatabaseHas('candidats', ['name' => 'Jean Dupont']);

    $candidat = Candidat::where('name', 'Jean Dupont')->first();
    $this->assertDatabaseHas('analyses', [
        'offre_id' => $this->offre->id,
        'candidat_id' => $candidat->id,
        'status' => 'pending',
    ]);

    $response->assertRedirect(route('candidats.show', [$this->offre, $candidat]));
    $response->assertSessionHas('success');

    Queue::assertPushed(AnalyseCvJob::class);
});

test('store returns 403 for another users offre', function () {
    $this->actingAs($this->otherUser)->post(route('candidats.store', $this->offre), [
        'name' => 'Jean Dupont',
        'cv_text' => str_repeat('Experienced PHP developer. ', 20),
    ])->assertForbidden();
});

test('store returns 404 for non-existent offre', function () {
    $this->actingAs($this->user)
        ->post('/offres/99999/candidats', [
            'name' => 'Jean Dupont',
            'cv_text' => str_repeat('a', 100),
        ])
        ->assertStatus(404);
});

test('store returns validation error for missing name', function () {
    $this->actingAs($this->user)->post(route('candidats.store', $this->offre), [
        'name' => '',
        'cv_text' => str_repeat('a', 100),
    ])->assertSessionHasErrors('name');
});

test('store returns validation error for short cv_text', function () {
    $this->actingAs($this->user)->post(route('candidats.store', $this->offre), [
        'name' => 'Jean Dupont',
        'cv_text' => str_repeat('a', 49),
    ])->assertSessionHasErrors('cv_text');
});

test('show displays pending analysis status', function () {
    $candidat = Candidat::factory()->create();
    $analyse = Analyse::factory()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $candidat->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('candidats.show', [$this->offre, $candidat]));

    $response->assertStatus(200);
    $response->assertSee($candidat->name);
    $response->assertSee('Analyse en cours');
});

test('show displays completed analysis with results', function () {
    $candidat = Candidat::factory()->create();
    $analyse = Analyse::factory()->completed()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $candidat->id,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('candidats.show', [$this->offre, $candidat]));

    $response->assertStatus(200);
    $response->assertSee($candidat->name);
    $response->assertSee($analyse->matching_score);
    $response->assertSee($analyse->niveau_etudes);
});

test('show displays failed analysis with error message', function () {
    $candidat = Candidat::factory()->create();
    $analyse = Analyse::factory()->failed()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $candidat->id,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('candidats.show', [$this->offre, $candidat]));

    $response->assertStatus(200);
    $response->assertSee('Analyse échouée');
});

test('show returns 403 for another users candidat', function () {
    $candidat = Candidat::factory()->create();
    Analyse::factory()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $candidat->id,
    ]);

    $this->actingAs($this->otherUser)
        ->get(route('candidats.show', [$this->offre, $candidat]))
        ->assertForbidden();
});

test('show returns 404 for non-existent candidat', function () {
    $this->actingAs($this->user)
        ->get("/offres/{$this->offre->id}/candidats/99999")
        ->assertStatus(404);
});
