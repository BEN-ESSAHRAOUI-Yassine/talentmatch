<?php

use App\Http\Requests\StoreCandidatRequest;
use App\Models\Offre;
use Illuminate\Support\Facades\Validator;

test('name is required', function () {
    $offre = Offre::factory()->create();

    $validator = Validator::make(['name' => '', 'cv_text' => fake()->paragraphs(5, true)], (new StoreCandidatRequest)->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('name'))->toBeTrue();
});

test('name must be a string', function () {
    $validator = Validator::make(['name' => 123, 'cv_text' => str_repeat('a', 100)], (new StoreCandidatRequest)->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('name'))->toBeTrue();
});

test('name max is 255 characters', function () {
    $validator = Validator::make(['name' => str_repeat('a', 256), 'cv_text' => str_repeat('a', 100)], (new StoreCandidatRequest)->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('name'))->toBeTrue();
});

test('cv_text is required', function () {
    $validator = Validator::make(['name' => 'Jean Dupont', 'cv_text' => ''], (new StoreCandidatRequest)->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('cv_text'))->toBeTrue();
});

test('cv_text must be a string', function () {
    $validator = Validator::make(['name' => 'Jean Dupont', 'cv_text' => 123], (new StoreCandidatRequest)->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('cv_text'))->toBeTrue();
});

test('cv_text minimum is 50 characters', function () {
    $validator = Validator::make(['name' => 'Jean Dupont', 'cv_text' => str_repeat('a', 49)], (new StoreCandidatRequest)->rules());

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('cv_text'))->toBeTrue();
});

test('valid data passes validation', function () {
    $validator = Validator::make([
        'name' => 'Jean Dupont',
        'cv_text' => str_repeat('a', 100),
    ], (new StoreCandidatRequest)->rules());

    expect($validator->passes())->toBeTrue();
});
