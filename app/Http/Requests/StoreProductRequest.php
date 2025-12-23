<?php

namespace App\Http\Requests;

use App\Rules\ValidCategory;
use App\Rules\ValidExpiryDate;
use App\Rules\ValidSKU;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:3',
            'sku' => ['required', 'unique:products,sku', new ValidSKU],
            'price' => 'required|numeric|min:0.01|max:1000000',
            'stock' => 'required|integer|min:0|max:10000',
            'expiry_date' => ['nullable', 'date', 'after:today', new ValidExpiryDate],
            'category' => ['required', 'string', new ValidCategory],
            'description' => 'nullable|string|max:1000'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required',
            'sku.unique' => 'This SKU already exists',
            'price.min' => 'Price must be at least 0.01',
            'expiry_date.after' => 'Expiry date must be a future date',
        ];
    }

    public function attributes(): array
    {
        return [
            'expiry_date' => 'expiry date',
            'sku' => 'SKU',
        ];
    }
}