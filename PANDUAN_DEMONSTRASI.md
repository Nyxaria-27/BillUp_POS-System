# PANDUAN DEMONSTRASI PROJECT BILLUP
## Persiapan untuk Presentasi Modul POS Coffee Shop

---

## 📋 PERSIAPAN SEBELUM DEMONSTRASI

### 1. Pastikan Aplikasi Berjalan
```bash
# Terminal 1 - Laravel Server
cd c:\laragon\www\BillUp
php artisan serve

# Terminal 2 - Vite (untuk assets)
npm run dev
```

### 2. Siapkan Data Dummy
**Login Credentials:**
- **Admin:** admin@billup.com / password
- **Kasir:** kasir@billup.com / password

**Data Produk Coffee Shop:**
Pastikan sudah ada produk dengan kategori:
- ☕ Espresso-based (Americano, Latte, Cappuccino)
- 🍵 Non-Coffee (Tea, Chocolate)
- 🍰 Food (Cake, Pastry, Sandwich)

---

## 🎯 ALUR DEMONSTRASI (180 Menit)

### BAGIAN 1: PENGENALAN (15 menit)

#### 1.1 Tampilkan Landing Page
- Buka: `http://localhost:8000`
- Jelaskan: "Ini adalah BillUp, sistem POS untuk Coffee Shop"
- Tunjukkan: Hero section, features, how it works

