<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\BudgetType;

class BudgetRequest extends FormRequest
{
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del presupuesto es obligatorio',
            'amount.required' => 'La cantidad es obligatoria',
            'amount.decimal' => 'La cantidad debe ser un número válido',
            'amount.min' => 'La cantidad debe ser mayor a 0',
            'type.required' => 'El tipo de presupuesto es obligatorio',
            'type.in' => 'El tipo de presupuesto no es válido',
        ];
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'decimal:0,2', 'min:0.01'],
            'type' => ['required', new Enum(BudgetType::class)],
        ];
    }
}