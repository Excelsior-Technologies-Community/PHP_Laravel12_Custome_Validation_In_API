<?php

namespace App\Http\Requests;

use App\Rules\ValidCategory;
use App\Rules\ValidExpiryDate;
use App\Rules\ValidSKU;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product');

        return [
            'name' => 'sometimes|string|max:255|min:3',
            'sku' => ['sometimes', "unique:products,sku,{$productId}", new ValidSKU],
            'price' => 'sometimes|numeric|min:0.01|max:1000000',
            'stock' => 'sometimes|integer|min:0|max:10000',
            'expiry_date' => ['sometimes', 'nullable', 'date', 'after:today', new ValidExpiryDate],
            'category' => ['sometimes', 'string', new ValidCategory],
            'description' => 'sometimes|nullable|string|max:1000'
        ];
    }
}