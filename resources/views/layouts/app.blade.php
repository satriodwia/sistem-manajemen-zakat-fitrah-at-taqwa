<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pembayaran Zakat & Sedekah')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Midtrans Snap JS -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" 
            data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-emerald-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center hover:opacity-80 transition">
                        <i class="fas fa-mosque text-white text-2xl mr-3"></i>
                        <span class="text-white text-xl font-bold hidden sm:inline">Zakat & Sedekah</span>
                        <span class="text-white text-xl font-bold sm:hidden">Z & S</span>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-white hover:text-emerald-200 px-3 py-2 rounded-md text-sm font-medium transition">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <a href="{{ route('zakat.form') }}" class="text-white hover:text-emerald-200 px-3 py-2 rounded-md text-sm font-medium transition">
                        <i class="fas fa-hand-holding-heart mr-1"></i> Zakat
                    </a>
                    <a href="{{ route('sedekah.form') }}" class="text-white hover:text-emerald-200 px-3 py-2 rounded-md text-sm font-medium transition">
                        <i class="fas fa-heart mr-1"></i> Sedekah
                    </a>
                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle" onclick="toggleDarkMode()" class="text-white hover:text-emerald-200 px-3 py-2 rounded-md text-sm font-medium transition">
                        <i class="fas fa-moon"></i>
                    </button>
                    <a href="{{ url('/admin') }}" class="bg-white text-emerald-600 hover:bg-emerald-50 px-4 py-2 rounded-md text-sm font-medium transition">
                        <i class="fas fa-user-shield mr-1"></i> Admin
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" type="button" class="text-white hover:text-emerald-200 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-emerald-700">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="text-white hover:bg-emerald-800 block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-home mr-2"></i> Home
                </a>
                <a href="{{ route('zakat.form') }}" class="text-white hover:bg-emerald-800 block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-hand-holding-heart mr-2"></i> Bayar Zakat
                </a>
                <a href="{{ route('sedekah.form') }}" class="text-white hover:bg-emerald-800 block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-heart mr-2"></i> Sedekah
                </a>
                <button onclick="toggleDarkMode()" class="text-white hover:bg-emerald-800 w-full text-left px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-moon mr-2" id="darkModeToggleMobile"></i> Dark Mode
                </button>
                <a href="{{ url('/admin') }}" class="text-white hover:bg-emerald-800 block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-user-shield mr-2"></i> Admin Panel
                </a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="py-10">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 py-8 text-center">
            <p>&copy; {{ date('Y') }} Sistem Zakat & Sedekah. Semua hak dilindungi.</p>
            <p class="text-sm text-gray-400 mt-2">Dibuat dengan Laravel & Filament</p>
        </div>
    </footer>

    @stack('scripts')
    
    <!-- Mobile Menu Toggle Script -->
    <script>
        // Mobile Menu Toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const button = document.getElementById('mobile-menu-button');
            
            if (!menu.contains(event.target) && !button.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // Dark Mode Toggle Function (Global)
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDark);
            
            // Update desktop icon
            const desktopIcon = document.querySelector('#darkModeToggle i');
            if (desktopIcon) {
                if (isDark) {
                    desktopIcon.classList.remove('fa-moon');
                    desktopIcon.classList.add('fa-sun');
                } else {
                    desktopIcon.classList.remove('fa-sun');
                    desktopIcon.classList.add('fa-moon');
                }
            }

            // Update mobile icon
            const mobileIcon = document.querySelector('#darkModeToggleMobile');
            if (mobileIcon) {
                if (isDark) {
                    mobileIcon.classList.remove('fa-moon');
                    mobileIcon.classList.add('fa-sun');
                } else {
                    mobileIcon.classList.remove('fa-sun');
                    mobileIcon.classList.add('fa-moon');
                }
            }
        }

        // Load dark mode preference on page load
        document.addEventListener('DOMContentLoaded', function() {
            const darkMode = localStorage.getItem('darkMode') === 'true';
            if (darkMode) {
                document.body.classList.add('dark-mode');
                
                const desktopIcon = document.querySelector('#darkModeToggle i');
                if (desktopIcon) {
                    desktopIcon.classList.remove('fa-moon');
                    desktopIcon.classList.add('fa-sun');
                }

                const mobileIcon = document.querySelector('#darkModeToggleMobile');
                if (mobileIcon) {
                    mobileIcon.classList.remove('fa-moon');
                    mobileIcon.classList.add('fa-sun');
                }
            }
        });
    </script>
</body>
</html>