<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;


class ProductImportController extends Controller
{

    public function import(Request $request)
    {

        $request->validate([

            'file' =>
            'required|mimes:xlsx,csv|max:2048'

        ]);

        $import = new ProductImport();

        Excel::import(

            $import,

            $request->file('file')

        );

        if (count($import->errors)) {


            return response()->json([

                'success' => false,

                'message' => 'Some rows failed validation',

                'errors' => $import->errors


            ], 422);
        }

        return response()->json([

            'success' => true,

            'message' => 'Products imported successfully'

        ]);
    }
}
