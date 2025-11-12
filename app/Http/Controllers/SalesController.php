<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SalesDetail;
use App\Models\Product;
use App\Models\Member;
use App\Models\MemberDiscount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sale::with('user', 'member')->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        $members = Member::with('user')->get();
        return view('sales.create', compact('products', 'members'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'items' => json_decode($request->input('items'), true)
        ]);

        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'payment_method' => 'required|in:cash,card,transfer',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ], [
            'items.required' => 'Minimal harus ada 1 item',
            'items.min' => 'Minimal harus ada 1 item',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $invoiceDetails = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi");
                }

                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $invoiceDetails[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $discountAmount = 0;
            $member = null;

            if ($validated['member_id']) {
                $member = Member::findOrFail($validated['member_id']);
                $discountPercentage = MemberDiscount::getDiscount($subtotal);
                $discountAmount = ($subtotal * $discountPercentage) / 100;
            }

            $totalAmount = $subtotal - $discountAmount;

            $sale = Sale::create([
                'invoice_number' => Sale::generateInvoiceNumber(),
                'user_id' => Auth::id(),
                'member_id' => $validated['member_id'],
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'status' => 'completed',
            ]);

            foreach ($invoiceDetails as $detail) {
                SalesDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'unit_price' => $detail['unit_price'],
                    'subtotal' => $detail['subtotal'],
                ]);

                $product = Product::find($detail['product_id']);
                $product->decrement('stock', $detail['quantity']);
            }

            if ($member) {
                $member->increment('total_purchase', $totalAmount);
            }

            DB::commit();

            return redirect()->route('sales.show', $sale->id)->with('success', 'Transaksi berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Transaksi gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Sale $sale)
    {
        $sale->load('user', 'member', 'details.product');
        return view('sales.show', compact('sale'));
    }

    public function invoice(Sale $sale)
    {
        $sale->load('user', 'member', 'details.product');
        return view('sales.invoice', compact('sale'));
    }
}