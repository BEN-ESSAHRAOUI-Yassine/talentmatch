<?php

use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();
    $this->offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $this->candidat1 = Candidat::factory()->create(['name' => 'Alice']);
    $this->candidat2 = Candidat::factory()->create(['name' => 'Bob']);
    $this->analyse1 = Analyse::factory()->completed()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $this->candidat1->id,
        'matching_score' => 85,
    ]);
    $this->analyse2 = Analyse::factory()->completed()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $this->candidat2->id,
        'matching_score' => 65,
    ]);
});

test('guest is redirected to login for comparer', function () {
    $this->get(route('candidats.comparer', [
        $this->offre,
        'ids' => [$this->candidat1->id, $this->candidat2->id],
    ]))->assertRedirect(route('login'));
});

test('comparer displays two analyses side by side', function () {
    $response = $this->actingAs($this->user)
        ->get(route('candidats.comparer', [
            $this->offre,
            'ids' => [$this->candidat1->id, $this->candidat2->id],
        ]));

    $response->assertStatus(200);
    $response->assertSee('Alice');
    $response->assertSee('Bob');
    $response->assertSee('85%');
    $response->assertSee('65%');
});

test('comparer shows meilleur score badge on higher scorer', function () {
    $response = $this->actingAs($this->user)
        ->get(route('candidats.comparer', [
            $this->offre,
            'ids' => [$this->candidat1->id, $this->candidat2->id],
        ]));

    $response->assertStatus(200);
    $response->assertSee('Meilleur score');
});

test('comparer rejects non numeric ids', function () {
    $response = $this->actingAs($this->user)
        ->get(route('candidats.comparer', [
            $this->offre,
            'ids' => ['abc', 'def'],
        ]));

    $response->assertSessionHasErrors('ids.0');
});

test('comparer rejects wrong count of ids', function () {
    $response = $this->actingAs($this->user)
        ->get(route('candidats.comparer', [
            $this->offre,
            'ids' => [$this->candidat1->id],
        ]));

    $response->assertSessionHasErrors('ids');
});

test('comparer rejects candidates from different offres', function () {
    $otherOffre = Offre::factory()->create(['user_id' => $this->user->id]);
    $otherCandidat = Candidat::factory()->create();
    Analyse::factory()->completed()->create([
        'offre_id' => $otherOffre->id,
        'candidat_id' => $otherCandidat->id,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('candidats.comparer', [
            $this->offre,
            'ids' => [$this->candidat1->id, $otherCandidat->id],
        ]));

    $response->assertSessionHasErrors('ids');
});

test('comparer rejects non completed analyses', function () {
    $pendingCandidat = Candidat::factory()->create();
    Analyse::factory()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $pendingCandidat->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('candidats.comparer', [
            $this->offre,
            'ids' => [$this->candidat1->id, $pendingCandidat->id],
        ]));

    $response->assertSessionHasErrors('ids');
});

test('comparer returns 403 for another users offre', function () {
    $this->actingAs($this->otherUser)
        ->get(route('candidats.comparer', [
            $this->offre,
            'ids' => [$this->candidat1->id, $this->candidat2->id],
        ]))
        ->assertForbidden();
});

test('comparer returns 404 for non-existent candidate', function () {
    $response = $this->actingAs($this->user)
        ->get(route('candidats.comparer', [
            $this->offre,
            'ids' => [$this->candidat1->id, 99999],
        ]));

    $response->assertSessionHasErrors('ids.*');
});
