<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows the budget owner to create an expense in a general budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general'
    ]);

    $response = $this->actingAs($user)->post(route('expenses.store', $budget), [
        'name' => 'Gasto',
        'amount' => 300,
        'category' => 'health'
    ]);
    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHas('success','Gasto registrado correctamente');

    $this->assertDatabaseHas('expenses', [
        'name' => 'Gasto',
        'amount' => 300,
        'category' => 'health',
        'budget_id' => $budget->id
    ]);
});

it('allows the budget owner to create an expense in a goal budget without category', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'goal'
    ]);

    $response = $this->actingAs($user)->post(route('expenses.store', $budget), [
        'name' => 'Gasto',
        'amount' => 300
    ]);
    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHas('success','Gasto registrado correctamente');

    $this->assertDatabaseHas('expenses', [
        'name' => 'Gasto',
        'amount' => 300,
        'budget_id' => $budget->id
    ]);
});

it('does not allow guests to create expenses', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'goal'
    ]);

    $response = $this->post(route('expenses.store', $budget), [
        'name' => 'Gasto',
        'amount' => 300
    ]);
    $response->assertRedirect(route('login'));
});

it('does not allow unverified users to create expenses', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'goal'
    ]);

    $response = $this->actingAs($user)->post(route('expenses.store', $budget), [
        'name' => 'Gasto',
        'amount' => 300
    ]);
    $response->assertRedirect(route('verification.notice'));
});

it('does not allow other users to create expenses in someone else budget', function () {
    $owner = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $otherUser = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($owner)->create();

    $response = $this->actingAs($otherUser)->post(route('expenses.store', $budget), [
        'name' => 'Gasto',
        'amount' => 300
    ]);
    $response->assertForbidden();
    $response->assertStatus(403);

    $this->assertDatabaseMissing('expenses', [
        'name' => 'Gasto',
        'amount' => 300,
        'budget_id' => $budget->id
    ]);
});

it('validates required fields when creating an expense in a general budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type'=> 'general'
    ]);

    $response = $this->actingAs($user)->from(route('budgets.show', $budget))->post(route('expenses.store', $budget), [
        'name' => '',
        'amount' => '',
        'category' => ''
    ]);
    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHasErrors([
        'name',
        'amount',
        'category'
    ]);
});

it('validates category must be valid for a general budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type'=> 'general'
    ]);

    $response = $this->actingAs($user)->from(route('budgets.show', $budget))->post(route('expenses.store', $budget), [
        'name' => 'Gasto',
        'amount' => 100,
        'category' => 'invalid_category'
    ]);
    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHasErrors([
        'category'
    ]);
});

it('does not require category for a goal budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type'=> 'goal'
    ]);

    $response = $this->actingAs($user)->from(route('budgets.show', $budget))->post(route('expenses.store', $budget), [
        'name' => '',
        'amount' => '',
        'category' => ''
    ]);
    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHasErrors([
        'name',
        'amount'
    ]);
    $response->assertSessionDoesntHaveErrors(['category']);
});
