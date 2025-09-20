<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>@yield('title', 'Dashboard Admin Sekolah')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Sidebar Styles */
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
        
        /* Custom scrollbar for sidebar */
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

        /* Adjustments for main content area */
        main {
            margin-left: 18rem;
            min-height: 100vh;
        }

        /* Make sidebar content scrollable */
        .sidebar-content {
            height: 100vh;
            overflow-y: auto;
        }
        
        /* Optional: Space adjustments for mobile screen sizes */
        @media screen and (max-width: 1024px) {
            main {
                margin-left: 0;
            }

            .sidebar-container {
                position: static;
                width: 100%;
                box-shadow: none;
            }

            /* Ensure sidebar remains visible in mobile */
            .sidebar-content {
                overflow-y: scroll;
                max-height: calc(100vh - 4rem); /* To leave space for header */
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-green-50 to-emerald-50">

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar-container">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10  rounded-lg flex items-center justify-center">
                    <img src="{{ asset('images/tut.jpg') }}" alt="Logo" class="img-fluid" style="max-height: 55px;">
                </div>
                <div>
                    <h1 class="text-lg font-bold text-green-700">SMK Negeri 2 Balige</h1>
                   
                </div>
            </div>
        </div>

        <!-- Sidebar Content with scroll -->
        <div class="sidebar-content">
            <div class="p-6">
                @include('layouts.guru-sidebar')
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main>
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-30">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="lg:ml-0 ml-16">
                        <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard Guru')</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                       <div class="flex items-center space-x-3">
       <div class="hidden sm:block text-right">
        <p class="text-sm font-medium text-gray-800">Guru</p>
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
        // Optional: Handle sidebar toggle for mobile (if you still want mobile support)
        document.addEventListener('DOMContentLoaded', function () {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const closeMenu = document.getElementById('closeMenu');

            function openSidebar() {
                sidebar.classList.add('show');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            if (menuToggle) {
                menuToggle.addEventListener('click', openSidebar);
            }

            if (closeMenu) {
                closeMenu.addEventListener('click', closeSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            // Close sidebar on window resize to desktop
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) {
                    closeSidebar();
                }
            });
        });
    </script>
</body>

</html>
