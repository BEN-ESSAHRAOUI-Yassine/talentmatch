<?php

use App\Models\Offre;
use App\Models\User;

test('offre factory creates a valid offre', function () {
    $offre = Offre::factory()->create();

    expect($offre)->toBeInstanceOf(Offre::class);
    expect($offre->title)->not->toBeEmpty();
    expect($offre->description)->not->toBeEmpty();
    expect($offre->required_skills)->toBeArray();
    expect($offre->minimum_experience)->toBeInt();
});

test('offre belongs to a user', function () {
    $user = User::factory()->create();
    $offre = Offre::factory()->create(['user_id' => $user->id]);

    expect($offre->user)->toBeInstanceOf(User::class);
    expect($offre->user->id)->toBe($user->id);
});

test('offre has analyses', function () {
    $offre = Offre::factory()->hasAnalyses(3)->create();

    expect($offre->analyses)->toHaveCount(3);
});

test('offre casts required_skills to array', function () {
    $offre = Offre::factory()->create();

    expect($offre->required_skills)->toBeArray();
});

test('offre fillable attributes are mass assignable', function () {
    $offre = Offre::factory()->create([
        'title' => 'Développeur Laravel',
        'description' => 'Description du poste',
        'required_skills' => ['PHP', 'Laravel'],
        'minimum_experience' => 3,
    ]);

    expect($offre->title)->toBe('Développeur Laravel');
    expect($offre->minimum_experience)->toBe(3);
});
