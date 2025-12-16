# 🧪 Testing Guide - BillUp

## 🔐 Login Credentials

### Admin Account
- **Email**: `admin@billup.com`
- **Password**: `password`
- **Access**: Dashboard Admin, CRUD Produk, CRUD Kategori, Riwayat Transaksi

### Kasir Account
- **Email**: `kasir@billup.com`
- **Password**: `password`
- **Access**: Dashboard Kasir, Proses Transaksi

## 📋 Testing Checklist

### ✅ Admin Features

#### 1. Login Admin
- [ ] Buka `http://localhost:8000/login`
- [ ] Login dengan `admin@billup.com` / `password`
- [ ] Verify redirect ke `/admin/dashboard`
- [ ] Cek dashboard menampilkan statistik

#### 2. Manage Kategori
- [ ] Klik menu "Kategori"
- [ ] Lihat list kategori (4 kategori: Kopi, Minuman Non-Kopi, Makanan, Snack)
- [ ] Klik "Tambah Kategori"
- [ ] Isi form:
  - Nama: `Dessert`
  - Icon: `🍰`
- [ ] Submit dan verify kategori muncul di list
- [ ] Klik "Edit" pada kategori
- [ ] Update nama dan simpan
- [ ] (Optional) Hapus kategori yang baru dibuat

#### 3. Manage Produk
- [ ] Klik menu "Produk"
- [ ] Lihat list produk (21 produk sample)
- [ ] Klik "Tambah Produk"
- [ ] Isi form:
  - Nama: `Tiramisu`
  - Kategori: `Dessert` (atau kategori lain)
  - Harga: `35000`
  - Stok: `20`
  - Deskripsi: `Italian coffee-flavored dessert`
- [ ] Submit dan verify produk muncul di list
- [ ] Cek badge stok (hijau > 10, kuning 1-10, merah = 0)
- [ ] Klik "Edit" pada produk
- [ ] Update harga dan stok
- [ ] Simpan dan verify perubahan
- [ ] (Optional) Hapus produk

#### 4. Riwayat Transaksi
- [ ] Klik menu "Riwayat Transaksi"
- [ ] Lihat list transaksi (kosong jika belum ada transaksi)
- [ ] Klik "Detail" pada transaksi
- [ ] Verify detail lengkap: items, subtotal, diskon, total, metode pembayaran

### ✅ Kasir Features

#### 1. Login Kasir
- [ ] Logout dari admin (jika masih login)
- [ ] Buka `http://localhost:8000/login`
- [ ] Login dengan `kasir@billup.com` / `password`
- [ ] Verify redirect ke `/cashier`
- [ ] Cek layout 3 kolom: sidebar, catalog, cart

#### 2. Search Produk
- [ ] Gunakan search bar di bagian atas catalog
- [ ] Ketik "Latte" dan tekan Enter
- [ ] Verify hanya produk Latte yang muncul
- [ ] Clear search untuk melihat semua produk

#### 3. Filter Kategori
- [ ] Klik tombol kategori "Kopi"
- [ ] Verify hanya produk kategori Kopi yang ditampilkan
- [ ] Klik "Semua" untuk reset filter

#### 4. Tambah ke Keranjang
- [ ] Klik kartu produk "Cappuccino"
- [ ] Verify produk masuk ke keranjang (bagian kanan)
- [ ] Cek quantity, price, dan subtotal
- [ ] Klik lagi pada "Cappuccino"
- [ ] Verify quantity bertambah

#### 5. Update Quantity
- [ ] Di keranjang, klik tombol "+" pada item
- [ ] Verify quantity bertambah dan subtotal update
- [ ] Klik tombol "-"
- [ ] Verify quantity berkurang

#### 6. Remove dari Keranjang
- [ ] Klik icon "X" (close) pada item di keranjang
- [ ] Confirm dialog
- [ ] Verify item hilang dari keranjang

#### 7. Checkout - Cash
- [ ] Tambah beberapa produk ke keranjang
- [ ] Pilih metode pembayaran "CASH"
- [ ] (Optional) Masukkan diskon: `5000`
- [ ] Klik "BAYAR SEKARANG"
- [ ] Verify redirect ke halaman invoice
- [ ] Cek detail invoice lengkap

#### 8. Checkout - Cashless
- [ ] Buat transaksi baru
- [ ] Tambah produk ke keranjang
- [ ] Pilih metode pembayaran "CASHLESS"
- [ ] Checkout
- [ ] Verify invoice

#### 9. Cetak Struk
- [ ] Pada halaman invoice, klik "🖨️ Cetak Struk"
- [ ] Verify print dialog muncul
- [ ] Preview struk (clean design, no navigation)
- [ ] (Optional) Save as PDF

#### 10. Verify Stok Update
- [ ] Login sebagai admin
- [ ] Klik menu "Produk"
- [ ] Cek stok produk yang dibeli kasir
- [ ] Verify stok berkurang sesuai quantity yang dibeli

### ✅ Additional Tests

#### 1. Role-based Access
- [ ] Login sebagai kasir
- [ ] Try akses `/admin/dashboard` langsung
- [ ] Verify error 403 Forbidden
- [ ] Login sebagai admin
- [ ] Try akses `/cashier` langsung
- [ ] Verify error 403 Forbidden

#### 2. Registration
- [ ] Logout
- [ ] Klik "Register"
- [ ] Isi form registration:
  - Name: `Test User`
  - Email: `testuser@example.com`
  - Password: `password`
  - Confirm Password: `password`
- [ ] Submit
- [ ] Verify redirect ke cashier (default role: user)

#### 3. Feature Toggle - Discount
- [ ] Edit `.env` atau `config/features.php`
- [ ] Set `FEATURE_DISCOUNT=false`
- [ ] Clear cache: `php artisan config:clear`
- [ ] Login sebagai kasir
- [ ] Verify input diskon tidak muncul di checkout

#### 4. Responsive Design
- [ ] Buka aplikasi di browser
- [ ] Resize window ke tablet size
- [ ] Verify layout masih rapi
- [ ] Resize ke mobile size
- [ ] Cek apakah tetap usable

#### 5. Data Validation
- [ ] Try tambah produk tanpa nama (harus error)
- [ ] Try tambah produk dengan harga negatif (harus error)
- [ ] Try checkout dengan keranjang kosong (tombol disabled)
- [ ] Try tambah produk ke cart melebihi stok (harus error)

## 🎯 Expected Results

### Sukses Jika:
- ✅ Admin bisa CRUD produk & kategori
- ✅ Kasir bisa search, filter, dan checkout
- ✅ Stok otomatis berkurang saat transaksi
- ✅ Invoice bisa di-print
- ✅ Role-based access berfungsi
- ✅ Design sesuai BillUp concept (biru, clean, modern)
- ✅ Semua fitur sesuai dengan soal ujian

## 🐛 Common Issues

### Issue: Session tidak persist
**Solution**: Check `.env` `SESSION_DRIVER=file` dan clear cache

### Issue: CSRF Token Mismatch
**Solution**: Refresh halaman dan try lagi

### Issue: 500 Error saat checkout
**Solution**: Check logs di `storage/logs/laravel.log`

### Issue: Produk tidak muncul
**Solution**: Run seeder: `php artisan db:seed`

## 📸 Screenshot Checklist

Untuk dokumentasi ujian, ambil screenshot:
1. Admin Dashboard
2. List Produk
3. Form Tambah Produk
4. List Kategori
5. Dashboard Kasir (3 kolom layout)
6. Keranjang dengan items
7. Invoice/Struk
8. Riwayat Transaksi (Admin view)

---

**Happy Testing! 🎉**
