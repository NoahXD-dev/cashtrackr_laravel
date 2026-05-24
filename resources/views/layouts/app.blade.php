@extends('layouts.base')

@section('content')
    <div class="max-w-5xl mx-auto p-5 lg:p-10">
        @yield('accions')
    </div>

    <main class="mt-5 max-w-5xl mx-auto p-5 lg:p-10 mb-20">
        @yield('dashboard-content')
    </main>
@endsection