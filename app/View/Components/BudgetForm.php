<?php

namespace App\View\Components;

use Closure;
use App\Models\Budget;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BudgetForm extends Component
{
    public ?Budget $budget;

    /**
     * Create a new component instance.
     */
    public function __construct(Budget $budget)
    {
        $this->budget = $budget;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.budget-form');
    }
}
