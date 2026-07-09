<?php

namespace App\Imports;

use App\Models\Product;
use App\Rules\ValidSKU;
use App\Rules\ValidCategory;
use App\Rules\ValidProductStatus;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class ProductImport implements ToCollection
{
    public $errors = [];

    public function collection(Collection $rows)
    {

        foreach ($rows as $index => $row) {

            if ($index == 0) {
                continue;
            }

            $data = [

                'name' => $row[0],

                'sku' => $row[1],

                'price' => $row[2],

                'stock' => $row[3],

                'category' => $row[4],

                'status' => $row[5] ?? 'active'


            ];

            $validator = Validator::make(

                $data,

                [

                    'name' => 'required|string|max:255',

                    'sku' => [
                        'required',
                        'unique:products,sku',
                        new ValidSKU()
                    ],

                    'price' => 'required|numeric|min:0',

                    'stock' => 'required|integer|min:0',

                    'category' => [
                        'required',
                        new ValidCategory()
                    ],


                    'status' => [
                        'required',
                        new ValidProductStatus()
                    ]


                ]

            );

            if ($validator->fails()) {

                $this->errors["Row " . ($index + 1)]
                    =
                    $validator->errors()
                    ->all();


                continue;
            }

            Product::create($data);
        }
    }
}
