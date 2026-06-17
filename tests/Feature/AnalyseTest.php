<?php

use App\Enums\AnalyseStatusEnum;
use App\Enums\RecommandationEnum;
use App\Models\AgentConversation;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;

test('analyse factory creates a valid analyse', function () {
    $analyse = Analyse::factory()->create();

    expect($analyse)->toBeInstanceOf(Analyse::class);
    expect($analyse->status)->toBeInstanceOf(AnalyseStatusEnum::class);
    expect($analyse->status)->toBe(AnalyseStatusEnum::Pending);
});

test('analyse completed factory state works', function () {
    $analyse = Analyse::factory()->completed()->create();

    expect($analyse->status)->toBe(AnalyseStatusEnum::Completed);
    expect($analyse->competences_extraites)->toBeArray();
    expect($analyse->matching_score)->toBeInt();
    expect($analyse->recommandation)->toBeInstanceOf(RecommandationEnum::class);
});

test('analyse failed factory state works', function () {
    $analyse = Analyse::factory()->failed()->create();

    expect($analyse->status)->toBe(AnalyseStatusEnum::Failed);
    expect($analyse->error_message)->not->toBeEmpty();
});

test('analyse belongs to offre', function () {
    $offre = Offre::factory()->create();
    $analyse = Analyse::factory()->create(['offre_id' => $offre->id]);

    expect($analyse->offre)->toBeInstanceOf(Offre::class);
    expect($analyse->offre->id)->toBe($offre->id);
});

test('analyse belongs to candidat', function () {
    $candidat = Candidat::factory()->create();
    $analyse = Analyse::factory()->create(['candidat_id' => $candidat->id]);

    expect($analyse->candidat)->toBeInstanceOf(Candidat::class);
    expect($analyse->candidat->id)->toBe($candidat->id);
});

test('analyse has one agent conversation', function () {
    $analyse = Analyse::factory()->has(AgentConversation::factory(), 'agentConversation')->create();

    expect($analyse->agentConversation)->toBeInstanceOf(AgentConversation::class);
});

test('analyse casts array fields correctly', function () {
    $analyse = Analyse::factory()->completed()->create();

    expect($analyse->competences_extraites)->toBeArray();
    expect($analyse->langues)->toBeArray();
    expect($analyse->points_forts)->toBeArray();
    expect($analyse->lacunes)->toBeArray();
    expect($analyse->competences_manquantes)->toBeArray();
});

test('analyse factory creates unique candidat per analyse', function () {
    $analyse1 = Analyse::factory()->create();
    $analyse2 = Analyse::factory()->create();

    expect($analyse1->candidat_id)->not->toBe($analyse2->candidat_id);
});
