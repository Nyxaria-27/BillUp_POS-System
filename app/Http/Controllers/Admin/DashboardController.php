<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic Stats
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::sum('total');
        
        // Today's Stats
        $todayRevenue = Transaction::whereDate('created_at', Carbon::today())->sum('total');
        $todayTransactions = Transaction::whereDate('created_at', Carbon::today())->count();
        
        // This Month Stats
        $monthRevenue = Transaction::whereMonth('created_at', Carbon::now()->month)
                                   ->whereYear('created_at', Carbon::now()->year)
                                   ->sum('total');
        $monthTransactions = Transaction::whereMonth('created_at', Carbon::now()->month)
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->count();
        
        // Payment Method Stats
        $cashRevenue = Transaction::where('payment_method', 'cash')->sum('total');
        $cashlessRevenue = Transaction::where('payment_method', 'cashless')->sum('total');
        $cashTransactions = Transaction::where('payment_method', 'cash')->count();
        $cashlessTransactions = Transaction::where('payment_method', 'cashless')->count();
        
        // Low Stock Products
        $lowStockProducts = Product::where('stock', '<=', 10)->orderBy('stock')->take(5)->get();
        
        // Recent Transactions
        $recentTransactions = Transaction::with('user')->latest()->take(10)->get();
        
        // Top Products (most sold) - menggunakan join untuk performa lebih baik
        $topProducts = Product::leftJoin('transaction_items', 'products.id', '=', 'transaction_items.product_id')
                              ->selectRaw('products.*, COALESCE(SUM(transaction_items.quantity), 0) as total_sold')
                              ->groupBy('products.id', 'products.category_id', 'products.name', 'products.description', 'products.price', 'products.stock', 'products.image', 'products.created_at', 'products.updated_at')
                              ->with('category')
                              ->orderBy('total_sold', 'desc')
                              ->take(5)
                              ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalTransactions',
            'totalRevenue',
            'todayRevenue',
            'todayTransactions',
            'monthRevenue',
            'monthTransactions',
            'cashRevenue',
            'cashlessRevenue',
            'cashTransactions',
            'cashlessTransactions',
            'lowStockProducts',
            'recentTransactions',
            'topProducts'
        ));
    }
}
