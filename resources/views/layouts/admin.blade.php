<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BillUp') }} - Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Sidebar fixed position */
            #sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                z-index: 40;
            }
            
            /* Main content with margin to accommodate fixed sidebar */
            main {
                margin-left: 16rem; /* 64 * 0.25rem = 16rem (w-64) */
            }
            
            /* Mobile adjustments */
            @media (max-width: 1023px) {
                #sidebar {
                    transform: translateX(-100%);
                    transition: transform 0.3s ease-in-out;
                }
                
                #sidebar:not(.hidden) {
                    transform: translateX(0);
                }
                
                main {
                    margin-left: 0;
                }
            }
            
            /* Fix untuk SweetAlert - prevent padding shift */
            body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) {
                padding-right: 0 !important;
                overflow: hidden !important;
            }
            
            body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) #sidebar {
                padding-right: 0 !important;
            }
        </style>
    </head>
    <body class="h-full bg-gray-50 font-sans antialiased">
        <div class="flex flex-col lg:flex-row h-full">
            <!-- Mobile Header -->
            <div class="lg:hidden bg-blue-600 p-4 flex items-center justify-between">
                <h1 class="text-xl font-bold text-white">BillUp</h1>
                <button id="mobile-menu-btn" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Sidebar -->
            <aside id="sidebar" class="hidden lg:flex w-full lg:w-64 bg-white shadow-lg flex-col">
                <!-- Logo -->
                <div class="hidden lg:flex items-center justify-center h-16 bg-blue-600">
                    <h1 class="text-2xl font-bold text-white">BillUp</h1>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition {{ request()->routeIs('admin.products.*') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Produk
                    </a>

                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Kategori
                    </a>

                    <a href="{{ route('admin.transactions.index') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition {{ request()->routeIs('admin.transactions.*') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Riwayat Transaksi
                    </a>

                    @if(config('features.financial_reports'))
                    <a href="{{ route('admin.reports.index') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition {{ request()->routeIs('admin.reports.*') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Laporan Keuangan
                    </a>
                    @endif
                    </a>
                </nav>

                <!-- User Info & Logout -->
                <div class="p-4 border-t border-gray-200">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                    </div>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="button" onclick="confirmLogout()" class="w-full px-4 py-2 text-sm font-semibold text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto w-full">
                <!-- Header -->
                <header class="bg-white shadow-sm">
                    <div class="px-4 lg:px-8 py-4">
                        <h2 class="text-xl lg:text-2xl font-bold text-gray-800">
                            @yield('title', 'Dashboard')
                        </h2>
                    </div>
                </header>

                <!-- Content -->
                <div class="p-4 lg:p-8">
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- Mobile Menu Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const menuBtn = document.getElementById('mobile-menu-btn');
                const sidebar = document.getElementById('sidebar');
                
                if (menuBtn && sidebar) {
                    menuBtn.addEventListener('click', function() {
                        sidebar.classList.toggle('hidden');
                    });

                    // Close sidebar when clicking outside on mobile
                    document.addEventListener('click', function(event) {
                        if (window.innerWidth < 1024) {
                            if (!sidebar.contains(event.target) && !menuBtn.contains(event.target)) {
                                sidebar.classList.add('hidden');
                            }
                        }
                    });
                }

                // Show SweetAlert for success/error messages
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#3B82F6',
                        timer: 3000,
                        timerProgressBar: true
                    });
                @endif

                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: '{{ session('error') }}',
                        confirmButtonColor: '#3B82F6'
                    });
                @endif
            });

            // Logout confirmation
            function confirmLogout() {
                Swal.fire({
                    title: 'Keluar?',
                    text: 'Apakah Anda yakin ingin keluar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Keluar',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                });
            }
        </script>
    </body>
</html>
