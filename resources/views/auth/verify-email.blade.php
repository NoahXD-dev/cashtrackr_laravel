@extends('layouts.auth')

@section('title', 'Confirma tu cuenta')

@section('auth-content')
    <p class="mt-5 text-lg">Tu cuenta fue creada con éxito. Ahora solo debes confirmarla. Revisa tu email.</p>

    @if(session('success'))
        <x-alert :message="session('success')" />
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        <input 
            type="submit" 
            value='Reenviar Correo de Verificación'
            class="bg-amber-500 w-full text-center mt-5 px-5 py-2 uppercase font-bold cursor-pointer"
        />
    </form>
@endsection