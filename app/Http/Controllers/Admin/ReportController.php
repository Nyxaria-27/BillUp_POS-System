<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'today');
        $startDate = null;
        $endDate = null;

        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::tomorrow();
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::today();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'custom':
                $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::today();
                $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : Carbon::tomorrow();
                break;
        }

        // Revenue Stats
        $totalRevenue = Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total');
        $totalDiscount = Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('discount');
        $totalTransactions = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();
        
        // Payment Method Breakdown
        $cashRevenue = Transaction::whereBetween('created_at', [$startDate, $endDate])
                                  ->where('payment_method', 'cash')
                                  ->sum('total');
        $cashlessRevenue = Transaction::whereBetween('created_at', [$startDate, $endDate])
                                      ->where('payment_method', 'cashless')
                                      ->sum('total');
        $cashCount = Transaction::whereBetween('created_at', [$startDate, $endDate])
                                ->where('payment_method', 'cash')
                                ->count();
        $cashlessCount = Transaction::whereBetween('created_at', [$startDate, $endDate])
                                    ->where('payment_method', 'cashless')
                                    ->count();

        // Average Transaction
        $averageTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // Top Selling Products
        $topProducts = TransactionItem::selectRaw('product_name, sum(quantity) as total_quantity, sum(subtotal) as total_revenue')
                                      ->whereHas('transaction', function($query) use ($startDate, $endDate) {
                                          $query->whereBetween('created_at', [$startDate, $endDate]);
                                      })
                                      ->groupBy('product_name')
                                      ->orderBy('total_quantity', 'desc')
                                      ->take(10)
                                      ->get();

        // Daily Revenue Chart Data (last 7 days or within period)
        $dailyRevenue = Transaction::whereBetween('created_at', [$startDate, $endDate])
                                   ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as transactions')
                                   ->groupBy('date')
                                   ->orderBy('date', 'asc')
                                   ->get();

        // Cashier Performance
        $cashierPerformance = Transaction::with('user')
                                         ->whereBetween('created_at', [$startDate, $endDate])
                                         ->selectRaw('user_id, COUNT(*) as total_transactions, SUM(total) as total_revenue')
                                         ->groupBy('user_id')
                                         ->orderBy('total_revenue', 'desc')
                                         ->get();

        return view('admin.reports.index', compact(
            'period',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalDiscount',
            'totalTransactions',
            'cashRevenue',
            'cashlessRevenue',
            'cashCount',
            'cashlessCount',
            'averageTransaction',
            'topProducts',
            'dailyRevenue',
            'cashierPerformance'
        ));
    }
}
