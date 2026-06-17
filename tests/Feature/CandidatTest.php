<?php

use App\Models\Analyse;
use App\Models\Candidat;

test('candidat factory creates a valid candidat', function () {
    $candidat = Candidat::factory()->create();

    expect($candidat)->toBeInstanceOf(Candidat::class);
    expect($candidat->name)->not->toBeEmpty();
    expect($candidat->cv_text)->not->toBeEmpty();
});

test('candidat has one analyse', function () {
    $candidat = Candidat::factory()->has(Analyse::factory(), 'analyse')->create();

    expect($candidat->analyse)->toBeInstanceOf(Analyse::class);
});

test('candidat fillable attributes are mass assignable', function () {
    $candidat = Candidat::factory()->create([
        'name' => 'Jean Dupont',
        'cv_text' => 'Expérience en développement Laravel depuis 5 ans...',
    ]);

    expect($candidat->name)->toBe('Jean Dupont');
    expect($candidat->cv_text)->toBe('Expérience en développement Laravel depuis 5 ans...');
});
