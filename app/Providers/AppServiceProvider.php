<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Custom validation for product name
        Validator::extend('product_name_format', function ($attribute, $value, $parameters, $validator) {
            // Product name should contain at least 3 characters and not just numbers
            if (strlen($value) < 3) {
                return false;
            }
            
            // Check if it's not just numbers
            if (preg_match('/^\d+$/', $value)) {
                return false;
            }
            
            return true;
        }, 'The :attribute must be at least 3 characters long and not consist of only numbers.');

        // Custom validation for stock availability
        Validator::extend('stock_availability', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            
            // If product has expiry date and stock is high, ensure it's not too high
            if (isset($data['expiry_date']) && $value > 1000) {
                $expiryDate = \Carbon\Carbon::parse($data['expiry_date']);
                $monthsUntilExpiry = now()->diffInMonths($expiryDate, false);
                
                // If expiry is within 6 months, limit stock
                if ($monthsUntilExpiry > 0 && $monthsUntilExpiry < 6 && $value > 500) {
                    return false;
                }
            }
            
            return true;
        }, 'Stock quantity is too high for products expiring within 6 months.');
    }
}