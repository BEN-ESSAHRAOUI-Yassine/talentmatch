<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('title is required', function () {
    $response = $this->actingAs($this->user)->post(route('offres.store'), [
        'description' => 'Description',
        'required_skills' => ['PHP'],
        'minimum_experience' => 0,
    ]);

    $response->assertSessionHasErrors('title');
});

test('title must be a string', function () {
    $response = $this->actingAs($this->user)->post(route('offres.store'), [
        'title' => 123,
        'description' => 'Description',
        'required_skills' => ['PHP'],
        'minimum_experience' => 0,
    ]);

    $response->assertSessionHasErrors('title');
});

test('title must not exceed 255 characters', function () {
    $response = $this->actingAs($this->user)->post(route('offres.store'), [
        'title' => str_repeat('a', 256),
        'description' => 'Description',
        'required_skills' => ['PHP'],
        'minimum_experience' => 0,
    ]);

    $response->assertSessionHasErrors('title');
});

test('description is required', function () {
    $response = $this->actingAs($this->user)->post(route('offres.store'), [
        'title' => 'Développeur',
        'required_skills' => ['PHP'],
        'minimum_experience' => 0,
    ]);

    $response->assertSessionHasErrors('description');
});

test('required_skills is required', function () {
    $response = $this->actingAs($this->user)->post(route('offres.store'), [
        'title' => 'Développeur',
        'description' => 'Description',
        'minimum_experience' => 0,
    ]);

    $response->assertSessionHasErrors('required_skills');
});

test('required_skills must have at least one item', function () {
    $response = $this->actingAs($this->user)->post(route('offres.store'), [
        'title' => 'Développeur',
        'description' => 'Description',
        'required_skills' => [],
        'minimum_experience' => 0,
    ]);

    $response->assertSessionHasErrors('required_skills');
});

test('minimum_experience is required', function () {
    $response = $this->actingAs($this->user)->post(route('offres.store'), [
        'title' => 'Développeur',
        'description' => 'Description',
        'required_skills' => ['PHP'],
    ]);

    $response->assertSessionHasErrors('minimum_experience');
});

test('minimum_experience must not be negative', function () {
    $response = $this->actingAs($this->user)->post(route('offres.store'), [
        'title' => 'Développeur',
        'description' => 'Description',
        'required_skills' => ['PHP'],
        'minimum_experience' => -1,
    ]);

    $response->assertSessionHasErrors('minimum_experience');
});
