<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ProductController extends Controller
{
    public function index(): InertiaResponse
    {
        return Inertia::render('Admin/ProductsPage', [
            'products' => Product::all(),
        ]);
    }

    public function setDimensions(Request $request): JsonResponse
    {
        $product = Product::findOrFail($request->product_id);

        $product->dimensions = $request->dimensions;
        $product->save();

        return response()->json(['ok']);
    }
}
