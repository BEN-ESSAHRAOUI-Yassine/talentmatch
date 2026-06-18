<?php

use App\Ai\Agents\AnalyseCvAgent;
use App\Jobs\AnalyseCvJob;
use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    Queue::fake();
});

test('job is queued after candidat submission', function () {
    $user = User::factory()->create();
    $offre = Offre::factory()->create(['user_id' => $user->id]);
    $candidat = Candidat::factory()->create();

    AnalyseCvJob::dispatch($offre->id, $candidat->id, 1);

    Queue::assertPushed(AnalyseCvJob::class);
});

test('job processes successfully and updates analyse', function () {
    $offre = Offre::factory()->create([
        'required_skills' => ['PHP', 'Laravel', 'MySQL'],
        'minimum_experience' => 3,
        'description' => 'We need a Laravel developer.',
    ]);
    $candidat = Candidat::factory()->create([
        'cv_text' => str_repeat('Experienced Laravel developer with PHP and MySQL. ', 20),
    ]);
    $analyse = Analyse::factory()->create([
        'offre_id' => $offre->id,
        'candidat_id' => $candidat->id,
        'status' => 'pending',
    ]);

    AnalyseCvAgent::fake(function () {
        return [
            'competences_extraites' => ['PHP', 'Laravel', 'MySQL'],
            'annees_experience' => 5,
            'niveau_etudes' => 'Bac+5',
            'langues' => ['Français', 'Anglais'],
            'matching_score' => 85,
            'points_forts' => ['Expérience Laravel', 'Maîtrise MySQL'],
            'lacunes' => ['Pas de DevOps'],
            'competences_manquantes' => ['Docker'],
            'recommandation' => 'convoquer',
            'justification' => 'Correspond bien au profil recherché.',
        ];
    });

    (new AnalyseCvJob($offre->id, $candidat->id, $analyse->id))->handle();

    $analyse->refresh();

    expect($analyse->status->value)->toBe('completed');
    expect($analyse->matching_score)->toBe(85);
    expect($analyse->recommandation->value)->toBe('convoquer');
    AnalyseCvAgent::assertPrompted('Analyse this CV against the job offer and return the structured analysis.');
});

test('job marks analyse as failed on exception', function () {
    $offre = Offre::factory()->create();
    $candidat = Candidat::factory()->create();
    $analyse = Analyse::factory()->create([
        'offre_id' => $offre->id,
        'candidat_id' => $candidat->id,
        'status' => 'pending',
    ]);

    AnalyseCvAgent::fake(function () {
        throw new RuntimeException('AI service unavailable');
    });

    (new AnalyseCvJob($offre->id, $candidat->id, $analyse->id))->handle();

    $analyse->refresh();

    expect($analyse->status->value)->toBe('failed');
    expect($analyse->error_message)->not->toBeEmpty();
});
