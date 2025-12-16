# FEATURE TOGGLES - BILLUP

## 📋 Daftar Feature Toggles

Feature toggles memungkinkan Anda mengaktifkan/menonaktifkan fitur tertentu tanpa perlu mengubah code.

### 1. FEATURE_DISCOUNT
**Lokasi:** `.env`
```bash
FEATURE_DISCOUNT=true  # Default: true
```

**Fungsi:**
- Mengaktifkan/menonaktifkan field diskon pada proses checkout
- Jika `false`: Field diskon tidak akan muncul di halaman kasir

**Affected Files:**
- `resources/views/cashier/index.blade.php`
- `app/Http/Controllers/User/CashierController.php`

---

### 2. FEATURE_TRANSACTION_HISTORY
**Lokasi:** `.env`
```bash
FEATURE_TRANSACTION_HISTORY=true  # Default: true
```

**Fungsi:**
- Mengaktifkan/menonaktifkan fitur riwayat transaksi
- Jika `false`: Menu "Riwayat" tidak akan muncul di sidebar kasir

**Affected Files:**
- `resources/views/layouts/cashier.blade.php`
- `routes/web.php`

---

### 3. FEATURE_CASHLESS_PAYMENT ⭐
**Lokasi:** `.env`
```bash
FEATURE_CASHLESS_PAYMENT=false  # Default: false (sesuai modul demonstrasi)
```

**Fungsi:**
- Mengaktifkan/menonaktifkan metode pembayaran Non-Tunai/Cashless
- Jika `false`:
  - Hanya metode pembayaran TUNAI yang tersedia
  - Field "Uang Diterima" wajib diisi
  - Kembalian otomatis dihitung
  - Badge "💵 Pembayaran Tunai" ditampilkan
- Jika `true`:
  - Pilihan metode pembayaran: TUNAI atau NON-TUNAI
  - Field "Uang Diterima" hanya muncul untuk TUNAI
  - Kembalian hanya dihitung untuk TUNAI

**Affected Files:**
- `config/features.php`
- `resources/views/cashier/index.blade.php`
- `resources/views/cashier/invoice.blade.php`
- `app/Http/Controllers/User/CashierController.php`

---

## 🔧 Cara Menggunakan

### 1. Setup di File .env

Copy `.env.example` ke `.env` jika belum ada:
```bash
cp .env.example .env
```

Tambahkan di bagian bawah file `.env`:
```bash
# BillUp Feature Toggles
FEATURE_DISCOUNT=true
FEATURE_TRANSACTION_HISTORY=true
FEATURE_CASHLESS_PAYMENT=false
```

### 2. Clear Config Cache (Wajib setelah perubahan)

Setiap kali mengubah nilai di `.env`, jalankan:
```bash
php artisan config:clear
php artisan cache:clear
```

Atau untuk development, bisa disable config cache:
```bash
php artisan config:clear
```

---

## 📖 Contoh Penggunaan

### Scenario 1: Demonstrasi Modul (Default)
**Requirement:** Fokus pada pembayaran tunai dengan kembalian

**Setting:**
```bash
FEATURE_DISCOUNT=true
FEATURE_TRANSACTION_HISTORY=true
FEATURE_CASHLESS_PAYMENT=false  ← Setting ini!
```

**Hasil:**
- ✅ Halaman POS hanya menampilkan pembayaran TUNAI
- ✅ Field "Uang Diterima" wajib diisi
- ✅ Kembalian otomatis dihitung real-time
- ✅ Struk menampilkan: Total, Uang Diterima, Kembalian
- ❌ Tidak ada pilihan pembayaran Non-Tunai

---

### Scenario 2: Production Mode (Fleksibel)
**Requirement:** Support berbagai metode pembayaran

**Setting:**
```bash
FEATURE_DISCOUNT=true
FEATURE_TRANSACTION_HISTORY=true
FEATURE_CASHLESS_PAYMENT=true  ← Enable cashless
```

**Hasil:**
- ✅ Halaman POS menampilkan 2 pilihan: TUNAI dan NON-TUNAI
- ✅ Untuk TUNAI: Field "Uang Diterima" muncul, kembalian dihitung
- ✅ Untuk NON-TUNAI: Field "Uang Diterima" hidden, kembalian = 0
- ✅ Struk menyesuaikan dengan metode pembayaran

---

### Scenario 3: Coffee Shop Sederhana
**Requirement:** Tidak ada diskon, hanya pembayaran tunai

**Setting:**
```bash
FEATURE_DISCOUNT=false  ← Disable diskon
FEATURE_TRANSACTION_HISTORY=true
FEATURE_CASHLESS_PAYMENT=false
```

**Hasil:**
- ✅ Proses checkout lebih simple tanpa field diskon
- ✅ Hanya pembayaran tunai
- ✅ Fokus pada transaksi cepat

