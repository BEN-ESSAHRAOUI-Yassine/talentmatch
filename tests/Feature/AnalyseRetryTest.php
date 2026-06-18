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
    $this->candidat = Candidat::factory()->create();
    $this->analyse = Analyse::factory()->failed()->create([
        'offre_id' => $this->offre->id,
        'candidat_id' => $this->candidat->id,
    ]);
});

test('retry resets analyse status to pending and dispatches job', function () {
    Queue::fake();

    $response = $this->actingAs($this->user)
        ->post(route('analyses.retry', $this->analyse));

    $response->assertSessionHas('success');

    $this->analyse->refresh();
    expect($this->analyse->status->value)->toBe('pending');
    expect($this->analyse->error_message)->toBeNull();

    Queue::assertPushed(AnalyseCvJob::class, function ($job) {
        return $job->offreId === $this->offre->id
            && $job->candidatId === $this->candidat->id
            && $job->analyseId === $this->analyse->id;
    });
});

test('retry returns 403 for another users analyse', function () {
    $this->actingAs($this->otherUser)
        ->post(route('analyses.retry', $this->analyse))
        ->assertForbidden();
});

test('retry returns 404 for non-existent analyse', function () {
    $this->actingAs($this->user)
        ->post('/analyses/99999/retry')
        ->assertStatus(404);
});