#### 1.2 Tampilkan Database
- Buka: phpMyAdmin (http://localhost/phpmyadmin)
- Tunjukkan tabel: users, categories, products, transactions, transaction_items
- Jelaskan: "Database menggunakan MySQL dengan relasi yang terstruktur"

**Screenshot yang diperlukan:**
- ✅ Landing page
- ✅ Struktur database
- ✅ Sample data di tabel products

---

### BAGIAN 2: DEMONSTRASI INPUT DATA MENU (30 menit)

#### 2.1 Login sebagai Admin
- Klik "Login" di navbar
- Email: `admin@billup.com`
- Password: `password`
- Jelaskan: "Sistem memiliki role-based access, admin punya akses penuh"

#### 2.2 Tambah Kategori Baru
- Navigate: Dashboard → Kategori → Tambah Kategori
- Input:
  - Nama: "Espresso"
  - Icon: ☕
- Klik "Simpan"
- Tunjukkan: Success message & data muncul di list

#### 2.3 Tambah Produk Kopi
- Navigate: Dashboard → Produk → Tambah Produk
- Input untuk **Americano**:
  - Nama: "Americano"
  - Kategori: "Espresso"
  - Harga: 25000
  - Stok: 100
  - Deskripsi: "Espresso dengan air panas"
  - Gambar: (Upload atau skip untuk gunakan icon)
- Klik "Simpan"

- Input untuk **Cappuccino**:
  - Nama: "Cappuccino"
  - Kategori: "Espresso"
  - Harga: 30000
  - Stok: 100
  - Deskripsi: "Espresso dengan steamed milk dan foam"

- Input untuk **Latte**:
  - Nama: "Caffe Latte"
  - Kategori: "Espresso"
  - Harga: 32000
  - Stok: 100
  - Deskripsi: "Espresso dengan steamed milk"

#### 2.4 Lihat Daftar Produk
- Navigate: Produk
- Tunjukkan: Semua produk yang baru ditambahkan
- Jelaskan: "Ini adalah fitur READ dari CRUD"

#### 2.5 Edit Produk
- Klik "Edit" pada salah satu produk
- Ubah harga atau stok
- Simpan
- Jelaskan: "Ini adalah fitur UPDATE dari CRUD"

**Screenshot yang diperlukan:**
- ✅ Form tambah kategori (sebelum & sesudah)
- ✅ Form tambah produk dengan semua field terisi
- ✅ Daftar produk setelah ditambahkan
- ✅ Form edit produk
- ✅ Success notification

**Source Code yang perlu di-screenshot:**
```php
// app/Http/Controllers/Admin/ProductController.php - Method store()
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('products', 'public');
    }

    Product::create($validated);

    return redirect()->route('admin.products.index')
        ->with('success', 'Produk berhasil ditambahkan!');
}
```

---

### BAGIAN 3: DEMONSTRASI TRANSAKSI PENJUALAN (45 menit)

#### 3.1 Logout dari Admin & Login sebagai Kasir
- Logout dari admin
- Login dengan: `kasir@billup.com` / `password`
- Jelaskan: "Kasir hanya bisa akses POS, tidak bisa edit produk"

#### 3.2 Tampilkan Halaman POS
- Otomatis redirect ke `/cashier`
- Jelaskan UI:
  - Kiri (60%): Catalog produk
  - Kanan (40%): Keranjang belanja
- Tunjukkan: Search bar dan filter kategori

#### 3.3 Proses Transaksi #1 - Pembayaran Cash

**Scenario:** Customer memesan 2 Americano & 1 Cappuccino

1. **Pilih Produk:**
   - Klik card "Americano" (akan masuk ke cart)
   - Update quantity jadi 2 dengan tombol +
   - Klik card "Cappuccino"
   - Tunjukkan: Cart otomatis update

2. **Tunjukkan Perhitungan:**
   - Item 1: Americano Rp 25.000 × 2 = Rp 50.000
   - Item 2: Cappuccino Rp 30.000 × 1 = Rp 30.000
   - **Total: Rp 80.000**
   - Jelaskan: "Perhitungan otomatis menggunakan algoritma: Subtotal = Harga × Qty"

3. **Input Diskon (Optional):**
   - Input diskon: 5000
   - Tunjukkan: Total berubah jadi Rp 75.000

3. **Pilih Metode Pembayaran:**
   - Metode: **💵 TUNAI (Default dan satu-satunya pilihan)**
   - Jelaskan: "Sesuai modul, sistem fokus pada pembayaran tunai dengan kembalian"

4. **Input Uang Diterima:**
   - Field otomatis muncul: "💵 Uang Diterima"
   - Input: 100000
   - **Tunjukkan: Kembalian dihitung real-time**
   - Jelaskan: "Ketika saya mengetik nominal, sistem langsung menghitung kembalian"
   - **Kembalian tampil: Rp 25.000** dengan warna hijau
   - Jelaskan algoritma: "Kembalian = Uang Diterima (100.000) - Total (75.000) = 25.000"

5. **Demonstrasikan Validasi:**
   - Coba input uang kurang (misalnya 50000)
   - Tunjukkan: Kembalian tampil merah dengan "(Kurang)"
   - Jelaskan: "Sistem memvalidasi bahwa uang harus cukup sebelum bisa checkout"
   - Input kembali 100000

6. **Proses Checkout:**
   - Klik "💰 PROSES PEMBAYARAN"
   - Tunjukkan: Invoice/Struk muncul
   - Jelaskan invoice number: INV-20251216-0001
   - **PENTING: Tunjukkan di struk:**
     - Total: Rp 75.000
     - 💵 Uang Diterima: Rp 100.000
     - 💰 Kembalian: Rp 25.000

#### 3.4 Proses Transaksi #2 - Uang Pas

**Scenario:** Customer memesan 1 Latte dengan uang pas

1. Klik card "Caffe Latte" (Total: Rp 32.000)
2. Input uang diterima: 32000 (pas)
3. Tunjukkan: Kembalian: Rp 0
4. Klik "💰 PROSES PEMBAYARAN"
5. Tunjukkan: Struk dengan kembalian Rp 0

**Screenshot yang diperlukan:**
- ✅ Halaman POS dengan grid produk
- ✅ Proses klik produk (before & after)
- ✅ Cart dengan multiple items
- ✅ Update quantity dengan tombol + / -
- ✅ Input diskon (jika enabled)
- ✅ **Field "Uang Diterima" dengan nilai terisi**
- ✅ **Tampilan kembalian real-time (warna hijau jika cukup)**
- ✅ **Tampilan kembalian merah "(Kurang)" saat validasi**
- ✅ **Struk dengan TOTAL, Uang Diterima, dan Kembalian**
- ✅ Tombol print pada struk

**Source Code yang perlu di-screenshot:**
```php
// app/Http/Controllers/User/CashierController.php - Method checkout()
public function checkout(Request $request)
{
    // Validasi
    $request->validate([
        'payment_method' => 'required|in:cash,cashless',
        'received_amount' => 'required_if:payment_method,cash|numeric|min:0',
        'discount' => 'nullable|numeric|min:0',
        'notes' => 'nullable|string'
    ]);

    $cart = session('cart', []);
    if (empty($cart)) {
        return back()->with('error', 'Keranjang kosong!');
    }

    // Hitung total
    $subtotal = collect($cart)->sum('subtotal');
    $discount = $request->discount ?? 0;
    $total = $subtotal - $discount;

    // Validasi pembayaran cash
    if ($request->payment_method === 'cash') {
        if ($request->received_amount < $total) {
            return back()->with('error', 'Uang tidak cukup!');
        }
    }

    // Validasi stok
    foreach ($cart as $productId => $item) {
        $product = Product::find($productId);
        if ($product->stock < $item['quantity']) {
            return back()->with('error', "Stok {$product->name} tidak cukup!");
        }
    }

    // Simpan transaksi
    DB::beginTransaction();
    try {
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'invoice_number' => $this->generateInvoiceNumber(),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes
        ]);

        // Simpan items & update stok
        foreach ($cart as $productId => $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $productId,
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal']
            ]);

            $product = Product::find($productId);
            $product->decrement('stock', $item['quantity']);
        }

        DB::commit();
        session()->forget('cart');

        return redirect()->route('cashier.invoice', $transaction);
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Checkout gagal!');
    }
}
```

---

### BAGIAN 4: TAMPILKAN DATA TRANSAKSI (20 menit)

#### 4.1 Riwayat Transaksi Kasir
- Navigate: Sidebar → Riwayat
- Tunjukkan: Daftar transaksi yang baru dibuat
- Klik "Lihat Struk" pada salah satu transaksi
- Jelaskan: "Struk bisa dilihat dan dicetak ulang kapan saja"

#### 4.2 Riwayat Transaksi Admin
- Logout kasir, login admin
- Navigate: Dashboard → Riwayat Transaksi
- Tunjukkan: Admin bisa lihat semua transaksi dari semua kasir
- Klik "Lihat Struk"

#### 4.3 Verifikasi di Database
- Buka phpMyAdmin
- Buka tabel `transactions`
- Tunjukkan: Data transaksi tersimpan
- Buka tabel `transaction_items`
- Tunjukkan: Detail item tersimpan
- Buka tabel `products`
- Tunjukkan: Stok berkurang sesuai quantity

**Screenshot yang diperlukan:**
- ✅ Riwayat transaksi kasir
- ✅ Riwayat transaksi admin
- ✅ Detail transaksi
- ✅ Data di tabel transactions (phpMyAdmin)
- ✅ Data di tabel transaction_items (phpMyAdmin)
- ✅ Stok produk berkurang (before & after)

---

### BAGIAN 5: FITUR TAMBAHAN (30 menit)

#### 5.1 Dashboard Admin
- Navigate: Dashboard
- Jelaskan setiap card statistik:
  - Total Pendapatan
  - Pendapatan Hari Ini
  - Pendapatan Bulan Ini
  - Total Produk
  - Pembayaran Tunai vs Non-Tunai
  - Produk Terlaris
  - Stok Menipis

#### 5.2 Laporan Keuangan
- Navigate: Laporan Keuangan
- Pilih periode: "Hari Ini"
- Tunjukkan:
  - Statistik pendapatan
  - Top produk terlaris
  - Performa kasir
  - Grafik pendapatan harian
- Klik "Cetak Laporan"
- Tunjukkan: Print preview

#### 5.3 Search & Filter
- Kembali ke halaman Kasir
- Demo search: ketik "latte"
- Demo filter: klik kategori "Espresso"

**Screenshot yang diperlukan:**
- ✅ Dashboard admin lengkap
- ✅ Laporan keuangan dengan filter
- ✅ Print preview laporan
- ✅ Search produk
- ✅ Filter kategori

---

### BAGIAN 6: PENJELASAN ALUR & CODE (40 menit)

#### 6.1 Struktur Project
```
BillUp/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── TransactionController.php
│   │   │   │   └── ReportController.php
│   │   │   └── User/
│   │   │       └── CashierController.php
│   │   └── Middleware/
│   │       └── CheckRole.php
│   └── Models/
│       ├── Product.php
│       ├── Category.php
│       ├── Transaction.php
│       ├── TransactionItem.php
│       └── User.php
├── database/
│   └── migrations/
│       ├── create_users_table.php
│       ├── create_categories_table.php
│       ├── create_products_table.php
│       ├── create_transactions_table.php
│       └── create_transaction_items_table.php
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── products/
│       │   ├── categories/
│       │   ├── transactions/
│       │   └── reports/
│       ├── cashier/
│       │   ├── index.blade.php
│       │   ├── invoice.blade.php
│       │   └── transactions.blade.php
│       └── layouts/
│           ├── admin.blade.php
│           └── cashier.blade.php
└── routes/
    └── web.php
```

#### 6.2 Jelaskan Alur Data

**1. Alur CRUD Produk:**
```
User Request → Route → Controller → Validation → Model → Database
                ↓                                              ↓
         Return View ← Controller ← Model ← Database Response
```

**2. Alur Transaksi:**
```
1. Kasir pilih produk → AJAX addToCart() → Session Cart
2. Update quantity → AJAX updateCart() → Session Cart
3. Checkout → Validation → BeginTransaction
4. Save Transaction → Generate Invoice → Save Items → Update Stock
5. Commit Transaction → Clear Cart → Show Invoice
```

**3. Algoritma Perhitungan:**
```javascript
// Subtotal per item
subtotal = price × quantity

// Total keseluruhan
total = Σ(subtotal) - discount

// Kembalian (untuk Cash)
change = received_amount - total
```

#### 6.3 Screenshot Source Code

**Tampilkan dan jelaskan:**

1. **Model Product.php:**
```php
protected $fillable = [
    'category_id', 'name', 'description', 
    'price', 'stock', 'image'
];

public function category() {
    return $this->belongsTo(Category::class);
}
```

2. **Migration create_products_table.php:**
```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained()->onDelete('cascade');
    $table->string('name');
    $table->text('description')->nullable();
    $table->decimal('price', 10, 2);
    $table->integer('stock');
    $table->string('image')->nullable();
    $table->timestamps();
});
```

3. **Route web.php:**
```php
// Admin Routes
Route::middleware(['auth', CheckRole::class.':admin'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::resource('products', ProductController::class);
    });

// Cashier Routes
Route::middleware(['auth', CheckRole::class.':user'])
    ->prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/', [CashierController::class, 'index']);
        Route::post('/checkout', [CashierController::class, 'checkout']);
    });
```

4. **View cashier/index.blade.php (Grid Produk):**
```blade
<div class="grid grid-cols-3 gap-4">
    @foreach($products as $product)
    <div onclick="addToCart({{ $product->id }})">
        <h3>{{ $product->name }}</h3>
        <p>Rp {{ number_format($product->price, 0, ',', '.') }}</p>
        <p>Stok: {{ $product->stock }}</p>
    </div>
    @endforeach
</div>
```

5. **JavaScript addToCart:**
```javascript
function addToCart(productId) {
    fetch('/cashier/add-to-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload untuk update cart
        }
    });
}
```

**Screenshot yang diperlukan:**
- ✅ Struktur folder project
- ✅ Model Product.php
- ✅ Controller ProductController.php (method store & update)
- ✅ Controller CashierController.php (method checkout)
- ✅ Migration create_products_table.php
- ✅ Route web.php
- ✅ View cashier/index.blade.php
- ✅ JavaScript function addToCart

---

## 🎤 POIN-POIN PENJELASAN VERBAL

### Saat Demonstrasi Input Data:
> "Sistem ini menggunakan konsep CRUD - Create, Read, Update, Delete. Ketika saya menambahkan produk Americano dengan harga Rp 25.000, data akan divalidasi terlebih dahulu, kemudian disimpan ke database MySQL melalui Model Product. Relasi dengan tabel categories memastikan setiap produk memiliki kategori yang valid."

### Saat Demonstrasi Transaksi:
> "Proses transaksi menggunakan session untuk menyimpan keranjang sementara. Ketika kasir mengklik produk, sistem akan menjalankan fungsi JavaScript addToCart() yang mengirim request AJAX ke server. Server akan menambahkan item ke session cart dan menghitung subtotal secara otomatis menggunakan algoritma: Subtotal = Harga × Quantity. Total keseluruhan dihitung dengan menjumlahkan semua subtotal dikurangi diskon."

### Saat Demonstrasi Pembayaran:
> "Untuk pembayaran tunai, kasir memasukkan nominal uang yang diterima dari customer di field 'Uang Diterima'. Sistem akan langsung menghitung kembalian secara real-time menggunakan algoritma: Kembalian = Uang Diterima - Total. Jika uang kurang, kembalian akan tampil merah dengan tanda '(Kurang)' dan sistem mencegah checkout. Setelah uang cukup dan checkout berhasil, informasi lengkap termasuk uang diterima dan kembalian akan tersimpan dan ditampilkan di struk untuk dokumentasi yang jelas bagi customer dan kasir."

### Saat Menjelaskan Database:
> "Database dirancang dengan normalisasi yang baik. Tabel products memiliki foreign key ke categories. Tabel transactions menyimpan header transaksi dengan user_id untuk tracking kasir mana yang melakukan transaksi. Tabel transaction_items menyimpan detail item dengan snapshot harga dan nama produk saat transaksi, sehingga jika harga produk berubah, data historis tetap akurat."

### Saat Menjelaskan Role-Based Access:
> "Sistem menggunakan middleware CheckRole untuk memisahkan akses. Admin memiliki akses penuh ke dashboard, CRUD produk, kategori, lihat semua transaksi, dan laporan. Kasir hanya bisa akses halaman POS untuk transaksi dan melihat riwayat transaksi mereka sendiri. Ini penting untuk security dan audit trail."

---

## ⏱️ TIME MANAGEMENT

| Waktu | Aktivitas | Durasi |
|-------|-----------|--------|
| 0-15 min | Pengenalan & tampilkan database | 15 menit |
| 15-45 min | Demonstrasi input data menu (CRUD) | 30 menit |
| 45-90 min | Demonstrasi transaksi penjualan | 45 menit |
| 90-110 min | Tampilkan data transaksi | 20 menit |
| 110-140 min | Fitur tambahan (Dashboard & Laporan) | 30 menit |
| 140-180 min | Penjelasan alur & source code | 40 menit |

---

## 📝 CHECKLIST AKHIR

### Sebelum Demonstrasi:
- [ ] Server Laravel running
- [ ] Vite running (npm run dev)
- [ ] Database terisi data dummy
- [ ] Browser bersih (clear cache & cookies)
- [ ] phpMyAdmin siap dibuka
- [ ] VS Code terbuka di project folder
- [ ] Koneksi internet stabil (untuk Google Fonts)

### Selama Demonstrasi:
- [ ] Bicara dengan jelas dan percaya diri
- [ ] Tunjukkan setiap step dengan detail
- [ ] Pause setelah action penting untuk screenshot
- [ ] Jelaskan konsep teknis dengan bahasa sederhana
- [ ] Tunjukkan error handling (coba input invalid)

### Setelah Demonstrasi:
- [ ] Semua screenshot terkumpul
- [ ] Source code ter-screenshot
- [ ] Database screenshot lengkap
- [ ] Siap compile ke Word document

---

## 💡 TIPS PRESENTASI

1. **Percaya Diri:** Project ini sudah sangat bagus, melebihi requirement modul
2. **Fokus pada Algoritma:** Jelaskan logika perhitungan dengan detail
3. **Tunjukkan Code:** Jangan ragu show source code, ini poin penting
4. **Interaktif:** Ajak penguji untuk menyebutkan produk yang ingin ditambahkan
5. **Highlight Fitur Plus:** Sebutkan fitur tambahan yang melebihi requirement
6. **Siap Troubleshoot:** Jika ada error, tenang saja, jelaskan error handling

---

**Good Luck! 🚀**
