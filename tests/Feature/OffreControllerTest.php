<?php

use App\Models\Offre;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();
});

test('guest is redirected to login for index', function () {
    $this->get(route('offres.index'))->assertRedirect(route('login'));
});

test('guest is redirected to login for create', function () {
    $this->get(route('offres.create'))->assertRedirect(route('login'));
});

test('guest is redirected to login for store', function () {
    $this->post(route('offres.store'), [])->assertRedirect(route('login'));
});

test('guest is redirected to login for show', function () {
    $offre = Offre::factory()->create();

    $this->get(route('offres.show', $offre))->assertRedirect(route('login'));
});

test('guest is redirected to login for edit', function () {
    $offre = Offre::factory()->create();

    $this->get(route('offres.edit', $offre))->assertRedirect(route('login'));
});

test('guest is redirected to login for update', function () {
    $offre = Offre::factory()->create();

    $this->put(route('offres.update', $offre), [])->assertRedirect(route('login'));
});

test('guest is redirected to login for destroy', function () {
    $offre = Offre::factory()->create();

    $this->delete(route('offres.destroy', $offre))->assertRedirect(route('login'));
});

test('index shows only authenticated users offres', function () {
    Offre::factory()->count(3)->create(['user_id' => $this->otherUser->id]);
    $myOffres = Offre::factory()->count(2)->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)->get(route('offres.index'));

    $response->assertStatus(200);
    foreach ($myOffres as $offre) {
        $response->assertSee($offre->title);
    }
    $response->assertDontSee(Offre::where('user_id', $this->otherUser->id)->first()->title);
});

test('index paginates results', function () {
    Offre::factory()->count(15)->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)->get(route('offres.index'));

    $response->assertStatus(200);
    $response->assertSee('15');
});

test('index search filters by title', function () {
    Offre::factory()->create(['user_id' => $this->user->id, 'title' => 'Développeur Laravel']);
    Offre::factory()->create(['user_id' => $this->user->id, 'title' => 'Designer UX']);

    $response = $this->actingAs($this->user)->get(route('offres.index', ['search' => 'Laravel']));

    $response->assertStatus(200);
    $response->assertSee('Développeur Laravel');
    $response->assertDontSee('Designer UX');
});

test('store creates an offre for the authenticated user', function () {
    $data = [
        'title' => 'Développeur Laravel',
        'description' => 'Description détaillée du poste.',
        'required_skills' => ['PHP', 'Laravel', 'MySQL'],
        'minimum_experience' => 3,
    ];

    $response = $this->actingAs($this->user)->post(route('offres.store'), $data);

    $this->assertDatabaseHas('offres', [
        'user_id' => $this->user->id,
        'title' => 'Développeur Laravel',
    ]);
    $response->assertRedirect(route('offres.show', Offre::latest()->first()));
});

test('store redirects to show with success flash', function () {
    $data = Offre::factory()->raw(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)->post(route('offres.store'), $data);

    $response->assertSessionHas('success');
});

test('store rejects missing title', function () {
    $response = $this->actingAs($this->user)->post(route('offres.store'), [
        'description' => 'Description',
        'required_skills' => ['PHP'],
        'minimum_experience' => 0,
    ]);

    $response->assertSessionHasErrors('title');
});

test('store rejects empty required_skills', function () {
    $response = $this->actingAs($this->user)->post(route('offres.store'), [
        'title' => 'Développeur',
        'description' => 'Description',
        'required_skills' => [],
        'minimum_experience' => 0,
    ]);

    $response->assertSessionHasErrors('required_skills');
});

test('store rejects negative minimum_experience', function () {
    $response = $this->actingAs($this->user)->post(route('offres.store'), [
        'title' => 'Développeur',
        'description' => 'Description',
        'required_skills' => ['PHP'],
        'minimum_experience' => -1,
    ]);

    $response->assertSessionHasErrors('minimum_experience');
});

test('show displays offre details', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)->get(route('offres.show', $offre));

    $response->assertStatus(200);
    $response->assertSee($offre->title);
    $response->assertSee($offre->description);
});

test('show returns 403 for another users offre', function () {
    $offre = Offre::factory()->create(['user_id' => $this->otherUser->id]);

    $response = $this->actingAs($this->user)->get(route('offres.show', $offre));

    $response->assertStatus(403);
});

test('show returns 404 for missing offre', function () {
    $response = $this->actingAs($this->user)->get('/offres/99999');

    $response->assertStatus(404);
});

test('edit returns pre-filled form', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)->get(route('offres.edit', $offre));

    $response->assertStatus(200);
    $response->assertSee($offre->title);
});

test('edit returns 403 for another users offre', function () {
    $offre = Offre::factory()->create(['user_id' => $this->otherUser->id]);

    $response = $this->actingAs($this->user)->get(route('offres.edit', $offre));

    $response->assertStatus(403);
});

test('update modifies the offre', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)->put(route('offres.update', $offre), [
        'title' => 'Nouveau Titre',
        'description' => $offre->description,
        'required_skills' => $offre->required_skills,
        'minimum_experience' => $offre->minimum_experience,
    ]);

    $this->assertDatabaseHas('offres', ['id' => $offre->id, 'title' => 'Nouveau Titre']);
    $response->assertRedirect(route('offres.show', $offre));
});

test('update redirects with success flash', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);
    $data = $offre->toArray();
    $data['required_skills'] = $offre->required_skills;

    $response = $this->actingAs($this->user)->put(route('offres.update', $offre), $data);

    $response->assertSessionHas('success');
});

test('update returns 403 for another users offre', function () {
    $offre = Offre::factory()->create(['user_id' => $this->otherUser->id]);

    $response = $this->actingAs($this->user)->put(route('offres.update', $offre), [
        'title' => 'Hack',
        'description' => 'Hack',
        'required_skills' => ['Hack'],
        'minimum_experience' => 0,
    ]);

    $response->assertStatus(403);
});

test('destroy deletes the offre', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)->delete(route('offres.destroy', $offre));

    $this->assertModelMissing($offre);
    $response->assertRedirect(route('offres.index'));
});

test('destroy redirects with success flash', function () {
    $offre = Offre::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user)->delete(route('offres.destroy', $offre));

    $response->assertSessionHas('success');
});

test('destroy returns 403 for another users offre', function () {
    $offre = Offre::factory()->create(['user_id' => $this->otherUser->id]);

    $response = $this->actingAs($this->user)->delete(route('offres.destroy', $offre));

    $response->assertStatus(403);
});

test('destroy cascades to related analyses', function () {
    $offre = Offre::factory()->hasAnalyses(2)->create(['user_id' => $this->user->id]);

    $this->actingAs($this->user)->delete(route('offres.destroy', $offre));

    $this->assertDatabaseMissing('analyses', ['offre_id' => $offre->id]);
});
