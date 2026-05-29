<?php

use App\Models\Budget;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows the expense owner to delete an expense', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create([
        'name' => 'Supermercado',
    ]);

    $response = $this->actingAs($user)->delete(route('expenses.destroy', [$budget, $expense]));
    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHas('success','Gasto eliminado correctamente');

    $this->assertSoftDeleted('expenses', [
        'id'=> $expense->id,
    ]);
});

it('does not allow guests to delete expenses', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create();

    $response = $this->delete(route('expenses.destroy', [$budget, $expense]));
    $response->assertRedirect(route(name: 'login'));

    $this->assertDatabaseHas('expenses', [
        'id'=> $expense->id,
    ]);
});

it('does not allow unverified users to delete expenses', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $budget = Budget::factory()->for($user)->create([
        'type' => 'general',
    ]);

    $expense = Expense::factory()->for($budget)->create();

    $response = $this->actingAs($user)->delete(route('expenses.destroy', [$budget, $expense]));
    $response->assertRedirect(route(name: 'verification.notice'));

    $this->assertDatabaseHas('expenses', [
        'id'=> $expense->id,
    ]);
});

it('does not allow other users to delete expenses they do not own', function () {
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
        'name' => 'Supermercado',
    ]);

    $response = $this->actingAs($otherUser)->delete(route('expenses.destroy', [$budget, $expense]));
    $response->assertForbidden();
    $response->assertStatus(403);

    $this->assertDatabaseHas('expenses', [
        'id'=> $expense->id,
    ]);
});
