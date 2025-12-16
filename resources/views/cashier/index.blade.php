@extends('layouts.cashier')

@section('content')
<!-- Catalog Section -->
<div class="w-full lg:w-3/5 bg-white p-4 lg:p-6 overflow-y-auto scrollbar-hide order-2 lg:order-1">
    <!-- Search & Filter -->
    <div class="mb-4 lg:mb-6">
        <form method="GET" action="{{ route('cashier.index') }}" class="space-y-3 lg:space-y-4">
            <!-- Search Bar -->
            <div class="relative">
                <input type="text" name="search" value="{{ $search }}" 
                    placeholder="Cari produk..." 
                    class="w-full px-4 lg:px-6 py-3 lg:py-4 text-base lg:text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="submit" class="absolute right-3 lg:right-4 top-1/2 transform -translate-y-1/2">
                    <svg class="w-5 h-5 lg:w-6 lg:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>

            <!-- Category Filter -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('cashier.index') }}" 
                    class="px-3 lg:px-4 py-2 rounded-lg font-semibold text-xs lg:text-sm transition {{ !$selectedCategory ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Semua
                </a>
                @foreach($categories as $category)
                <a href="{{ route('cashier.index', ['category' => $category->id]) }}" 
                    class="px-3 lg:px-4 py-2 rounded-lg font-semibold text-xs lg:text-sm transition {{ $selectedCategory == $category->id ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $category->icon }} {{ $category->name }}
                </a>
                @endforeach
            </div>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 lg:gap-4">
        @forelse($products as $product)
        <div class="bg-white border-2 border-gray-200 rounded-lg p-3 lg:p-4 hover:border-blue-500 hover:shadow-md cursor-pointer transition" 
             onclick="addToCart({{ $product->id }})">
            <div class="text-center">
                @if($product->image && file_exists(public_path('storage/' . $product->image)))
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-20 lg:h-24 object-cover rounded-lg mb-2">
                @else
                <div class="w-full h-20 lg:h-24 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center mb-2">
                    <span class="text-4xl lg:text-5xl">{{ $product->category->icon }}</span>
                </div>
                @endif
                <h3 class="font-semibold text-gray-800 text-xs lg:text-sm mb-1 line-clamp-2">{{ $product->name }}</h3>
                <p class="text-base lg:text-lg font-bold text-blue-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-xs mt-1 text-gray-500">Stok: {{ $product->stock }}</p>
            </div>
        </div>
        @empty
        <div class="col-span-2 md:col-span-3 xl:col-span-4 text-center py-8 lg:py-12 text-gray-500">
            Produk tidak ditemukan
        </div>
        @endforelse
    </div>
</div>

