@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('content')
<div class="mb-6 flex justify-between items-center print:hidden">
    <a href="{{ route('admin.transactions.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
        ← Kembali ke Daftar Transaksi
    </a>
    <button onclick="window.print()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-lg hover:shadow-lg transition-all duration-300">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
        </svg>
        Cetak Struk
    </button>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .print-area, .print-area * {
        visibility: visible;
    }
    .print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>

<div class="grid grid-cols-3 gap-6 print-area">
    <!-- Transaction Info -->
    <div class="col-span-2 space-y-6 print:col-span-3">
        <!-- Invoice Header -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-6 pb-4 border-b-2 border-gray-200">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent mb-2">BillUp</h1>
                    <p class="text-sm text-gray-600">Point of Sale System</p>
                </div>
                <div class="text-right">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $transaction->invoice_number }}</h2>
                    <p class="text-sm text-gray-600">{{ $transaction->created_at->format('d F Y, H:i:s') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                <div>
                    <p class="text-gray-600">Kasir:</p>
                    <p class="font-semibold text-gray-800">{{ $transaction->user->name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-600">Metode Pembayaran:</p>
                    <span class="inline-block px-4 py-1 text-sm font-semibold rounded-full {{ $transaction->payment_method === 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $transaction->payment_method === 'cash' ? 'Tunai' : 'Non-Tunai' }}
                    </span>
                </div>
                @if($transaction->notes)
                <div class="col-span-2">
                    <p class="text-gray-600">Catatan:</p>
                    <p class="font-semibold text-gray-800">{{ $transaction->notes }}</p>
                </div>
                @endif
            </div>
            <!-- Items -->
            <div class="border-t-2 border-gray-200 pt-4">
                <h3 class="font-semibold text-gray-800 mb-4">Item Pembelian</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Produk</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Harga</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Qty</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($transaction->items as $item)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $item->product_name }}</td>
                                <td class="px-6 py-4 text-sm text-right text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-center text-gray-600">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-sm text-right font-semibold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary -->
            <div class="border-t-2 border-gray-200 pt-4 mt-4">
                <div class="space-y-3">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($transaction->discount > 0)
                    <div class="flex justify-between text-sm text-red-600">
                        <span>Diskon:</span>
                        <span>- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    <div class="pt-3 border-t-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-800">Total:</span>
                            <span class="text-3xl font-bold text-blue-600">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t text-center">
                    <p class="text-xs text-gray-500">
                        Transaksi dibuat oleh <strong>{{ $transaction->user->name }}</strong><br>
                        {{ $transaction->created_at->format('d/m/Y') }} pukul {{ $transaction->created_at->format('H:i') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-2">Terima kasih atas kunjungan Anda!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Card (Screen Only) -->
    <div class="col-span-1 print:hidden">
        <div class="bg-white rounded-lg shadow p-6 sticky top-6">
            <h3 class="font-semibold text-gray-800 mb-4">Ringkasan Pembayaran</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                </div>
                
                @if($transaction->discount > 0)
                <div class="flex justify-between text-sm text-red-600">
                    <span>Diskon:</span>
                    <span>- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                
                <div class="pt-3 border-t-2">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-800">Total:</span>
                        <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="pt-3 border-t">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Metode Pembayaran:</span>
                        <span class="font-semibold text-gray-800 uppercase">{{ $transaction->payment_method }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t">
                <p class="text-xs text-gray-500">
                    Transaksi ini dibuat oleh <strong>{{ $transaction->user->name }}</strong> 
                    pada {{ $transaction->created_at->format('d/m/Y') }} pukul {{ $transaction->created_at->format('H:i') }}.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
