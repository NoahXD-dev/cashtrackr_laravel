@extends('layouts.base')

@section('content')
    <div class="max-w-2xl mt-10 mx-auto p-10 shadow-lg">
        <h1 class="text-4xl font-bold">@yield('title')</h1>

        @yield('auth-content')
    </div>
@endsection