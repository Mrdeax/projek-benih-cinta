<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\MemberDiscount;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function stockReport()
    {
        $products = Product::all();
        $lowStockProducts = Product::where('stock', '<=', 'minimum_stock')->get();
        $totalValue = Product::selectRaw('SUM(stock * price) as total')->first();
        
        return view('reports.stock', compact('products', 'lowStockProducts', 'totalValue'));
    }

    public function salesReport(Request $request)
    {
        $query = Sale::with('user', 'member');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $sales = $query->paginate(15);
        
        $totalSales = $query->count();
        $totalRevenue = clone $query;
        $totalDiscount = clone $query;
        
        $totalRevenue = $totalRevenue->where('status', 'completed')->sum('total_amount');
        $totalDiscount = $totalDiscount->sum('discount_amount');

        return view('reports.sales', compact('sales', 'totalSales', 'totalRevenue', 'totalDiscount'));
    }

    public function salesPdf(Request $request)
    {
        $query = Sale::with('user', 'member');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $sales = $query->get();
        $totalRevenue = $sales->where('status', 'completed')->sum('total_amount');
        $totalDiscount = $sales->sum('discount_amount');

        return view('reports.sales-pdf', compact('sales', 'totalRevenue', 'totalDiscount'));
    }

    public function stockPdf()
    {
        $products = Product::all();
        $lowStockProducts = Product::where('stock', '<=', 'minimum_stock')->get();
        $totalValue = Product::selectRaw('SUM(stock * price) as total')->first();

        return view('reports.stock-pdf', compact('products', 'lowStockProducts', 'totalValue'));
    }

    public function discountIndex()
    {
        $discounts = MemberDiscount::paginate(10);
        return view('discounts.index', compact('discounts'));
    }

    public function discountCreate()
    {
        return view('discounts.create');
    }

    public function discountStore(Request $request)
    {
        $validated = $request->validate([
            'minimum_purchase' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ], [
            'minimum_purchase.required' => 'Minimum pembelian harus diisi',
            'discount_percentage.required' => 'Persentase diskon harus diisi',
        ]);

        MemberDiscount::create($validated);
        return redirect()->route('discounts.index')->with('success', 'Diskon berhasil ditambahkan');
    }

    public function discountEdit(MemberDiscount $discount)
    {
        return view('discounts.edit', compact('discount'));
    }

    public function discountUpdate(Request $request, MemberDiscount $discount)
    {
        $validated = $request->validate([
            'minimum_purchase' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        $discount->update($validated);
        return redirect()->route('discounts.index')->with('success', 'Diskon berhasil diperbarui');
    }

    public function discountDestroy(MemberDiscount $discount)
    {
        $discount->delete();
        return redirect()->route('discounts.index')->with('success', 'Diskon berhasil dihapus');
    }
}