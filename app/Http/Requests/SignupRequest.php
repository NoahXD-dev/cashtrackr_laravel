<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SignupRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'email' => 'email',
            'password' => 'contraseña'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El :attribute es obligatorio',
            'email.required' => 'El :attribute es obligatorio',
            'email.email' => 'El :attribute debe ser una dirección de correo electrónico válida',
            'email.unique' => 'El :attribute ya está registrado',
            'password.required' => 'El :attribute es obligatorio',
            'password.confirmed' => 'Las :attributes no coinciden',
            'password.min' => 'La :attribute debe tener al menos :min caracteres',
            'password.letters' => 'La :attribute debe contener al menos 1 letra',
            'password.mixed' => 'La :attribute debe contener al menos 1 letra mayúscula y 1 letra minúscula',
            'password.symbols' => 'La :attribute debe contener al menos 1 carácter especial',
            'password.numbers' => 'La :attribute debe contener al menos 1 número',
            'password.uncompromised' => 'La :attribute ha sido comprometida en una filtración de datos. Por favor, elige una contraseña diferente.',
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
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->symbols()
                    ->numbers()
                    ->uncompromised()
            ]
        ];
    }
}
