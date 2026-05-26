<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows the owner to delete a budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create();

    $response = $this->actingAs($user)->delete(route('budgets.destroy', $budget));
    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('success','!Presupuesto eliminado correctamente!');

    $this->assertSoftDeleted('budgets', [
        'id' => $budget->id
    ]);
});

it('does not allow guests to delete budgets', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create();

    $response = $this->delete(route('budgets.destroy', $budget));
    $response->assertRedirect(route('login'));

    $this->assertDatabaseHas('budgets', [
        'id' => $budget->id
    ]);
});

it('does not allow unverified users to delete budgets', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $budget = Budget::factory()->for($user)->create();

    $response = $this->actingAs($user)->delete(route('budgets.destroy', $budget));
    $response->assertRedirect(route('verification.notice'));

    $this->assertDatabaseHas('budgets', [
        'id' => $budget->id
    ]);
});

it('does not allow other users to delete budgets', function () {
    $owner = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $otherUser = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($owner)->create([
        'name' => 'Presupueso importante'
    ]);

    $response = $this->actingAs($otherUser)->delete(route('budgets.destroy', $budget));
    $response->assertForbidden();
    $response->assertStatus(403);

    $this->assertDatabaseHas('budgets', [
        'id' => $budget->id,
        'name' => 'Presupueso importante'
    ]);
});
