@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
<!-- Filter Section -->
<div class="bg-white rounded-lg shadow p-6 mb-6 no-print">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Filter Laporan</h2>
    <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Periode</label>
            <select name="period" id="period" onchange="toggleCustomDate()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
                <option value="yesterday" {{ $period == 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Custom</option>
            </select>
        </div>

        <div id="customDateFields" class="md:col-span-2 grid grid-cols-2 gap-4" style="display: {{ $period == 'custom' ? 'grid' : 'none' }}">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>

        <div class="flex items-end">
            <button type="submit" class="w-full px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:shadow-lg transition-all duration-300">
                Tampilkan
            </button>
        </div>
    </form>
</div>

<script>
function toggleCustomDate() {
    const period = document.getElementById('period').value;
    const customFields = document.getElementById('customDateFields');
    customFields.style.display = period === 'custom' ? 'grid' : 'none';
}
</script>

<!-- Period Display -->
<div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-6 mb-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold mb-2">Periode Laporan</h3>
            <p class="text-2xl font-bold">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
        </div>
        <button onclick="window.print()" class="px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition-all duration-300 no-print">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak Laporan
        </button>
    </div>
</div>

<div class="print-content">

<!-- Main Stats -->
<div class="grid grid-cols-1 md:grid-cols-{{ config('features.cashless_payment') ? '4' : (config('features.discount') ? '3' : '2') }} gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-600">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600 uppercase">Total Pendapatan</h3>
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p class="text-3xl font-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        <p class="text-sm text-gray-600 mt-2">{{ $totalTransactions }} transaksi</p>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-600">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600 uppercase">Pembayaran Tunai</h3>
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
        <p class="text-3xl font-bold text-gray-800">Rp {{ number_format($cashRevenue, 0, ',', '.') }}</p>
        <p class="text-sm text-gray-600 mt-2">{{ $cashCount }} transaksi @if(config('features.cashless_payment'))({{ $totalRevenue > 0 ? number_format(($cashRevenue / $totalRevenue) * 100, 1) : 0 }}%)@endif</p>
    </div>

    @if(config('features.cashless_payment'))
    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-600">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600 uppercase">Pembayaran Non-Tunai</h3>
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
        </div>
        <p class="text-3xl font-bold text-gray-800">Rp {{ number_format($cashlessRevenue, 0, ',', '.') }}</p>
        <p class="text-sm text-gray-600 mt-2">{{ $cashlessCount }} transaksi ({{ $totalRevenue > 0 ? number_format(($cashlessRevenue / $totalRevenue) * 100, 1) : 0 }}%)</p>
    </div>
    @endif

    @if(config('features.discount'))
    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-600">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-600 uppercase">Total Diskon</h3>
            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
        </div>
        <p class="text-3xl font-bold text-gray-800">Rp {{ number_format($totalDiscount ?? 0, 0, ',', '.') }}</p>
        <p class="text-sm text-gray-600 mt-2">diberikan ke pelanggan</p>
    </div>
    @endif
</div>

<!-- Charts and Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Top Selling Products -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Produk Terlaris</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 px-2 text-sm font-semibold text-gray-700">Produk</th>
                            <th class="text-right py-3 px-2 text-sm font-semibold text-gray-700">Terjual</th>
                            <th class="text-right py-3 px-2 text-sm font-semibold text-gray-700">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $product)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-2 text-sm text-gray-800">{{ $product->product_name }}</td>
                            <td class="py-3 px-2 text-sm text-right font-semibold text-gray-800">{{ $product->total_quantity }}</td>
                            <td class="py-3 px-2 text-sm text-right text-blue-600 font-semibold">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-gray-500">Belum ada data penjualan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Cashier Performance -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Performa Kasir</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 px-2 text-sm font-semibold text-gray-700">Kasir</th>
                            <th class="text-right py-3 px-2 text-sm font-semibold text-gray-700">Transaksi</th>
                            <th class="text-right py-3 px-2 text-sm font-semibold text-gray-700">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cashierPerformance as $cashier)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-2 text-sm text-gray-800">{{ $cashier->user->name }}</td>
                            <td class="py-3 px-2 text-sm text-right font-semibold text-gray-800">{{ $cashier->total_transactions }}</td>
                            <td class="py-3 px-2 text-sm text-right text-green-600 font-semibold">Rp {{ number_format($cashier->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-gray-500">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Daily Revenue Chart -->
<div class="bg-white rounded-lg shadow mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Grafik Pendapatan Harian</h3>
    </div>
    <div class="p-6">
        @if($dailyRevenue->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Tanggal</th>
                        <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Transaksi</th>
                        <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Pendapatan</th>
                        <th class="py-3 px-4 text-sm font-semibold text-gray-700">Grafik</th>
                    </tr>
                </thead>
                <tbody>
                    @php $maxRevenue = $dailyRevenue->max('revenue'); @endphp
                    @foreach($dailyRevenue as $day)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm text-gray-800">{{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}</td>
                        <td class="py-3 px-4 text-sm text-right text-gray-800">{{ $day->transactions }}</td>
                        <td class="py-3 px-4 text-sm text-right font-semibold text-blue-600">Rp {{ number_format($day->revenue, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-4 rounded-full" style="width: {{ $maxRevenue > 0 ? ($day->revenue / $maxRevenue * 100) : 0 }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 font-bold">
                        <td class="py-3 px-4 text-sm text-gray-800">Total</td>
                        <td class="py-3 px-4 text-sm text-right text-gray-800">{{ $dailyRevenue->sum('transactions') }}</td>
                        <td class="py-3 px-4 text-sm text-right text-blue-600">Rp {{ number_format($dailyRevenue->sum('revenue'), 0, ',', '.') }}</td>
                        <td class="py-3 px-4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <p class="text-center text-gray-500 py-8">Belum ada data untuk periode ini</p>
        @endif
    </div>
</div>

<!-- Summary Stats -->
{{-- <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Laporan</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg p-4 shadow">
            <p class="text-sm text-gray-600 mb-1">Total Pendapatan Kotor</p>
            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalRevenue + $totalDiscount, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow">
            <p class="text-sm text-gray-600 mb-1">Total Diskon Diberikan</p>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalDiscount, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 shadow">
            <p class="text-sm text-gray-600 mb-1">Total Pendapatan Bersih</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
    </div>
</div> --}}

</div><!-- Close print-content -->

<style>
@media print {
    .no-print {
        display: none !important;
    }
    .print-content {
        margin: 0;
        padding: 20px;
    }
    body {
        background: white;
    }
}
</style>
@endsection
