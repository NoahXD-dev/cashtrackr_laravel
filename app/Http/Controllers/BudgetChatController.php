<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware("auth")]
#[Middleware("verified")]
class BudgetChatController extends Controller
{
    public function store(Request $request, Budget $budget)
    {
        dd('Hola');
    }
}
