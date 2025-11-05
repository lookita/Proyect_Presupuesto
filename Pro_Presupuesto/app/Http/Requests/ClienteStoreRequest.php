<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $cliente = $this->route('cliente');

        return [
            'nombre' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('clientes', 'email')->ignore($cliente), 
            ],
            // El código ya no se requiere en el formulario, pero sigue siendo único
            'codigo' => 'nullable|unique:clientes,codigo', 
        ];
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El formato del email no es válido.',
            'email.unique' => 'Este email ya está registrado.', 
            'codigo.unique' => 'Este código ya está registrado.',
        ];
    }
}
