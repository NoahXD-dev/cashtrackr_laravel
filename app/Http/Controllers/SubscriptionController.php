<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware('auth')]
#[Middleware('verified')]
class SubscriptionController extends Controller
{
    public function show()
    {

    }

    public function swap(Request $request, string $plan)
    {

    }

    public function cancel()
    {

    }

    public function resume()
    {

    }
}
