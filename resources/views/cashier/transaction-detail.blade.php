@extends('layouts.cashier')

@section('content')
<div class="max-w-4xl mt-28 mx-auto">
    <!-- Header Actions -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('cashier.transactions') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-all duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        <button onclick="window.print()" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:shadow-lg transition-all duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak Struk
        </button>
    </div>

    <!-- Invoice/Struk -->
    <div class="bg-white rounded-lg shadow-xl overflow-hidden" id="invoice-content">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">BillUp</h1>
                    <p class="text-blue-100 text-sm mt-1">Sistem Kasir Modern</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-blue-100">Invoice</p>
                    <p class="text-xl font-bold">{{ $transaction->invoice_number }}</p>
                </div>
            </div>
        </div>

        <!-- Transaction Info -->
        <div class="px-8 py-6 border-b border-gray-200">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Kasir</p>
                    <p class="font-semibold text-gray-900">{{ $transaction->user->name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600 mb-1">Tanggal & Waktu</p>
                    <p class="font-semibold text-gray-900">{{ $transaction->created_at->format('d F Y, H:i') }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-600 mb-1">Metode Pembayaran</p>
                <span class="inline-flex px-4 py-1.5 text-sm font-semibold rounded-full {{ $transaction->payment_method === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ $transaction->payment_method === 'cash' ? 'CASH' : 'CASHLESS' }}
                </span>
            </div>
        </div>

        <!-- Items Table -->
        <div class="px-8 py-6">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 text-sm font-semibold text-gray-600">Produk</th>
                        <th class="text-center py-3 text-sm font-semibold text-gray-600">Qty</th>
                        <th class="text-right py-3 text-sm font-semibold text-gray-600">Harga</th>
                        <th class="text-right py-3 text-sm font-semibold text-gray-600">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transaction->items as $item)
                    <tr>
                        <td class="py-4 text-gray-900">{{ $item->product_name }}</td>
                        <td class="py-4 text-center text-gray-600">{{ $item->quantity }}</td>
                        <td class="py-4 text-right text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="py-4 text-right font-semibold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
            <div class="max-w-sm ml-auto space-y-3">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal:</span>
                    <span class="font-semibold">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                </div>
                
                @if($transaction->discount > 0)
                <div class="flex justify-between text-red-600">
                    <span>Diskon:</span>
                    <span class="font-semibold">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                </div>
                @endif

                <div class="flex justify-between text-xl font-bold text-gray-900 pt-3 border-t-2 border-gray-300">
                    <span>TOTAL:</span>
                    <span class="text-blue-600">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        @if($transaction->notes)
        <div class="px-8 py-4 bg-yellow-50 border-t border-yellow-200">
            <p class="text-sm text-gray-600 mb-1">Catatan:</p>
            <p class="text-gray-900">{{ $transaction->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="px-8 py-6 bg-gray-100 text-center border-t border-gray-200">
            <p class="text-sm text-gray-600">Terima kasih atas kunjungan Anda!</p>
            <p class="text-xs text-gray-500 mt-2">Powered by BillUp - Sistem Kasir Modern</p>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #invoice-content, #invoice-content * {
            visibility: visible;
        }
        #invoice-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
    }
</style>
@endsection