<!-- Cart & Checkout Section -->
<div class="w-full lg:w-2/5 bg-gray-50 flex flex-col order-1 lg:order-2 max-h-[70vh] lg:max-h-full">
    <!-- Cart Header -->
    <div class="bg-blue-600 text-white px-4 lg:px-6 py-3 lg:py-4">
        <h2 class="text-lg lg:text-xl font-bold">Keranjang Belanja</h2>
    </div>  

    <!-- Cart Items -->
    <div class="flex-1 overflow-y-auto scrollbar-hide px-4 lg:px-6 py-3 lg:py-4 space-y-2 lg:space-y-3" id="cart-items">
        @forelse($cart as $id => $item)
        <div class="bg-white rounded-lg p-3 lg:p-4 shadow" data-product-id="{{ $id }}">
            <div class="flex justify-between items-start mb-2">
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800 text-sm lg:text-base">{{ $item['name'] }}</h3>
                    <p class="text-xs lg:text-sm text-gray-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                </div>
                <button onclick="removeFromCart({{ $id }})" class="text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex flex-col flex-1">
                    <div class="flex items-center space-x-2">
                        <button onclick="updateCart({{ $id }}, 'decrease')" 
                            class="w-7 h-7 lg:w-8 lg:h-8 bg-gray-200 rounded hover:bg-gray-300 font-bold text-sm flex items-center justify-center">−</button>
                        <input type="number" 
                            id="qty-{{ $id }}"
                            value="{{ $item['quantity'] }}" 
                            min="1" 
                            max="{{ $item['stock'] }}"
                            onchange="updateCartQuantity({{ $id }}, this.value)"
                            title="Max: {{ $item['stock'] }}"
                            class="w-14 lg:w-16 text-center font-semibold text-sm lg:text-base border border-gray-300 rounded px-1 py-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent quantity-{{ $id }}">
                        <button onclick="updateCart({{ $id }}, 'increase')" 
                            class="w-7 h-7 lg:w-8 lg:h-8 bg-blue-600 text-white rounded hover:bg-blue-700 font-bold text-sm flex items-center justify-center">+</button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-9">Max: {{ $item['stock'] }}</p>
                </div>
                <div class="font-bold text-gray-800 text-sm lg:text-base subtotal-{{ $id }} ml-2">
                    Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-8 lg:py-12 text-gray-500" id="empty-cart">
            Keranjang masih kosong
        </div>
        @endforelse
    </div>

    <!-- Summary & Checkout -->
    <div class="bg-white border-t-2 border-gray-200 px-4 lg:px-6 py-3 lg:py-4">
        <form action="{{ route('cashier.checkout') }}" method="POST" id="checkout-form">
            @csrf
            
            <!-- Subtotal -->
            <div class="flex justify-between text-xs lg:text-sm text-gray-600 mb-2">
                <span>Subtotal:</span>
                <span id="subtotal">Rp {{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 0, ',', '.') }}</span>
            </div>

            <!-- Discount (if enabled) -->
            @if(config('features.discount'))
            <div class="mb-3">
                <label class="text-xs lg:text-sm font-semibold text-gray-700 mb-1 block">Diskon (Rp)</label>
                <input type="number" name="discount" id="discount" min="0" value="0" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs lg:text-sm" 
                    onchange="calculateTotal()">
            </div>
            @endif

            <!-- Total -->
            <div class="flex justify-between items-center mb-3 lg:mb-4 pb-3 lg:pb-4 border-b-2">
                <span class="text-base lg:text-lg font-bold text-gray-800">TOTAL:</span>
                <span class="text-xl lg:text-3xl font-bold text-blue-600" id="total">
                    Rp {{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)), 0, ',', '.') }}
                </span>
            </div>

            @if(config('features.cashless_payment'))
            <!-- Payment Method -->
            <div class="mb-3 lg:mb-4">
                <label class="text-xs lg:text-sm font-semibold text-gray-700 mb-2 block">Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-2 lg:gap-3">
                    <label class="relative">
                        <input type="radio" name="payment_method" value="cash" class="peer sr-only" required checked onchange="togglePaymentFields()">
                        <div class="p-3 lg:p-4 border-2 border-gray-300 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 text-center transition">
                            <svg class="w-6 h-6 lg:w-8 lg:h-8 mx-auto mb-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-semibold text-xs lg:text-sm">TUNAI</span>
                        </div>
                    </label>
                    <label class="relative">
                        <input type="radio" name="payment_method" value="cashless" class="peer sr-only" required onchange="togglePaymentFields()">
                        <div class="p-3 lg:p-4 border-2 border-gray-300 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 text-center transition">
                            <svg class="w-6 h-6 lg:w-8 lg:h-8 mx-auto mb-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <span class="font-semibold text-xs lg:text-sm">NON-TUNAI</span>
                        </div>
                    </label>
                </div>
            </div>
            @else
            <!-- Payment Method (Hidden - Default Cash Only) -->
            <input type="hidden" name="payment_method" value="cash">
            <div class="mb-3 lg:mb-4 p-2 lg:p-3 bg-green-50 border border-green-200 rounded-lg text-center">
                <span class="text-xs lg:text-sm font-semibold text-green-700">💵 Pembayaran Tunai</span>
            </div>
            @endif

            <!-- Uang Diterima (show always, but required only for cash) -->
            <div class="mb-3" id="received-amount-field">
                <label class="text-xs lg:text-sm font-semibold text-gray-700 mb-2 block">💵 Uang Diterima (Rp)</label>
                <input type="number" name="received_amount" id="received_amount" min="0" value="0" 
                    class="w-full px-3 lg:px-4 py-2 lg:py-3 text-base lg:text-lg border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                    placeholder="Masukkan nominal uang..."
                    onchange="calculateChange()"
                    oninput="calculateChange()"
                    {{ config('features.cashless_payment') ? '' : 'required' }}>
            </div>

            <!-- Kembalian -->
            <div class="mb-3 lg:mb-4 p-3 lg:p-4 bg-gradient-to-r from-green-50 to-green-100 border-2 border-green-300 rounded-lg" id="change-field">
                <div class="flex justify-between items-center">
                    <span class="text-xs lg:text-sm font-semibold text-gray-700">💰 Kembalian:</span>
                    <span class="text-lg lg:text-2xl font-bold text-green-600" id="change">Rp 0</span>
                </div>
            </div>

            <!-- Checkout Button -->
            <button type="submit" 
                class="w-full py-3 lg:py-4 bg-green-600 text-white text-base lg:text-lg font-bold rounded-lg hover:bg-green-700 transition disabled:bg-gray-300 disabled:cursor-not-allowed"
                {{ empty($cart) ? 'disabled' : '' }}>
                💰 PROSES PEMBAYARAN
            </button>
        </form>
    </div>
