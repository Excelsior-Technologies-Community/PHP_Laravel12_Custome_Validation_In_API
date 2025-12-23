## PHP_Laravel12_Custom_Validation_API

A complete Laravel 12 REST API project demonstrating **advanced custom validation techniques** using Form Requests, Custom Rule classes, Validator closures, bulk validation, and clean JSON error handling.

This project is ideal for learning **real‑world Laravel API validation**, interview preparation, and academic submissions (MCA / BCA).

---

## Project Overview

This project demonstrates how to:

* Build a Laravel 12 REST API
* Create reusable custom validation rules
* Use Form Request validation
* Implement validation using closures
* Handle bulk data validation
* Return clean JSON validation responses
* Test APIs using Postman or cURL

---

## Tech Stack

Backend Framework: Laravel 12

Language: PHP 8+

Database: MySQL

API Testing: Postman / cURL

---

## Quick Start

### Prerequisites

* PHP 8.0+
* Composer
* MySQL
* Postman (optional)

---

## Step 1: Create a New Laravel Project

```bash
composer create-project laravel/laravel laravel-custom-validation-api
cd laravel-custom-validation-api
php artisan key:generate
```

---

## Step 2: Database Setup

Update `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_validation
DB_USERNAME=root
DB_PASSWORD=
```

Create database and run migrations:

```bash
mysql -u root -p -e "CREATE DATABASE laravel_validation;"
php artisan migrate
```

---

## Step 3: Product Model & Migration

```bash
php artisan make:model Product -m
```

Products table fields:

* name
* sku (unique)
* price
* stock
* expiry_date
* category
* description

Model uses `$fillable` and `$casts` for clean data handling.

---

## Step 4: Custom Validation Rules

Created custom rule classes:

* ValidSKU
* ValidExpiryDate
* ValidCategory

These rules ensure:

* SKU format consistency
* Logical expiry date ranges
* Restricted category values

All rules are reusable and centralized in `app/Rules/`.

---

## Step 5: Form Request Validation

Used Form Requests for clean validation logic:

* StoreProductRequest
* UpdateProductRequest

Benefits:

* Clean controllers
* Centralized validation
* Custom error messages

---

## Step 6: API Controller

```bash
php artisan make:controller API/ProductController --api
```

Implemented features:

* CRUD operations
* Search
* Category filtering
* Sorting
* Pagination
* Bulk product creation

---

## Step 7: Custom Validation Using Closure

Custom validators added in `AppServiceProvider`:

* product_name_format
* stock_availability

These validations handle complex business logic that cannot be handled by simple rules.

---

## Step 8: API Routes

```php
Route::apiResource('products', ProductController::class);
Route::post('products/bulk', [ProductController::class, 'bulkStore']);
Route::post('validate-product', function () { ... });
```

---

## Step 9: Database Seeder

Seeder creates sample products for testing:

```bash
php artisan make:seeder ProductSeeder
php artisan db:seed
```

---

## Step 10: API Testing

Test endpoints using Postman or cURL:

* GET /api/products
* POST /api/products
* PUT /api/products/{id}
* DELETE /api/products/{id}
* POST /api/products/bulk
* POST /api/validate-product

Both success and validation error responses are handled properly.

---

## Step 11: Custom Exception Handler

Overrides validation error responses for consistent JSON output when API requests fail.

---

## Project Structure Summary

```
laravel-custom-validation-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/API
│   │   └── Requests
│   ├── Rules
│   └── Providers
├── routes/api.php
├── database/
│   ├── migrations
│   └── seeders
└── .env
```

---

## Key Features Implemented

* Custom Validation Rule Classes
* Form Request Validation
* Validator Extensions with Closures
* RESTful API Resource Controller
* Bulk Validation
* Search, Filter, Sort APIs
* Clean JSON Error Handling

---

## Use Cases

* Laravel API validation reference
* MCA / BCA final year projects
* Interview preparation
* Real‑world Laravel backend development

---
## Screenshot
<img width="1377" height="943" alt="image" src="https://github.com/user-attachments/assets/062b9d8c-07a2-44ae-b7e2-ec134e934b4b" />
<img width="1399" height="987" alt="image" src="https://github.com/user-attachments/assets/7877ffa7-6402-477b-9f41-47f78e27754a" />

