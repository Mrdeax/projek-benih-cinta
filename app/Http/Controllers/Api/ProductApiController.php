<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function getProduct($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'id' => $product->id,
            'code' => $product->code,
            'name' => $product->name,
            'price' => $product->price,
            'stock' => $product->stock,
            'available' => $product->stock > 0
        ]);
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('q');
        
        $products = Product::where('stock', '>', 0)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('code', 'like', '%' . $query . '%');
            })
            ->limit(10)
            ->get(['id', 'code', 'name', 'price', 'stock']);

        return response()->json($products);
    }
}