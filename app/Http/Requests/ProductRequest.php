<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->user()->tokenCan('add-product') or request()->user()->tokenCan('update-product');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'bail|required|string|max:255',
            'description' => 'bail|required|string',
            'price' => 'bail|required|numeric',
            'quantity' => 'bail|required|integer',
            'image' => request()->isMethod('post') ? 'bail|required|file|mimes:png,jpg' : 'bail|nullable|string'
        ];
    }
}
