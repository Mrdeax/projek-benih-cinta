<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(15);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:products',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
        ], [
            'code.required' => 'Kode barang harus diisi',
            'code.unique' => 'Kode barang sudah ada',
            'name.required' => 'Nama barang harus diisi',
            'price.required' => 'Harga harus diisi',
            'stock.required' => 'Stok harus diisi',
            'minimum_stock.required' => 'Stok minimum harus diisi',
        ]);

        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:products,code,' . $product->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
        ]);

        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        if ($product->salesDetails()->exists()) {
            return back()->with('error', 'Barang tidak dapat dihapus karena sudah ada dalam transaksi');
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Barang berhasil dihapus');
    }
}