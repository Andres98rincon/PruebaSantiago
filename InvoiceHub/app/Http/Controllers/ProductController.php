<?php

namespace App\Http\Controllers;

use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'message' => 'listado de productps',
            'code' => 200,
            'data' => $products,
        ]);
    }
}
