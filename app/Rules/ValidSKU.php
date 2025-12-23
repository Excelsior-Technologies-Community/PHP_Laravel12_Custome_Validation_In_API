<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidSKU implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // SKU must start with 'PROD-', followed by 6 digits
        if (!preg_match('/^PROD-\d{6}$/', $value)) {
            $fail('The :attribute must start with PROD- followed by 6 digits (e.g., PROD-123456).');
        }
    }
}