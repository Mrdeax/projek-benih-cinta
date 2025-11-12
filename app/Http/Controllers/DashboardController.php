<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\User;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $totalSales = Sale::count();
            $totalRevenue = Sale::where('status', 'completed')->sum('total_amount');
            $totalProducts = Product::count();
            $totalUsers = User::count();
            $recentSales = Sale::with('user', 'member')->latest()->take(5)->get();
            $lowStockProducts = Product::where('stock', '<=', 'minimum_stock')->get();

            return view('dashboard.admin', compact('totalSales', 'totalRevenue', 'totalProducts', 'totalUsers', 'recentSales', 'lowStockProducts'));
        } elseif ($user->isOfficer()) {
            $totalSalesDay = Sale::whereDate('created_at', date('Y-m-d'))->count();
            $totalRevenueDay = Sale::whereDate('created_at', date('Y-m-d'))->where('status', 'completed')->sum('total_amount');
            $totalProducts = Product::count();
            $lowStockProducts = Product::where('stock', '<=', 'minimum_stock')->take(5)->get();

            return view('dashboard.officer', compact('totalSalesDay', 'totalRevenueDay', 'totalProducts', 'lowStockProducts'));
        } else {
            $member = $user->member;
            $totalPurchase = $member ? $member->total_purchase : 0;
            $recentPurchases = Sale::where('member_id', $member?->id)->latest()->take(5)->get();

            return view('dashboard.member', compact('totalPurchase', 'recentPurchases'));
        }
    }
}