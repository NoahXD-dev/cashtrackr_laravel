<?php

use App\Models\Budget;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows the expense owner to update an expense', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create([
        'name' => 'Supermercado',
        'amount' => 500,
        'category' => 'food'
    ]);

    $response = $this->actingAs($user)->put(route('expenses.update', [$budget, $expense]), [
        'name' => 'Supermercado Bodega',
        'amount' => 750,
        'category' => 'food'
    ]);

    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHas('success','Gasto actualizado correctamente');

    $this->assertDatabaseHas('expenses', [
        'id' => $expense->id,
        'name' => 'Supermercado Bodega',
        'amount' => 750,
        'category' => 'food',
        'budget_id' => $budget->id
    ]);
});

it('does not allow guests to update expenses', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create();
    
    $response = $this->put(route('expenses.update', [$budget, $expense]), [
        'name' => 'Gasto',
        'amount' => 500,
        'category' => 'other'
    ]);

    $response->assertRedirect(route('login'));

    $this->assertDatabaseMissing('expenses', [
        'id' => $expense->id,
        'name' => 'Gasto',
        'amount' => 500,
        'category' => 'other'
    ]);
});

it('does not allow unverified users to update expenses', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create();
    
    $response = $this->actingAs($user)->put(route('expenses.update', [$budget, $expense]), [
        'name' => 'Gasto',
        'amount' => 500,
        'category' => 'other'
    ]);

    $response->assertRedirect(route('verification.notice'));

    $this->assertDatabaseMissing('expenses', [
        'id' => $expense->id,
        'name' => 'Gasto',
        'amount' => 500,
        'category' => 'other'
    ]);
});

it('does not allow other users to update expenses they do not own', function () {
    $owner = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $otherUser = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($owner)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create([
        'name' => 'Original'
    ]);

    $response = $this->actingAs($otherUser)->put(route('expenses.update', [$budget, $expense]), [
        'name'=> 'Hakeado',
        'amount' => 999,
        'category'=> 'food'
    ]);
    $response->assertForbidden();
    $response->assertStatus(403);

    $this->assertDatabaseHas('expenses', [
        'id'=> $expense->id,
        'name' => 'Original'
    ]);
});

it('validates required fields when updating an expense in a general budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create();

    $response = $this->actingAs($user)->from(route('budgets.show', $budget))->put(route('expenses.update', [$budget, $expense]), [
        'name'=> '',
        'amount'=> '',
        'category'=> ''
    ]);
    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHasErrors([
        'name',
        'amount',
        'category',
    ]);
});