---

## 🧪 Testing Feature Toggles

### Test 1: Disable Cashless Payment
```bash
# Set di .env
FEATURE_CASHLESS_PAYMENT=false

# Clear cache
php artisan config:clear

# Test di browser
1. Login sebagai kasir
2. Tambah produk ke cart
3. Lihat: Hanya ada badge "💵 Pembayaran Tunai"
4. Field "Uang Diterima" wajib diisi
5. Kembalian muncul otomatis
```

### Test 2: Enable Cashless Payment
```bash
# Set di .env
FEATURE_CASHLESS_PAYMENT=true

# Clear cache
php artisan config:clear

# Test di browser
1. Login sebagai kasir
2. Tambah produk ke cart
3. Lihat: Ada 2 pilihan "TUNAI" dan "NON-TUNAI"
4. Pilih TUNAI: Field "Uang Diterima" muncul
5. Pilih NON-TUNAI: Field "Uang Diterima" hilang
```

### Test 3: Disable Discount
```bash
# Set di .env
FEATURE_DISCOUNT=false

# Clear cache
php artisan config:clear

# Test di browser
1. Login sebagai kasir
2. Tambah produk ke cart
3. Lihat: Tidak ada field "Diskon"
4. Checkout langsung dengan total normal
```

---

## 🎯 Untuk Demonstrasi Modul

**Recommended Settings:**
```bash
FEATURE_DISCOUNT=true                  # Tetap ada untuk fleksibilitas
FEATURE_TRANSACTION_HISTORY=true      # Penting untuk menunjukkan data tersimpan
FEATURE_CASHLESS_PAYMENT=false        # FOKUS PADA TUNAI sesuai modul!
```

**Alasan:**
1. ✅ **Sesuai requirement modul:** "Masukkan nominal pembayaran dan tampilkan kembalian"
2. ✅ **Demonstrasi lebih fokus** pada perhitungan kembalian
3. ✅ **Mudah dijelaskan** tanpa bingung dengan metode pembayaran lain
4. ✅ **Cocok untuk coffee shop tradisional** yang mayoritas cash transaction

---

## 🔄 Cara Mengembalikan ke Default

Jika ingin reset ke setting awal:

```bash
# Hapus atau comment feature toggles di .env
# FEATURE_DISCOUNT=true
# FEATURE_TRANSACTION_HISTORY=true
# FEATURE_CASHLESS_PAYMENT=false

# Clear cache
php artisan config:clear
php artisan cache:clear
```

Sistem akan menggunakan default value dari `config/features.php`:
- `FEATURE_DISCOUNT` → `true`
- `FEATURE_TRANSACTION_HISTORY` → `true`
- `FEATURE_CASHLESS_PAYMENT` → `false`

---

## ⚠️ Troubleshooting

### Problem: Perubahan tidak terlihat setelah edit .env
**Solution:**
```bash
php artisan config:clear
php artisan cache:clear
# Reload browser (Ctrl + F5)
```

### Problem: Error "config not found"
**Solution:**
```bash
# Pastikan file config/features.php ada
# Re-cache config
php artisan config:cache
```

### Problem: Field Uang Diterima tidak muncul
**Solution:**
```bash
# Check .env
FEATURE_CASHLESS_PAYMENT=false  # Pastikan false

# Clear cache
php artisan config:clear

# Clear browser cache
Ctrl + Shift + R
```

---

## 📚 Advanced: Menambah Feature Toggle Baru

Jika ingin menambah feature toggle sendiri:

### 1. Tambah di config/features.php
```php
return [
    'discount' => env('FEATURE_DISCOUNT', true),
    'transaction_history' => env('FEATURE_TRANSACTION_HISTORY', true),
    'cashless_payment' => env('FEATURE_CASHLESS_PAYMENT', false),
    'your_new_feature' => env('FEATURE_YOUR_NEW_FEATURE', false), // ← Add here
];
```

### 2. Tambah di .env.example
```bash
FEATURE_YOUR_NEW_FEATURE=false
```

### 3. Gunakan di Blade
```blade
@if(config('features.your_new_feature'))
    <!-- Your feature content here -->
@endif
```

### 4. Gunakan di Controller
```php
if (config('features.your_new_feature')) {
    // Your feature logic
}
```

---

## 📝 Notes

- ✅ Feature toggles adalah best practice untuk production apps
- ✅ Memudahkan A/B testing dan gradual rollout
- ✅ Tidak perlu deploy ulang untuk enable/disable fitur
- ✅ Cocok untuk multi-tenant atau customizable apps
- ⚠️ Jangan lupa clear cache setelah perubahan di .env!

---

**Updated:** December 16, 2025  
**Version:** 1.0  
**Project:** BillUp - Point of Sale System