</div>

<script>
let totalAmount = {{ array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)) }};
const hasCashlessFeature = {{ config('features.cashless_payment') ? 'true' : 'false' }};

function togglePaymentFields() {
    if (!hasCashlessFeature) return; // Skip jika cashless tidak aktif
    
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    const receivedAmountField = document.getElementById('received-amount-field');
    const changeField = document.getElementById('change-field');
    const receivedAmountInput = document.getElementById('received_amount');
    
    if (paymentMethod === 'cash') {
        receivedAmountField.style.display = 'block';
        changeField.style.display = 'block';
        receivedAmountInput.required = true;
    } else {
        receivedAmountField.style.display = 'none';
        changeField.style.display = 'none';
        receivedAmountInput.required = false;
        receivedAmountInput.value = totalAmount; // Set otomatis untuk cashless
    }
}

function calculateTotal() {
    const cartSubtotal = {{ array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)) }};
    const discount = parseFloat(document.getElementById('discount')?.value || 0);
    totalAmount = cartSubtotal - discount;
    
    document.getElementById('total').textContent = 'Rp ' + totalAmount.toLocaleString('id-ID');
    calculateChange();
}

function calculateChange() {
    const receivedAmount = parseFloat(document.getElementById('received_amount').value || 0);
    const change = receivedAmount - totalAmount;
    
    const changeElement = document.getElementById('change');
    if (change >= 0) {
        changeElement.textContent = 'Rp ' + change.toLocaleString('id-ID');
        changeElement.classList.remove('text-red-600');
        changeElement.classList.add('text-green-600');
    } else {
        changeElement.textContent = 'Rp ' + Math.abs(change).toLocaleString('id-ID') + ' (Kurang)';
        changeElement.classList.remove('text-green-600');
        changeElement.classList.add('text-red-600');
    }
}

// Initialize change calculation
document.addEventListener('DOMContentLoaded', function() {
    calculateChange();
});

function addToCart(productId) {
    fetch('{{ route("cashier.add-to-cart") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.error || 'Terjadi kesalahan',
                confirmButtonColor: '#3B82F6'
            });
        }
    });
}

function updateCart(productId, action) {
    fetch('{{ route("cashier.update-cart") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ product_id: productId, action: action })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.error || 'Terjadi kesalahan',
                confirmButtonColor: '#3B82F6'
            });
        }
    });
}

function updateCartQuantity(productId, quantity) {
    quantity = parseInt(quantity);
    
    // Validasi input
    if (isNaN(quantity) || quantity < 1) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: 'Quantity minimal 1',
            confirmButtonColor: '#3B82F6'
        }).then(() => {
            location.reload();
        });
        return;
    }
    
    fetch('{{ route("cashier.update-cart") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ product_id: productId, action: 'set', quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.error || 'Terjadi kesalahan',
                confirmButtonColor: '#3B82F6'
            }).then(() => {
                location.reload();
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan',
            confirmButtonColor: '#3B82F6'
        }).then(() => {
            location.reload();
        });
    });
}

function removeFromCart(productId) {
    Swal.fire({
        title: 'Hapus Item?',
        text: 'Item akan dihapus dari keranjang',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("cashier.remove-from-cart") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    });
}

function calculateTotal() {
    const subtotalText = document.getElementById('subtotal').textContent.replace(/[^0-9]/g, '');
    const subtotal = parseInt(subtotalText) || 0;
    const discount = parseInt(document.getElementById('discount')?.value) || 0;
    const total = subtotal - discount;
    
    document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
}
</script>
@endsection
