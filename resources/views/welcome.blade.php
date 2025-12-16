<!DOCTYPE html>

<html lang="id">

<head>

    <head>

        <meta charset="UTF-8">
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>BillUp - Sistem Kasir Online Modern</title>
        <title>BillUp - Sistem Kasir Online Modern</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preconnect" href="https://fonts.bunny.net">

        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

<body class="bg-gray-50 font-sans antialiased">

    <nav class="fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-sm shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">Bill<span
                            class="bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">Up</span></span>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    @auth
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">Dashboard
                                Admin</a>
                        @else
                            <a href="{{ route('cashier.index') }}"
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">Buka
                                Kasir</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-6 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-all duration-300">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-gray-700 hover:text-blue-600 font-semibold transition-all duration-300">Login</a>
                        <a href="{{ route('register') }}"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">Daftar
                            Sekarang</a>
                    @endauth
                </div>

                <button onclick="toggleMobileMenu()" class="md:hidden text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <div id="mobileMenu" class="hidden md:hidden mt-4 pb-4 space-y-3">
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg">Dashboard Admin</a>
                    @else
                        <a href="{{ route('cashier.index') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg">Buka Kasir</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50 rounded-lg">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg">Login</a>
                    <a href="{{ route('register') }}"
                        class="block px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg text-center font-semibold">Daftar
                        Sekarang</a>
                @endauth
            </div>
        </div>
    </nav>

    <section class="pt-32 pb-20 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-900 leading-tight mb-6">
                        Sistem Kasir <span
                            class="bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">Modern</span>
                        untuk Bisnis Anda
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        <strong class="text-gray-900">BillUp</strong> adalah solusi Point of Sale (POS) yang dirancang
                        untuk mempercepat transaksi, mengelola produk, dan meningkatkan efisiensi bisnis Anda.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        @guest
                            <a href="{{ route('register') }}"
                                class="px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold text-lg rounded-lg shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">Mulai
                                Gratis</a>
                            <a href="{{ route('login') }}"
                                class="px-8 py-4 bg-white text-blue-600 font-bold text-lg rounded-lg border-2 border-blue-600 hover:bg-blue-50 transition-all duration-300">Login</a>
                        @else
                            @if (auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold text-lg rounded-lg shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">Ke
                                    Dashboard Admin</a>
                            @else
                                <a href="{{ route('cashier.index') }}"
                                    class="px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold text-lg rounded-lg shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">Buka
                                    Kasir</a>
                            @endif
                        @endguest
                    </div>
                </div>

                <div class="relative">
                    <div class="bg-gradient-to-br from-blue-100 to-blue-50 rounded-2xl p-8 shadow-2xl">
                        <div class="bg-white rounded-xl p-6 shadow-lg mb-4">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-500">#INV-2025</span>
                            </div>
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Kopi Susu</span>
                                    <span class="font-semibold">Rp 25.000</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Nasi Goreng</span>
                                    <span class="font-semibold">Rp 30.000</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Teh Manis</span>
                                    <span class="font-semibold">Rp 10.000</span>
                                </div>
                            </div>
                            <div class="border-t-2 border-gray-200 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-800">TOTAL:</span>
                                    <span class="text-3xl font-bold text-blue-600">Rp 65.000</span>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-green-500 text-white p-3 rounded-lg text-center font-semibold">CASH</div>
                            <div class="bg-blue-500 text-white p-3 rounded-lg text-center font-semibold">CASHLESS</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Fitur Unggulan <span
                        class="bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">BillUp</span>
                </h2>
                <p class="text-xl text-gray-600">Solusi lengkap untuk mengelola bisnis Anda dengan mudah</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div
                    class="bg-gray-50 rounded-xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Transaksi Cepat</h3>
                    <p class="text-gray-600 leading-relaxed">Proses pembayaran yang efisien dengan antarmuka yang
                        intuitif. Hemat waktu pelanggan dan tingkatkan omzet.</p>
                </div>

                <div
                    class="bg-gray-50 rounded-xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-green-600 to-green-700 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Manajemen Produk</h3>
                    <p class="text-gray-600 leading-relaxed">Kelola stok, kategori, dan harga produk dengan mudah.
                        Update real-time untuk kontrol inventory yang akurat.</p>
                </div>

                <div
                    class="bg-gray-50 rounded-xl p-8 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Laporan Lengkap</h3>
                    <p class="text-gray-600 leading-relaxed">Analisis penjualan dan riwayat transaksi untuk membantu
                        pengambilan keputusan bisnis yang lebih baik.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Cara Kerja <span
                        class="bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">BillUp</span>
                </h2>
                <p class="text-xl text-gray-600">Tiga langkah mudah untuk memulai</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-3xl font-bold text-white">1</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Daftar & Login</h3>
                    <p class="text-gray-600">Buat akun Anda dan login ke sistem BillUp dengan mudah dan aman.</p>
                </div>

                <div class="text-center">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-green-600 to-green-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-3xl font-bold text-white">2</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Kelola Produk</h3>
                    <p class="text-gray-600">Tambahkan produk, atur kategori, dan kelola stok dengan antarmuka yang
                        user-friendly.</p>
                </div>

                <div class="text-center">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-purple-600 to-purple-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-3xl font-bold text-white">3</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Mulai Transaksi</h3>
                    <p class="text-gray-600">Layani pelanggan dengan cepat menggunakan sistem kasir yang efisien dan
                        mudah digunakan.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-12 shadow-2xl">
                <h2 class="text-4xl font-bold text-white mb-6">Siap Meningkatkan Bisnis Anda?</h2>
                <p class="text-xl text-blue-100 mb-8">Bergabunglah dengan BillUp sekarang dan rasakan kemudahan
                    mengelola kasir Anda.</p>
                @guest
                    <a href="{{ route('register') }}"
                        class="inline-block px-10 py-4 bg-white text-blue-600 font-bold text-lg rounded-lg shadow-xl hover:bg-gray-100 transition-all duration-300 hover:-translate-y-1">Mulai
                        Gratis Sekarang</a>
                @else
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="inline-block px-10 py-4 bg-white text-blue-600 font-bold text-lg rounded-lg shadow-xl hover:bg-gray-100 transition-all duration-300 hover:-translate-y-1">Dashboard
                            Admin</a>
                    @else
                        <a href="{{ route('cashier.index') }}"
                            class="inline-block px-10 py-4 bg-white text-blue-600 font-bold text-lg rounded-lg shadow-xl hover:bg-gray-100 transition-all duration-300 hover:-translate-y-1">Buka
                            Kasir Sekarang</a>
                    @endif
                @endguest
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-gray-400 py-12 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">BillUp</span>
                    </div>
                    <p class="text-sm leading-relaxed">Sistem kasir online modern yang membantu bisnis Anda berkembang
                        dengan lebih efisien.</p>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-4">Menu</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('login') }}"
                                class="hover:text-blue-400 transition-colors duration-300">Login</a></li>
                        <li><a href="{{ route('register') }}"
                                class="hover:text-blue-400 transition-colors duration-300">Daftar</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-4">Tentang</h3>
                    <p class="text-sm leading-relaxed">BillUp adalah solusi POS yang dirancang untuk UMKM dan bisnis
                        retail yang ingin meningkatkan efisiensi operasional.</p>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} BillUp. All rights reserved. Dibuat dengan ❤️ untuk bisnis Indonesia.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>

</body>

</html>
