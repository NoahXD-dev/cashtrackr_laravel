<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware('auth')]
#[Middleware('verified')]
class LogoutController extends Controller
{
    public function store(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
