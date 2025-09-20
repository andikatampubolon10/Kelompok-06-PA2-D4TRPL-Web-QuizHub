<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>@yield('title', 'Dashboard Admin Sekolah')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <!-- Tambahkan ini di bagian <head> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;   
        }

        .sidebar-container {
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 18rem;
            background-color: white;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            z-index: 50;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        main {
            margin-left: 18rem;
            min-height: 100vh;
        }

        .sidebar-content {
            height: 100vh;
            overflow-y: auto;
        }

        @media screen and (max-width: 1024px) {
            main {
                margin-left: 0;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-green-50 to-emerald-50">

    <!-- Overlay for mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="sidebar-container transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center">
                    <img src="{{ asset('images/tut.jpg') }}" alt="Logo" class="img-fluid" style="max-height: 55px;">
                </div>
                <div>
                    <h1 class="text-lg font-bold text-green-700">SMK Negeri 2 Balige</h1>
                </div>
            </div>
        </div>

        <div class="sidebar-content">
            <div class="p-6">
                @include('layouts.admin-sidebar')
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main>
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-30">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Hamburger Menu for Mobile -->
                    <div class="flex items-center space-x-4">
                        <button id="menuToggle" class="block lg:hidden text-gray-700 hover:text-gray-900 focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Admin')</h2>
                            <p class="text-sm text-gray-600 mt-1">@yield('page-description', 'Kelola Bisnis ')</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-3">
                            <div class="hidden sm:block text-right">
                                <p class="text-sm font-medium text-gray-800">Admin</p>
                            </div>
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-green-600 text-sm"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="p-6">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            if (menuToggle) {
                menuToggle.addEventListener('click', openSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) {
                    closeSidebar();
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif

    @yield('scripts')
</body>

</html>
