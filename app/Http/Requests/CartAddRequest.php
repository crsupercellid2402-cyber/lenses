<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CartAddRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'rx.sph' => 'nullable|numeric',
            'rx.cyl' => 'nullable|numeric',
            'rx.axis' => 'nullable|integer|min:0|max:180',
            'rx.add' => 'nullable|numeric',
            'rx.prism' => 'nullable|numeric',
        ];
    }
}
