<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows the owner to view the edit budget form', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create([
        'name' => 'Casa',
        'amount' => 10000,
        'type' => 'goal'
    ]);

    $response = $this->actingAs($user)->get(route('budgets.edit', $budget));
    $response->assertOK();
    $response->assertStatus(200);
    $response->assertSee('Casa');
});

it('does not allow guests to view the edit budget form', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($user)->create();

    $response = $this->get(route('budgets.edit', $budget));
    $response->assertRedirect(route('login'));
});

it('does not allow other users to view the edit budget form', function () {
    $owner = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    
    $otherUser = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $budget = Budget::factory()->for($owner)->create();

    $response = $this->actingAs($otherUser)->get(route('budgets.edit', $budget));
    $response->assertForbidden();
    $response->assertStatus(403);
    $response->assertSee('No tienes permiso para editar este presupuesto.');
});
