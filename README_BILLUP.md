# 🧾 BillUp - Sistem Kasir Online Modern

Aplikasi web kasir online dengan tema café/coffee shop yang dibangun menggunakan **Laravel 11**, **Blade**, **Tailwind CSS**, dan **Laravel Breeze** untuk autentikasi.

## ✨ Fitur Utama

### 👤 Role System
- **Admin**: Kelola produk, kategori, dan lihat riwayat transaksi
- **User/Kasir**: Proses transaksi penjualan

### 🔐 Autentikasi
- Login & Register dengan Laravel Breeze
- Middleware role-based access control
- Password hashing dengan bcrypt

### 📦 Manajemen Produk (Admin)
- CRUD Produk (Create, Read, Update, Delete)
- CRUD Kategori dengan icon emoji
- Filter produk berdasarkan kategori
- Management stok otomatis

### 💰 Sistem Kasir (User)
- **UI 3 Kolom Modern**:
  - Sidebar navigasi (15%)
  - Katalog produk dengan search & filter (60%)
  - Keranjang & checkout (40%)
- Real-time cart management
- Search produk
- Filter berdasarkan kategori
- Quantity adjustment (+/-)
- Payment method: Cash & Cashless
- Fitur diskon (dapat di-toggle via config)

### 📊 Riwayat Transaksi
- Admin dapat melihat semua transaksi
- Detail transaksi lengkap
- Filter dan pagination
- Cetak struk/invoice (print-ready)

### 🎨 Design System
Mengikuti konsep **BillUp Design System**:
- **Warna Primer**: `#007BFF` (Biru Cerah)
- **Warna Sekunder**: `#28A745` (Hijau)
- **Background**: `#F8F9FA` (Abu-abu Terang)
- **Font**: Inter (Clean & Modern Sans-serif)
- **Layout**: Responsive dengan fokus pada efisiensi

## 🚀 Instalasi & Setup

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Laragon (atau XAMPP/WAMP)

### Langkah Instalasi

1. **Clone/Extract project ke folder Laragon**
   ```bash
   cd C:\laragon\www\BillUp
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup Environment**
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

4. **Configure Database** (edit `.env`)
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=billup
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run Migrations & Seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build Assets**
   ```bash
   npm run build
   # atau untuk development
   npm run dev
   ```

7. **Start Server**
   ```bash
   php artisan serve
   ```

8. **Akses Aplikasi**
   - URL: `http://localhost:8000`
   - Admin: `admin@billup.com` / `password`
   - Kasir: `kasir@billup.com` / `password`

## 📁 Struktur Project

```
BillUp/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Controllers untuk admin
│   │   │   └── User/           # Controllers untuk kasir
│   │   └── Middleware/
│   │       └── CheckRole.php   # Role-based middleware
│   └── Models/
│       ├── Category.php
│       ├── Product.php
│       ├── Transaction.php
│       └── TransactionItem.php
├── config/
│   └── features.php            # Feature toggles
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── admin/              # Views untuk admin
│       ├── cashier/            # Views untuk kasir
│       └── layouts/
│           ├── admin.blade.php
│           └── cashier.blade.php
└── routes/
    └── web.php                 # All routes
```

## 🔧 Konfigurasi Feature Toggle

Edit `config/features.php` untuk enable/disable fitur:

```php
return [
    'discount' => env('FEATURE_DISCOUNT', true),  // Fitur diskon
    'transaction_history' => env('FEATURE_TRANSACTION_HISTORY', true),
];
```

Atau via `.env`:
```
FEATURE_DISCOUNT=true
FEATURE_TRANSACTION_HISTORY=true
```

## 📊 Database Schema

### Users
- `id`, `name`, `email`, `password`, `role` (admin/user)

### Categories
- `id`, `name`, `icon`

### Products
- `id`, `category_id`, `name`, `description`, `price`, `stock`, `image`

### Transactions
- `id`, `invoice_number`, `user_id`, `subtotal`, `discount`, `total`, `payment_method`, `notes`

### Transaction Items
- `id`, `transaction_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal`

## 🎯 Fitur Sesuai Soal

### ✅ ROLE (2)
1. **Admin** - Full access
2. **User/Pembeli** - Kasir access

### ✅ FUNGSI ADMIN (5)
1. ✅ **LOGIN**
2. ✅ **CRUD PRODUK**
3. ✅ **CRUD KATEGORI** (Opsional - Implemented)
4. ✅ **RIWAYAT TRANSAKSI** (Opsional - Implemented)
5. ✅ **LOGOUT**

### ✅ FUNGSI USER (6)
1. ✅ **REGISTRASI**
2. ✅ **LOGIN**
3. ✅ **SEARCH** (produk)
4. ✅ **BELI + CART**
5. ✅ **PEMBAYARAN + CETAK STRUK** (CASH & CASHLESS)
6. ✅ **LOGOUT**

## 🎨 Design Features

- ✅ Modern & Clean UI
- ✅ Responsive Design
- ✅ Color Palette sesuai konsep BillUp
- ✅ Inter Font Family
- ✅ 3-Column Layout untuk Kasir
- ✅ Real-time Cart Updates
- ✅ Print-ready Invoice
- ✅ Icon-based Navigation

## 🧪 Testing

### Test Admin Login
1. Buka `http://localhost:8000/login`
2. Email: `admin@billup.com`
3. Password: `password`
4. Akan redirect ke `/admin/dashboard`

### Test Kasir Login
1. Buka `http://localhost:8000/login`
2. Email: `kasir@billup.com`
3. Password: `password`
4. Akan redirect ke `/cashier`

### Test Transaksi
1. Login sebagai kasir
2. Pilih produk dari katalog
3. Tambah ke keranjang
4. Atur quantity jika perlu
5. Pilih metode pembayaran
6. (Optional) Tambahkan diskon
7. Klik "BAYAR SEKARANG"
8. Lihat invoice & cetak struk

## 📝 Notes

- **Tema**: Café/Coffee Shop dengan 21 produk sample
- **Kategori**: Kopi, Minuman Non-Kopi, Makanan, Snack
- **Stok**: Otomatis berkurang saat transaksi
- **Invoice**: Auto-generate dengan format INV-YYYYMMDD-XXXX
- **Session-based Cart**: Menggunakan Laravel session untuk cart management
- **Middleware**: Custom CheckRole middleware untuk role-based access

## 🛠️ Troubleshooting

### Error: "Target class [CheckRole] does not exist"
- Pastikan middleware sudah diregister di `bootstrap/app.php`

### Cart tidak update
- Clear browser cache
- Check browser console untuk error JavaScript

### Stok tidak berkurang
- Check database transaction rollback
- Verify product stock values

## 👨‍💻 Developer

Developed by **[Your Name]** untuk Ujian Akhir Pemrograman Web

## 📄 License

This project is developed for educational purposes.

---

**BillUp** - Sistem Kasir Online Modern 🚀
