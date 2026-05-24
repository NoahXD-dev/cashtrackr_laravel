<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Test para verificar que la pantalla de login se muestra correctamente
it('show the login screen', function () {
    $response = $this->get( route('login') );

    $response->assertOk();
    $response->assertStatus(200);

    $response->assertSee('Iniciar Sesión');
    $response->assertSee('Email');
    $response->assertSee('Password');
    $response->assertSee('¿Olvidaste tu Contraseña?');

    $response->assertSeeInOrder([
        'Iniciar Sesión',
        'Email',
        'Password',
        '¿Olvidaste tu Contraseña?'
    ]);
});

// Test para verificar que un usuario verificado puede iniciar sesión correctamente
it('logs in a verified user successfully', function () {
    User::factory()->create([
        'email' => 'user@example.com',
        'password' => bcrypt('Pass1234.'),
        'email_verified_at' => now()
    ]);

    $response = $this->post( route('login.store', [
        'email' => 'user@example.com',
        'password' => 'Pass1234.'
    ]));

    $response->assertRedirect( route('dashboard') );
    $this->assertAuthenticated();
});

// Test para verificar el manejo de credenciales inválidas al intentar iniciar sesión
it('does not log in with invalid credentials', function () {
    User::factory()->create([
        'email' => 'user@example.com',
        'password' => bcrypt('Pass1234.')
    ]);

    $response = $this->from( route('login') )->post( route('login.store', [
        'email' => 'user@example.com',
        'password' => 'WrongPassword'
    ]));

    $response->assertRedirect( route('login') );
    $response->assertSessionHas('error', 'Credenciales inválidas. Por favor, verifica tu email y contraseña e intenta nuevamente.');
    $this->assertGuest();
});

// Test para verificar que un usuario no verificado no pueda acceder al dashboard
it('prevents unverified users from accessing dashboard', function () {
    User::factory()->unverified()->create([
        'email' => 'user@example.com',
        'password' => bcrypt('Pass1234.')
    ]);

    $response = $this->post( route('login.store', [
        'email' => 'user@example.com',
        'password' => 'Pass1234.'
    ]));

    $response->assertRedirect( route('dashboard') );
    $this->assertAuthenticated();

    $dashboardResponse = $this->get( route('dashboard') );
    $dashboardResponse->assertRedirect( route('verification.notice') );
});

// Test para verificar que un usuario no verificado no pueda acceder al dashboard incluso si está autenticado
it('does not allow access to dashboard if email is not verified', function () {
    $user = User::factory()->create([
        'email_verified_at' => null
    ]);

    $response = $this->actingAs($user)->get( route('dashboard') );
    $response->assertRedirect( route('verification.notice') );
});

// Test para verificar que un usuario verificado pueda acceder al dashboard sin problemas
it('allows access to dashboard if email is verified', function () {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->get( route('dashboard') );
    $response->assertOk();
});

it('fails login if user does not exist', function () {
    $response = $this->from( route('login') )->post( route('login.store', [
        'email' => 'nonexistent@example.com',
        'password' => 'Pass1234.'
    ]));

    
    $response->assertRedirect( route('login') );
    $response->assertSessionHasErrors('email', 'No se encontró una cuenta con ese email.');
    $this->assertGuest();
});