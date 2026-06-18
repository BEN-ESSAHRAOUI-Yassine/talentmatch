<?php

use App\Models\Candidat;
use App\Models\Offre;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();
});

test('guest is redirected to login for candidate index', function () {
    $this->get(route('candidats.index'))->assertRedirect(route('login'));
});

test('index shows all candidates ordered by creation date', function () {
    $candidat1 = Candidat::factory()->create(['name' => 'Alice', 'created_at' => now()->subDay()]);
    $candidat2 = Candidat::factory()->create(['name' => 'Bob', 'created_at' => now()]);

    $response = actingAs($this->user)
        ->get(route('candidats.index'));

    $response->assertStatus(200);
    $response->assertSeeInOrder(['Bob', 'Alice']);
});

test('index shows cv text truncated', function () {
    Candidat::factory()->create([
        'name' => 'Charlie',
        'cv_text' => str_repeat('A', 300),
    ]);

    $response = actingAs($this->user)
        ->get(route('candidats.index'));

    $response->assertStatus(200);
    $response->assertSee('Charlie');
    $response->assertSee('AAA');
});

test('index shows empty state when no candidates', function () {
    $response = actingAs($this->user)
        ->get(route('candidats.index'));

    $response->assertStatus(200);
    $response->assertSee('Aucun candidat dans le répertoire');
});

test('index shows reuse dropdown with user offres', function () {
    $candidat = Candidat::factory()->create(['name' => 'David']);
    $offre = Offre::factory()->create(['user_id' => $this->user->id, 'title' => 'Developpeur Laravel']);

    $response = actingAs($this->user)
        ->get(route('candidats.index'));

    $response->assertStatus(200);
    $response->assertSee('David');
    $response->assertSee('Developpeur Laravel');
    $response->assertSee('Réutiliser');
});

test('index only shows the authenticated user offres in dropdown', function () {
    $candidat = Candidat::factory()->create();
    Offre::factory()->create(['user_id' => $this->otherUser->id, 'title' => 'Offre Autre User']);

    $response = actingAs($this->user)
        ->get(route('candidats.index'));

    $response->assertStatus(200);
    $response->assertDontSee('Offre Autre User');
});

test('create form pre-fills from query params', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);

    $response = actingAs($this->user)
        ->get(route('candidats.create', $offre) . '?name=Jean+Reused&cv_text=CV+content+for+reuse');

    $response->assertStatus(200);
    $response->assertSee('Jean Reused');
    $response->assertSee('CV content for reuse');
});

test('create form does not pre-fill cv_text if below 50 chars validation', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);

    $response = actingAs($this->user)
        ->get(route('candidats.create', $offre) . '?name=Jane&cv_text=Short');

    $response->assertStatus(200);
    $response->assertSee('Jane');
    $response->assertSee('Short');
});
