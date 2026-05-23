@extends('layouts.auth')

@section('title', 'Administra tus presupuestos')

@section('auth-content')
    @if(session('success'))
        <p class="my-10 text-center border border-green-400 bg-green-100 py-3 text-green-700 text-sm">
            {{ session('success') }}
        </p>
    @endif
@endsection