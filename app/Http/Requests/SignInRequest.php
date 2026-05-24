<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SignInRequest extends FormRequest
{

    public function attributes(): array
    {
        return [
            'email' => 'email',
            'password' => 'contraseña'
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'No se encontró una cuenta con ese email.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required']
        ];
    }
}
