<?php

use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Registered;

uses(RefreshDatabase::class);

// Tests para conprobar que la pantalla existe
it('show the registration screen', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
    $response->assertStatus(200);

    $response->assertSee('Crear Cuenta');
    $response->assertSee('Nombre');
    $response->assertSee('Email');
    $response->assertSee('Password');
    $response->assertSee('Registrarme');

    $response->assertSeeInOrder([
        'Crear Cuenta',
        'Nombre',
        'Email',
        'Password',
        'Registrarme'
    ]);
});

// Tests para comprobar que el proceso de registro funciona correctamente
it('register a new user as unverified and dispatches the registered event', function () {
    Event::fake();

    $response = $this->post( route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Pass1234.',
        'password_confirmation' => 'Pass1234.'
    ]);

    $response->assertRedirect( route('verification.notice') );

    $user = User::where('email', 'test@example.com')->first();

    expect($user)->not()->toBeNull();
    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    expect($user->hasVerifiedEmail())->toBeFalse();

    Event::assertDispatched(Registered::class);
});

// Tests para comprobar que la validación de los campos funciona correctamente
it('should validate required fields when the request is empty', function () {
    $response = $this->post( route('register.store'), [
        'name' => '',
        'email' => '',
        'password' => '',
        'password_confirmation' => ''
    ]);

    $response->assertSessionHasErrors([
        'name', 
        'email', 
        'password'
    ]);

    $response->assertSessionHasErrors([
        'name' => 'El nombre es obligatorio', 
        'email' => 'El email es obligatorio', 
        'password' => 'La contraseña es obligatoria'
    ]);
});

// Tests para comprobar que la validación de email funciona correctamente
it('prevents duplicate email addresses', function () {
    User::factory()->create([
        'email' => 'test@example.com'
    ]);

    $response = $this->post(route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Pass1234.',
        'password_confirmation' => 'Pass1234.'
    ]);

    $response->assertRedirect();

    $response->assertSessionHasErrors([
        'email' => 'El email ya está registrado'
    ]);
});

// Tests para comprobar que se envía la notificación de verificación de email después del registro
it('sends the verification email notification after registration', function() {
    Notification::fake();

    $response = $this->post( route('register.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Pass1234.',
        'password_confirmation' => 'Pass1234.'
    ]);

    $user = User::where('email', 'test@example.com')->first();

    Notification::assertSentTo($user, VerifyEmail::class);
});

// Tests para comprobar que el usuario puede verificar su email desde el enlace de verificación
it('verifies the user email from a signed verification link', function() {
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->id, 
            'hash' => sha1($user->email)
        ]
    );

    $response = $this->actingAs($user)->get($verificationUrl);
    $response->assertRedirect( route('dashboard') );

    expect($user->hasVerifiedEmail())->toBeTrue();
});

it('does not allow all unverified user to access the dashboard', function() {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get( route('dashboard') );
    $response->assertRedirect( route('verification.notice') );
});

it('allows verified user to access the dashboard', function() {
    $user = User::factory()->create([
        'email_verified_at' => now()
    ]);

    $response = $this->actingAs($user)->get( route('dashboard') );
    $response->assertOk();
    $response->assertStatus(200);
});