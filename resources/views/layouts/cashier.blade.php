<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BillUp') }} - Kasir</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,600,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }
            .scrollbar-hide {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
            
            /* Fix untuk SweetAlert - prevent padding shift */
            body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) {
                padding-right: 0 !important;
                overflow: hidden !important;
            }
            
            body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) #sidebar {
                padding-right: 0 !important;
            }
            
            /* Mobile menu positioning */
            @media (max-width: 1023px) {
                #sidebar {
                    position: fixed;
                    top: 64px;
                    left: 0;
                    bottom: 0;
                    z-index: 50;
                    width: 80%;
                    max-width: 280px;
                    transform: translateX(-100%);
                    transition: transform 0.3s ease-in-out;
                }
                
                #sidebar.open {
                    transform: translateX(0);
                }
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

            <!-- Sidebar Navigation -->
            <aside id="sidebar" class="lg:w-1/6 bg-white shadow-lg flex flex-col">
                <!-- Logo -->
                <div class="hidden lg:flex items-center justify-center h-16 bg-blue-600">
                    <h1 class="text-xl font-bold text-white">BillUp</h1>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-3 py-6 space-y-2">
                    <a href="{{ route('cashier.index') }}" 
                       class="flex flex-col items-center px-3 py-4 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition {{ request()->routeIs('cashier.index') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                        <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-xs">Kasir</span>
                    </a>

                    <a href="{{ route('cashier.transactions') }}" 
                       class="flex flex-col items-center px-3 py-4 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition {{ request()->routeIs('cashier.transactions*') ? 'bg-blue-50 text-blue-600 font-semibold' : '' }}">
                        <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-xs">Riwayat</span>
                    </a>
                </nav>

                <!-- User Info & Logout -->
                <div class="p-3 border-t border-gray-200">
                    <div class="flex flex-col items-center mb-3">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <p class="text-sm font-semibold text-gray-700 mt-2 text-center">{{ auth()->user()->name }}</p>
                    </div>
                    <form id="logout-form-cashier" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="button" onclick="confirmLogout()" class="w-full px-3 py-2 text-xs font-semibold text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 flex flex-col lg:flex-row w-full">
                @yield('content')
            </main>
        </div>

        <!-- Mobile Menu Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const menuBtn = document.getElementById('mobile-menu-btn');
                const sidebar = document.getElementById('sidebar');
                
                if (menuBtn && sidebar) {
                    menuBtn.addEventListener('click', function() {
                        sidebar.classList.toggle('open');
                    });

                    // Close sidebar when clicking outside on mobile
                    document.addEventListener('click', function(event) {
                        if (window.innerWidth < 1024) {
                            if (!sidebar.contains(event.target) && !menuBtn.contains(event.target)) {
                                sidebar.classList.remove('open');
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
                        document.getElementById('logout-form-cashier').submit();
                    }
                });
            }
        </script>
    </body>
</html>
