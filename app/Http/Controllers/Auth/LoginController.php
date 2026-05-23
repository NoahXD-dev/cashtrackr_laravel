<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function store(SignInRequest $request)
    {
        $data = $request->validated();

        if(!Auth::attempt($data)) {
            return redirect()->back()->with('error', 'Credenciales inválidas. Por favor, verifica tu email y contraseña e intenta nuevamente.');
        }

        return redirect()->route('dashboard');
    }
}
