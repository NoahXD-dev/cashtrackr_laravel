<?php

namespace App\Ai\Tools;

use App\Models\Expense;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchExpenses implements Tool
{
    public function __construct(
        public int $budgetId
    ) {}
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Busca gastos del presupuesto actual, puedes filtrar por nombre, gastos más barato o costoso, buscar gastos por categoría o traer y mostrar todos los gastos';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $query = Expense::where('budget_id', $this->budgetId);

        if ($request['name'] ?? null) {
            $query->where('name', 'ilike', '%' . $request['name'] . '%');
        }

        if ($request['category'] ?? null) {
            $query->where('category', 'ilike', '%' . $request['category'] . '%');
        }

        $expenses = $query->get(['name', 'amount', 'category', 'created_at']);

        if ($expenses->isEmpty()) {
            return 'No se encontraron gastos con esos criterios.';
        }

        $total = $expenses->sum('amount');

        return "Gastos encontrados ({$expenses->count()}):\n" .
        $expenses->map(function ($e) {
            $cat = $e->category ? $e->category->label() : 'Sin categoría';
            return "- {$e->name}: \${$e->amount} ({$cat})";
        })->implode("\n") .
        "\n\nTotal: \${$total}";
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Texto para buscar en el nombre del gasto (ej: Uber, comida, renta, dentista)'),
            'category' => $schema->string()->description('Categoría del gasto (ej: food, transport, hosuing, suscriptions, other)')
        ];
    }
}
