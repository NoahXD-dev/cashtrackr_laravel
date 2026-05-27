<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows empty state when the user has no budgets', function () {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
    $response->assertStatus(200);
    $response->assertSee('No hay presupuestos.');
    $response->assertSee('Comienza creando uno.');
});

it('only shows the authenticated user budgets', function () {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $otherUser = User::factory()->create([
        'email_verified_at'=> now()
    ]);

    Budget::factory()->for($user)->create([
        'name' => 'Mi presupuesto'
    ]);

    Budget::factory()->for($otherUser)->create([
        'name' => 'Otro presupuesto'
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('Mi presupuesto');
    $response->assertDontSee('Otro presupuesto');
});