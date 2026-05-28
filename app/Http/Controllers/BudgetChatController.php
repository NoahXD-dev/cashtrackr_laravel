<?php

namespace App\Http\Controllers;

use App\Ai\Agents\BudgetAssistant;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware("auth")]
#[Middleware("verified")]
class BudgetChatController extends Controller
{
    public function store(Request $request, Budget $budget)
    {
        $messages = $request->input("messages", []);
        $lastMessage = collect($messages)->last();
        $prompt = collect(data_get($lastMessage, 'parts', []))
            ->where('type', 'text')
            ->pluck('text')
            ->implode(' ')
            ?: data_get($lastMessage, 'content', '');

        $agent = new BudgetAssistant;
        $agent->budgetId = $budget->id;
        $agent->hasCategories = $budget->isGeneral();
        $agent->budgetContext = ($budget->isGoal()) 
            ? "Este presupuesto es de tipo Meta/Objetivo llamado '{$budget->name}' con un monto total de \${$budget->amount}. Los gastos NO tienen categorías, solo nombre y monto." 
            : "Este presupuesto es de tipo General llamado '{$budget->name}' con un monto total de \${$budget->amount}. Los gastos tienen nombre, monto y categoría.";

        return $agent->stream(
            $prompt, 
            provider: 'openrouter', 
            model: 'poolside/laguna-xs.2:free'
            // model: 'deepseek/deepseek-v4-flash:free'
            // model: 'google/gemma-4-31b-it:free'
            // model: 'qwen/qwen3-next-80b-a3b-instruct:free'
            // model: 'minimax/minimax-m2.5:free'
        )->usingVercelDataProtocol();
    }
}
