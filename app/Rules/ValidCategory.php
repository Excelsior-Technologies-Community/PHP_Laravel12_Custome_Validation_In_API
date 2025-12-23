<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCategory implements ValidationRule
{
    private array $validCategories = [
        'electronics',
        'clothing',
        'books',
        'home',
        'sports',
        'beauty'
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array(strtolower($value), $this->validCategories)) {
            $fail('The :attribute must be one of: ' . implode(', ', $this->validCategories) . '.');
        }
    }
}