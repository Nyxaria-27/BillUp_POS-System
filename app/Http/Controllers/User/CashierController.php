<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $selectedCategory = $request->get('category');
        $search = $request->get('search');

        $products = Product::with('category')
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                return $query->where('category_id', $selectedCategory);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->where('stock', '>', 0)
            ->get();

        $cart = session()->get('cart', []);

        return view('cashier.index', compact('categories', 'products', 'cart', 'selectedCategory', 'search'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        
        if ($product->stock < 1) {
            return response()->json(['error' => 'Stok habis'], 400);
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            if ($cart[$product->id]['quantity'] >= $product->stock) {
                return response()->json(['error' => 'Stok tidak mencukupi'], 400);
            }
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'stock' => $product->stock
            ];
        }

        session()->put('cart', $cart);
        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->product_id;
        $action = $request->action; // 'increase', 'decrease', or 'set'

        if (isset($cart[$productId])) {
            $product = Product::find($productId);
            
            if ($action === 'increase') {
                if ($cart[$productId]['quantity'] >= $product->stock) {
                    return response()->json(['error' => 'Stok tidak mencukupi'], 400);
                }
                $cart[$productId]['quantity']++;
            } elseif ($action === 'decrease') {
                $cart[$productId]['quantity']--;
                if ($cart[$productId]['quantity'] <= 0) {
                    unset($cart[$productId]);
                }
            } elseif ($action === 'set') {
                $quantity = (int) $request->quantity;
                
                // Validasi quantity
                if ($quantity < 1) {
                    return response()->json(['error' => 'Quantity minimal 1'], 400);
                }
                
                if ($quantity > $product->stock) {
                    return response()->json(['error' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock], 400);
                }
                
                $cart[$productId]['quantity'] = $quantity;
            }

            session()->put('cart', $cart);
        }

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function checkout(Request $request)
    {
        // Validasi dinamis berdasarkan fitur cashless
        $rules = [
            'payment_method' => 'required|in:cash,cashless',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ];

        // Jika cashless tidak aktif, paksa payment method ke cash
        if (!config('features.cashless_payment')) {
            $request->merge(['payment_method' => 'cash']);
        }

        // Received amount wajib untuk cash, opsional untuk cashless
        if ($request->payment_method === 'cash') {
            $rules['received_amount'] = 'required|numeric|min:0';
        } else {
            $rules['received_amount'] = 'nullable|numeric|min:0';
        }

        $validated = $request->validate($rules);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $discount = config('features.discount') && $request->discount ? $request->discount : 0;
            $total = $subtotal - $discount;

            // Validasi uang yang diterima untuk pembayaran tunai
            if ($validated['payment_method'] === 'cash') {
                $receivedAmount = $validated['received_amount'] ?? 0;
                if ($receivedAmount < $total) {
                    return redirect()->back()->with('error', 'Uang yang diterima kurang! Total: Rp ' . number_format($total, 0, ',', '.'));
                }
                
                // Simpan informasi pembayaran untuk ditampilkan di invoice
                session()->put('payment_details', [
                    'received_amount' => $receivedAmount,
                    'change' => $receivedAmount - $total
                ]);
            } else {
                // Untuk cashless, set received amount = total (uang pas)
                session()->put('payment_details', [
                    'received_amount' => $total,
                    'change' => 0
                ]);
            }

            // Generate invoice number
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(Transaction::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $productId,
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Update stock
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('cashier.invoice', $transaction->id)
                ->with('success', 'Transaksi berhasil!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function invoice(Transaction $transaction)
    {
        $transaction->load('items', 'user');
        return view('cashier.invoice', compact('transaction'));
    }

    public function transactions(Request $request)
    {
        // Kasir hanya bisa melihat transaksi mereka sendiri
        $transactions = Transaction::with('user')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('cashier.transactions', compact('transactions'));
    }

    public function showTransaction(Transaction $transaction)
    {
        // Pastikan kasir hanya bisa melihat transaksi mereka sendiri
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $transaction->load('items', 'user');
        return view('cashier.transaction-detail', compact('transaction'));
    }
}
