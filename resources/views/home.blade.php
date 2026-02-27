@extends('layouts.app')

@section('title', 'Sistem Zakat & Sedekah Online')

@section('content')
<style>
    /* Dark Mode Variables */
    :root {
        --bg-primary: #F9FAFB;
        --bg-secondary: #FFFFFF;
        --text-primary: #1F2937;
        --text-secondary: #6B7280;
        --border-color: #E5E7EB;
    }

    .dark-mode {
        --bg-primary: #1F2937;
        --bg-secondary: #111827;
        --text-primary: #F9FAFB;
        --text-secondary: #D1D5DB;
        --border-color: #374151;
    }

    body {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    .animate-fadeIn {
        animation: fadeIn 1s ease-out forwards;
    }

    .animate-slideInLeft {
        animation: slideInLeft 0.8s ease-out forwards;
    }

    .animate-slideInRight {
        animation: slideInRight 0.8s ease-out forwards;
    }

    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    .gradient-animate {
        background: linear-gradient(-45deg, #059669, #0d9488, #0891b2, #0284c7);
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
    }

    .delay-100 { animation-delay: 0.1s; opacity: 0; }
    .delay-200 { animation-delay: 0.2s; opacity: 0; }
    .delay-300 { animation-delay: 0.3s; opacity: 0; }
    .delay-400 { animation-delay: 0.4s; opacity: 0; }
    .delay-500 { animation-delay: 0.5s; opacity: 0; }

    /* Card Hover Effects */
    .hover-card {
        transition: all 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Button Pulse Effect */
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .btn-pulse:hover {
        animation: pulse 1s ease-in-out infinite;
    }

    /* Dark Mode Specific */
    .dark-mode .bg-white {
        background-color: var(--bg-secondary) !important;
    }

    .dark-mode .text-gray-800 {
        color: var(--text-primary) !important;
    }

    .dark-mode .text-gray-600 {
        color: var(--text-secondary) !important;
    }

    .dark-mode .border-gray-200 {
        border-color: var(--border-color) !important;
    }

    .dark-mode .shadow-md {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
    }
</style>

<!-- Hero Section -->
<div class="relative gradient-animate text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 py-20 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 animate-fadeInUp">
                Tunaikan Zakat & Sedekah Anda
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-emerald-50 animate-fadeInUp delay-100">
                Mudah, Cepat, dan Amanah
            </p>
            <p class="text-lg mb-10 max-w-3xl mx-auto text-emerald-100 animate-fadeInUp delay-200">
                Sistem pembayaran zakat fitrah dan sedekah online yang memudahkan Anda untuk berbagi kebaikan dengan sesama
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fadeInUp delay-300">
                <a href="{{ route('zakat.form') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-white text-emerald-600 rounded-lg font-bold text-lg hover:bg-emerald-50 transition duration-300 shadow-lg transform hover:scale-105 btn-pulse">
                    <i class="fas fa-hand-holding-heart mr-3"></i>
                    Bayar Zakat Fitrah
                </a>
                <a href="{{ route('sedekah.form') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-emerald-700 text-white rounded-lg font-bold text-lg hover:bg-emerald-800 transition duration-300 shadow-lg border-2 border-white transform hover:scale-105">
                    <i class="fas fa-heart mr-3"></i>
                    Sedekah Sekarang
                </a>
            </div>
        </div>
    </div>
    
    <!-- Floating Icons -->
    <div class="absolute top-20 left-10 opacity-20 animate-float">
        <i class="fas fa-mosque text-white text-6xl"></i>
    </div>
    <div class="absolute bottom-20 right-10 opacity-20 animate-float" style="animation-delay: 1s;">
        <i class="fas fa-hands text-white text-6xl"></i>
    </div>
    
    <!-- Wave decoration -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="var(--bg-primary)"/>
        </svg>
    </div>
</div>

<!-- Features Section -->
<div class="py-20" style="background-color: var(--bg-primary);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-fadeInUp">
            <h2 class="text-3xl md:text-4xl font-bold mb-4" style="color: var(--text-primary);">
                Kenapa Memilih Kami?
            </h2>
            <p class="text-lg" style="color: var(--text-secondary);">
                Sistem pembayaran zakat yang terpercaya dan mudah digunakan
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="rounded-xl shadow-md p-8 text-center hover-card animate-fadeInUp delay-100" style="background-color: var(--bg-secondary);">
                <div class="bg-emerald-100 dark:bg-emerald-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 transform transition-transform hover:rotate-12">
                    <i class="fas fa-shield-alt text-emerald-600 dark:text-emerald-400 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">Aman & Terpercaya</h3>
                <p style="color: var(--text-secondary);">
                    Sistem terintegrasi dengan payment gateway terpercaya untuk keamanan transaksi Anda
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="rounded-xl shadow-md p-8 text-center hover-card animate-fadeInUp delay-200" style="background-color: var(--bg-secondary);">
                <div class="bg-blue-100 dark:bg-blue-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 transform transition-transform hover:rotate-12">
                    <i class="fas fa-bolt text-blue-600 dark:text-blue-400 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">Cepat & Mudah</h3>
                <p style="color: var(--text-secondary);">
                    Proses pembayaran yang simpel, hanya butuh beberapa klik untuk menunaikan zakat
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="rounded-xl shadow-md p-8 text-center hover-card animate-fadeInUp delay-300" style="background-color: var(--bg-secondary);">
                <div class="bg-pink-100 dark:bg-pink-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 transform transition-transform hover:rotate-12">
                    <i class="fas fa-receipt text-pink-600 dark:text-pink-400 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">Bukti Transaksi</h3>
                <p style="color: var(--text-secondary);">
                    Dapatkan bukti pembayaran digital yang dapat disimpan atau dicetak kapan saja
                </p>
            </div>
        </div>
    </div>
</div>

<!-- About Zakat Section -->
<div class="py-20" style="background-color: var(--bg-secondary);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="animate-slideInLeft">
                <h2 class="text-3xl md:text-4xl font-bold mb-6" style="color: var(--text-primary);">
                    Tentang Zakat Fitrah
                </h2>
                <p class="text-lg mb-4" style="color: var(--text-secondary);">
                    Zakat fitrah adalah zakat yang wajib dikeluarkan oleh setiap muslim menjelang Idul Fitri sebagai pembersih bagi orang yang berpuasa dari perbuatan sia-sia dan sebagai makanan bagi orang miskin.
                </p>
                <p class="text-lg mb-6" style="color: var(--text-secondary);">
                    Besaran zakat fitrah setara dengan 2,5 kg atau 3,5 liter makanan pokok, atau dapat diganti dengan uang senilai harga tersebut.
                </p>
                
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-600 p-6 rounded-r-lg transform transition-all hover:scale-105">
                    <p class="text-gray-700 dark:text-gray-300 italic mb-2">
                        "Barangsiapa menunaikan zakat fitrah sebelum shalat (Idul Fitri), maka zakatnya diterima. Dan barangsiapa menunaikannya setelah shalat, maka itu hanya sedekah biasa."
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 font-semibold">- HR. Abu Daud & Ibnu Majah</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/50 dark:to-teal-900/50 rounded-2xl p-8 animate-slideInRight transform transition-all hover:shadow-2xl">
                <h3 class="text-2xl font-bold mb-6" style="color: var(--text-primary);">Ketentuan Zakat Fitrah</h3>
                <ul class="space-y-4">
                    <li class="flex items-start transform transition-transform hover:translate-x-2">
                        <i class="fas fa-check-circle text-emerald-600 dark:text-emerald-400 text-xl mr-3 mt-1"></i>
                        <div>
                            <p class="font-semibold" style="color: var(--text-primary);">Wajib bagi setiap Muslim</p>
                            <p class="text-sm" style="color: var(--text-secondary);">Laki-laki, perempuan, dewasa, atau anak-anak</p>
                        </div>
                    </li>
                    <li class="flex items-start transform transition-transform hover:translate-x-2">
                        <i class="fas fa-check-circle text-emerald-600 dark:text-emerald-400 text-xl mr-3 mt-1"></i>
                        <div>
                            <p class="font-semibold" style="color: var(--text-primary);">Waktu Pembayaran</p>
                            <p class="text-sm" style="color: var(--text-secondary);">Mulai awal Ramadan hingga sebelum shalat Idul Fitri</p>
                        </div>
                    </li>
                    <li class="flex items-start transform transition-transform hover:translate-x-2">
                        <i class="fas fa-check-circle text-emerald-600 dark:text-emerald-400 text-xl mr-3 mt-1"></i>
                        <div>
                            <p class="font-semibold" style="color: var(--text-primary);">Besaran Zakat</p>
                            <p class="text-sm" style="color: var(--text-secondary);">2,5 kg atau 3,5 liter makanan pokok per jiwa</p>
                        </div>
                    </li>
                    <li class="flex items-start transform transition-transform hover:translate-x-2">
                        <i class="fas fa-check-circle text-emerald-600 dark:text-emerald-400 text-xl mr-3 mt-1"></i>
                        <div>
                            <p class="font-semibold" style="color: var(--text-primary);">Penerima Zakat</p>
                            <p class="text-sm" style="color: var(--text-secondary);">8 golongan (asnaf) yang berhak menerima zakat</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Payment Methods -->
<div class="py-20" style="background-color: var(--bg-primary);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 animate-fadeInUp">
            <h2 class="text-3xl md:text-4xl font-bold mb-4" style="color: var(--text-primary);">
                Metode Pembayaran
            </h2>
            <p class="text-lg" style="color: var(--text-secondary);">
                Berbagai pilihan metode pembayaran untuk kemudahan Anda
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
            <div class="rounded-lg shadow-md p-6 text-center hover-card animate-fadeInUp delay-100" style="background-color: var(--bg-secondary);">
                <i class="fas fa-credit-card text-4xl text-blue-600 dark:text-blue-400 mb-3"></i>
                <p class="font-medium" style="color: var(--text-primary);">Credit Card</p>
            </div>
            <div class="rounded-lg shadow-md p-6 text-center hover-card animate-fadeInUp delay-200" style="background-color: var(--bg-secondary);">
                <i class="fas fa-university text-4xl text-emerald-600 dark:text-emerald-400 mb-3"></i>
                <p class="font-medium" style="color: var(--text-primary);">Transfer Bank</p>
            </div>
            <div class="rounded-lg shadow-md p-6 text-center hover-card animate-fadeInUp delay-300" style="background-color: var(--bg-secondary);">
                <i class="fas fa-mobile-alt text-4xl text-teal-600 dark:text-teal-400 mb-3"></i>
                <p class="font-medium" style="color: var(--text-primary);">GoPay</p>
            </div>
            <div class="rounded-lg shadow-md p-6 text-center hover-card animate-fadeInUp delay-400" style="background-color: var(--bg-secondary);">
                <i class="fas fa-qrcode text-4xl text-purple-600 dark:text-purple-400 mb-3"></i>
                <p class="font-medium" style="color: var(--text-primary);">QRIS</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="gradient-animate py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 animate-fadeInUp">
            Mulai Tunaikan Zakat Anda Sekarang
        </h2>
        <p class="text-emerald-100 text-lg mb-8 animate-fadeInUp delay-100">
            Jangan tunda kebaikan, tunaikan zakat fitrah Anda dengan mudah melalui sistem kami
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fadeInUp delay-200">
            <a href="{{ route('zakat.form') }}" 
               class="inline-flex items-center justify-center px-8 py-4 bg-white text-emerald-600 rounded-lg font-bold text-lg hover:bg-emerald-50 transition duration-300 shadow-lg transform hover:scale-105">
                <i class="fas fa-hand-holding-heart mr-3"></i>
                Bayar Zakat Fitrah
            </a>
            <a href="{{ route('sedekah.form') }}" 
               class="inline-flex items-center justify-center px-8 py-4 bg-transparent text-white rounded-lg font-bold text-lg hover:bg-emerald-700 transition duration-300 border-2 border-white transform hover:scale-105">
                <i class="fas fa-heart mr-3"></i>
                Sedekah Sekarang
            </a>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="py-16" style="background-color: var(--bg-secondary);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="transform transition-all hover:scale-110 animate-fadeInUp delay-100">
                <div class="text-4xl font-bold text-emerald-600 dark:text-emerald-400 mb-2">1000+</div>
                <p style="color: var(--text-secondary);">Muzakki Terdaftar</p>
            </div>
            <div class="transform transition-all hover:scale-110 animate-fadeInUp delay-200">
                <div class="text-4xl font-bold text-emerald-600 dark:text-emerald-400 mb-2">500+</div>
                <p style="color: var(--text-secondary);">Mustahik Terbantu</p>
            </div>
            <div class="transform transition-all hover:scale-110 animate-fadeInUp delay-300">
                <div class="text-4xl font-bold text-emerald-600 dark:text-emerald-400 mb-2">Rp 50 Juta+</div>
                <p style="color: var(--text-secondary);">Dana Terkumpul</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Dark Mode Toggle
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
        const isDark = document.body.classList.contains('dark-mode');
        localStorage.setItem('darkMode', isDark);
        
        // Update icon
        const icon = document.querySelector('#darkModeToggle i');
        if (isDark) {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }
    }

    // Load dark mode preference
    document.addEventListener('DOMContentLoaded', function() {
        const darkMode = localStorage.getItem('darkMode') === 'true';
        if (darkMode) {
            document.body.classList.add('dark-mode');
            const icon = document.querySelector('#darkModeToggle i');
            if (icon) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }
        }
    });
</script>
@endsection