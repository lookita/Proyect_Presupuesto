<?php
//Valida los datos del formulario
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePresupuestoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_emision' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario' => 'required|numeric|min:0',
            'items.*.descuento' => 'nullable|numeric|min:0|max:100',
        ];
    }

    public function messages()
    {
        return [
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'items.required' => 'Debe agregar al menos un producto.',
            'items.*.cantidad.min' => 'La cantidad debe ser mayor a 0.',
        ];
    }
}
