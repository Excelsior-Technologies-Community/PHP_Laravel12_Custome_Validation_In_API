<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidExpiryDate implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            // Parse the input date (ensure we're comparing dates only, not times)
            $expiryDate = \Carbon\Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
            $today = \Carbon\Carbon::today()->startOfDay();
            
            // Calculate the difference in days
            $daysUntilExpiry = $today->diffInDays($expiryDate, false);
            
            \Log::info('Expiry Date Validation:', [
                'today' => $today->toDateString(),
                'expiry_date' => $expiryDate->toDateString(),
                'days_until_expiry' => $daysUntilExpiry,
                'input_value' => $value
            ]);
            
            // Validate minimum 30 days
            if ($daysUntilExpiry < 30) {
                $fail("The :attribute must be at least 30 days from today. You entered {$expiryDate->format('Y-m-d')} which is {$daysUntilExpiry} days from today.");
                return;
            }
            
            // Validate maximum 5 years (1825 days)
            if ($daysUntilExpiry > 1825) { // 5 years * 365 days
                $fail('The :attribute cannot be more than 5 years from today.');
                return;
            }
            
        } catch (\Exception $e) {
            \Log::error('Date parsing error in ValidExpiryDate:', [
                'error' => $e->getMessage(),
                'value' => $value
            ]);
            $fail('The :attribute must be a valid date in Y-m-d format.');
        }
    }
}