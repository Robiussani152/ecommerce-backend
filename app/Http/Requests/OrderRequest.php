<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'total_amount' => 'bail|required|numeric',
            'instruction' => 'bail|nullable|string',
            'items.*.product_id' => 'bail|required|exists:products,id',
            'items.*.price' => 'bail|required|numeric',
            'items.*.quantity' => 'bail|required|numeric',
            'items.*.total_price' => 'bail|required|numeric',
        ];
    }
}
