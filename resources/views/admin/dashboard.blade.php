@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<!-- Main Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm mb-1">Total Pendapatan</p>
                <p class="text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p class="text-blue-100 text-xs mt-2">{{ $totalTransactions }} transaksi</p>
            </div>
            <div class="p-3 bg-white bg-opacity-20 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm mb-1">Hari Ini</p>
                <p class="text-3xl font-bold">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                <p class="text-green-100 text-xs mt-2">{{ $todayTransactions }} transaksi</p>
            </div>
            <div class="p-3 bg-white bg-opacity-20 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm mb-1">Bulan Ini</p>
                <p class="text-3xl font-bold">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</p>
                <p class="text-purple-100 text-xs mt-2">{{ $monthTransactions }} transaksi</p>
            </div>
            <div class="p-3 bg-white bg-opacity-20 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm mb-1">Total Produk</p>
                <p class="text-3xl font-bold">{{ $totalProducts }}</p>
                <p class="text-yellow-100 text-xs mt-2">{{ $totalCategories }} kategori</p>
            </div>
            <div class="p-3 bg-white bg-opacity-20 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

@if(config('features.cashless_payment'))
<!-- Payment Method Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Pembayaran Tunai
        </h3>
        <div class="flex items-end justify-between">
            <div>
                <p class="text-3xl font-bold text-gray-800">Rp {{ number_format($cashRevenue, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-600 mt-2">{{ $cashTransactions }} transaksi</p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-semibold text-green-600">
                    {{ $totalRevenue > 0 ? number_format(($cashRevenue / $totalRevenue) * 100, 1) : 0 }}%
                </p>
                <p class="text-xs text-gray-500">dari total</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            Pembayaran Non-Tunai
        </h3>
        <div class="flex items-end justify-between">
            <div>
                <p class="text-3xl font-bold text-gray-800">Rp {{ number_format($cashlessRevenue, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-600 mt-2">{{ $cashlessTransactions }} transaksi</p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-semibold text-blue-600">
                    {{ $totalRevenue > 0 ? number_format(($cashlessRevenue / $totalRevenue) * 100, 1) : 0 }}%
                </p>
                <p class="text-xs text-gray-500">dari total</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Top Products & Low Stock -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Top Products -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Produk Terlaris</h3>
        </div>
        <div class="p-6">
            @forelse($topProducts as $product)
            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                    <p class="text-sm text-gray-600">{{ $product->category->name }}</p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-blue-600">{{ $product->total_sold ?? 0 }} terjual</p>
                    <p class="text-xs text-gray-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 py-4">Belum ada data penjualan</p>
            @endforelse
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                Stok Menipis
            </h3>
        </div>
        <div class="p-6">
            @forelse($lowStockProducts as $product)
            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                    <p class="text-sm text-gray-600">{{ $product->category->name }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 text-sm font-bold rounded-full {{ $product->stock == 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $product->stock }} unit
                    </span>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500 py-4">Semua stok aman</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Invoice</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kasir</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Metode</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentTransactions as $transaction)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $transaction->invoice_number }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $transaction->user->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                        Rp {{ number_format($transaction->total, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $transaction->payment_method === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($transaction->payment_method) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.transactions.show', $transaction) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Belum ada transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
