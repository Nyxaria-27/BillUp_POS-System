<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaction->invoice_number }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-2xl mx-auto my-8 bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-blue-600 text-white px-8 py-6">
            <h1 class="text-3xl font-bold">BillUp</h1>
            <p class="text-sm">Sistem Kasir Online</p>
        </div>

        <!-- Invoice Info -->
        <div class="px-8 py-6 border-b">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">INVOICE</h2>
                    <p class="text-sm text-gray-600">{{ $transaction->invoice_number }}</p>
                    <p class="text-sm text-gray-600">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Kasir:</p>
                    <p class="font-semibold text-gray-800">{{ $transaction->user->name }}</p>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="px-8 py-6">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-300">
                        <th class="text-left py-2 text-sm font-semibold text-gray-600">ITEM</th>
                        <th class="text-right py-2 text-sm font-semibold text-gray-600">HARGA</th>
                        <th class="text-center py-2 text-sm font-semibold text-gray-600">QTY</th>
                        <th class="text-right py-2 text-sm font-semibold text-gray-600">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->items as $item)
                    <tr class="border-b border-gray-200">
                        <td class="py-3 text-sm text-gray-800">{{ $item->product_name }}</td>
                        <td class="py-3 text-sm text-right text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="py-3 text-sm text-center text-gray-600">{{ $item->quantity }}</td>
                        <td class="py-3 text-sm text-right font-semibold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="px-8 py-6 bg-gray-50 border-t-2">
            <div class="max-w-xs ml-auto space-y-2">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($transaction->discount > 0)
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Diskon:</span>
                    <span class="text-red-600">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between text-xl font-bold text-gray-800 pt-2 border-t-2">
                    <span>TOTAL:</span>
                    <span class="text-blue-600">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                </div>
                
                @php
                    $paymentDetails = session('payment_details', []);
                @endphp
                
                @if(!empty($paymentDetails))
                <div class="pt-3 border-t space-y-2">
                    <div class="flex justify-between text-base font-semibold text-gray-700">
                        <span>💵 Uang Diterima:</span>
                        <span>Rp {{ number_format($paymentDetails['received_amount'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-green-600">
                        <span>💰 Kembalian:</span>
                        <span>Rp {{ number_format($paymentDetails['change'], 0, ',', '.') }}</span>
                    </div>
                </div>
                @endif
                
                <div class="flex justify-between text-sm text-gray-600 pt-2 border-t">
                    <span>Metode Pembayaran:</span>
                    <span class="font-semibold uppercase">
                        @if($transaction->payment_method === 'cash')
                            💵 TUNAI
                        @else
                            💳 NON-TUNAI
                        @endif
                    </span>
                </div>
            </div>
        </div>

        @if($transaction->notes)
        <div class="px-8 py-4 bg-yellow-50 border-t">
            <p class="text-sm text-gray-600"><strong>Catatan:</strong> {{ $transaction->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="px-8 py-6 bg-gray-100 text-center border-t">
            <p class="text-sm text-gray-600">Terima kasih atas pembelian Anda!</p>
            <p class="text-xs text-gray-500 mt-2">BillUp - Sistem Kasir Online Modern</p>
        </div>

        <!-- Action Buttons -->
        <div class="px-8 py-6 no-print flex space-x-3">
            <button onclick="window.print()" class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                🖨️ Cetak Struk
            </button>
            <a href="{{ route('cashier.index') }}" class="flex-1 px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition text-center">
                Transaksi Baru
            </a>
        </div>
    </div>
</body>
</html>
