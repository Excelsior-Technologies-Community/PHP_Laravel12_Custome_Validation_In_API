<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidProductStatus implements ValidationRule
{

    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {


        $statuses = [
            'active',
            'inactive',
            'draft',
            'discontinued'
        ];


        if(!in_array($value,$statuses))
        {
            $fail(
                'The selected product status is invalid.'
            );
        }

    }

}