<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'min:3', 'max:100'],
            'description' => ['required', 'min:3', 'max:1000'],
            'prices' => ['required', 'array', 'min:1', 'max:10'],
            'prices.*.value'  => ['required', 'numeric', 'distinct', 'between:0.01,9999999.99']
        ];
    }
}
