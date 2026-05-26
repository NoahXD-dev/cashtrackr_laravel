<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('validates required fields when creating a budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->from(route('budgets.create'))
        ->post(route('budgets.store'), [
            'name' => '',
            'amount' => '',
            'type' => ''
        ]);

    $response->assertRedirect(route('budgets.create'));
    $response->assertSessionHasErrors([
        'name',
        'amount',
        'type'
    ]);
});

it('does not allow guest to create budget', function () {
    $response = $this->post(route('budgets.store'), [
        'name' => 'Boda',
        'amount' => 1000,
        'type' => 'goal'
    ]);

    $response->assertRedirect(route('login'));
});

it('assing the created budget to the authenticated user', function() {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $this->actingAs($user)->post(route('budgets.store'), [
        'name' => 'Viaje a las vegas',
        'amount' => 10000,
        'type' => 'goal'
    ]);

    $this->assertDatabaseHas('budgets', [
        'name' => 'Viaje a las vegas',
        'amount' => 10000,
        'type' => 'goal',
        'user_id' => $user->id
    ]);

    $budget = Budget::first();

    expect($budget->user_id)->toBe($user->id);
});

it('creates a budget and redirects with success message', function() {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->post(route('budgets.store'), [
        'name' => 'Viaje a las vegas',
        'amount' => 10000,
        'type' => 'goal'
    ]);

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('success','!Presupuesto creado correctamente!');
});

it('does not allow unverifield to create budgets', function() {
    $user = User::factory()->create([
        'email_verified_at' => null
    ]);

    $response = $this->actingAs($user)->post(route('budgets.store'), [
        'name' => 'Viaje a las vegas',
        'amount' => 10000,
        'type' => 'goal'
    ]);

    $response->assertRedirect(route('verification.notice'));
});

it('validates amount must be greater than zero', function () {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->from(route('budgets.create'))
        ->post(route('budgets.store'), [
            'name' => 'Boda',
            'amount' => -10,
            'type' => 'general'
        ]);

    $response->assertRedirect(route('budgets.create'));
    $response->assertSessionHasErrors(['amount']);
});

it('validate type must be valid', function () {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->from(route('budgets.create'))
        ->post(route('budgets.store'), [
            'name' => 'Boda',
            'amount' => 100,
            'type' => 'not_valid'
        ]);

    $response->assertRedirect(route('budgets.create'));
    $response->assertSessionHasErrors(['type']);
});

it('accept valid budget types', function () {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->post(route('budgets.store'), [
        'name' => 'Boda',
        'amount' => 100,
        'type' => 'general'
    ]);

    $response->assertSessionDoesntHaveErrors();

    $this->assertDatabaseHas('budgets', [
        'name' => 'Boda',
        'amount' => 100,
        'type' => 'general',
        'user_id' => $user->id
    ]);
});