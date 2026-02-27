@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="max-w-3xl mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <!-- Success Icon -->
        <div class="mb-6">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full">
                <i class="fas fa-check-circle text-green-600 text-5xl"></i>
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            @if($payment->paymentMethod->nama_metode == 'Tunai')
                Pembayaran Dicatat!
            @elseif($payment->status == 'lunas' || $payment->status == 'diterima')
                Pembayaran Berhasil!
            @else
                Pembayaran Dicatat!
            @endif
        </h1>
        
        @if($payment->paymentMethod->nama_metode == 'Tunai')
            <p class="text-gray-600 mb-8">
                Silakan datang ke <strong>Masjid At-Taqwa</strong> dengan membawa kode pembayaran berikut.
            </p>
            
            <!-- Kode Pembayaran untuk Tunai -->
            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-lg p-8 mb-6">
                <p class="text-white text-sm font-medium mb-2">KODE PEMBAYARAN ANDA</p>
                <p class="text-white text-5xl font-bold tracking-wider font-mono">
                    {{ $payment->kode_transaksi }}
                </p>
                <p class="text-emerald-100 text-sm mt-4">
                    <i class="fas fa-info-circle mr-1"></i>
                    Tunjukkan kode ini ke panitia saat datang ke masjid
                </p>
            </div>
            
            <!-- Instruksi untuk Tunai -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6 text-left">
                <h3 class="font-semibold text-blue-900 mb-3 flex items-center">
                    <i class="fas fa-clipboard-list text-blue-600 text-xl mr-2"></i>
                    Langkah Selanjutnya
                </h3>
                <ol class="list-decimal list-inside space-y-2 text-blue-800">
                    <li>Datang ke <strong>Masjid At-Taqwa</strong></li>
                    <li>Tunjukkan <strong>kode pembayaran</strong> di atas ke panitia</li>
                    <li>Serahkan pembayaran {{ $type == 'zakat' ? 'zakat' : 'sedekah' }} Anda</li>
                    <li>Panitia akan konfirmasi pembayaran di sistem</li>
                    <li>Anda akan menerima bukti pembayaran cetak</li>
                </ol>
            </div>
        @elseif($payment->status == 'lunas' || $payment->status == 'diterima')
            <p class="text-gray-600 mb-8">
                Jazakallahu khairan. Semoga menjadi amal jariyah yang bermanfaat.
            </p>
        @else
            <p class="text-gray-600 mb-8">
                Silakan lakukan pembayaran sesuai metode yang dipilih.
            </p>
        @endif

        <!-- Payment Details -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
            <h3 class="font-semibold text-lg mb-4 text-center border-b pb-2">Detail Transaksi</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Kode Transaksi:</span>
                    <span class="font-bold text-gray-800">{{ $payment->kode_transaksi }}</span>
                </div>
                
                @if($type == 'zakat')
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Muzakki:</span>
                        <span class="font-medium text-gray-800">{{ $payment->muzakki->nama_lengkap }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah Jiwa:</span>
                        <span class="font-medium text-gray-800">{{ $payment->jumlah_jiwa }} jiwa</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jenis Bayar:</span>
                        <span class="font-medium text-gray-800">{{ ucfirst($payment->jenis_bayar) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total:</span>
                        <span class="font-bold text-emerald-600 text-xl">Rp {{ number_format($payment->total_bayar, 0, ',', '.') }}</span>
                    </div>
                @else
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Donatur:</span>
                        <span class="font-medium text-gray-800">{{ $payment->display_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nominal:</span>
                        <span class="font-bold text-pink-600 text-xl">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</span>
                    </div>
                    @if($payment->catatan)
                    <div class="pt-3 border-t">
                        <span class="text-gray-600 block mb-1">Pesan:</span>
                        <p class="text-gray-700 italic">"{{ $payment->catatan }}"</p>
                    </div>
                    @endif
                @endif
                
                <div class="flex justify-between pt-3 border-t">
                    <span class="text-gray-600">Metode Pembayaran:</span>
                    <span class="font-medium text-gray-800">{{ $payment->paymentMethod->nama_metode }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($payment->status == 'lunas' || $payment->status == 'diterima')
                            bg-green-100 text-green-800
                        @elseif($payment->status == 'waiting' || $payment->status == 'pending')
                            bg-yellow-100 text-yellow-800
                        @else
                            bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal:</span>
                    <span class="font-medium text-gray-800">
                        {{ $type == 'zakat' ? $payment->tanggal_bayar->format('d M Y, H:i') : $payment->tanggal_donasi->format('d M Y, H:i') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Info Pembayaran Manual -->
        @if($payment->paymentMethod->nama_metode != 'Midtrans Payment Gateway' && ($payment->status == 'pending' || $payment->status == 'waiting'))
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 text-xl mt-1 mr-3"></i>
                <div class="text-left">
                    <h4 class="font-semibold text-blue-900 mb-2">Informasi Pembayaran</h4>
                    <p class="text-sm text-blue-800">{{ $payment->paymentMethod->keterangan }}</p>
                    <p class="text-sm text-blue-800 mt-2">Simpan kode transaksi Anda untuk konfirmasi pembayaran.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            @if($payment->paymentMethod->nama_metode == 'Tunai')
                <!-- Untuk Tunai: Hanya tombol kembali -->
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition">
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Beranda
                </a>
            @else
                <!-- Untuk Online: Ada tombol cetak -->
                @if($payment->status == 'lunas' || $payment->status == 'diterima')
                    <button onclick="window.print()" 
                            class="inline-flex items-center justify-center px-6 py-3 border-2 border-emerald-600 text-emerald-600 rounded-lg font-medium hover:bg-emerald-50 transition">
                        <i class="fas fa-print mr-2"></i>
                        Cetak Bukti
                    </button>
                @endif
                
                <a href="{{ $type == 'zakat' ? route('zakat.form') : route('sedekah.form') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                    <i class="fas fa-redo mr-2"></i>
                    {{ $type == 'zakat' ? 'Bayar Zakat Lagi' : 'Sedekah Lagi' }}
                </a>
                
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition">
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Beranda
                </a>
            @endif
        </div>

        @if($payment->paymentMethod->nama_metode != 'Tunai')
        <!-- Print Button (hanya untuk online) -->
        <div class="mt-6">
            <button onclick="window.print()" 
                    class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                <i class="fas fa-print mr-2"></i>Cetak Bukti
            </button>
        </div>
        @endif
    </div>

    <!-- Doa -->
    <div class="mt-8 bg-gradient-to-r from-emerald-50 to-blue-50 rounded-lg p-6 text-center">
        <p class="text-gray-700 italic mb-2">
            "Perumpamaan orang-orang yang menginfakkan hartanya di jalan Allah adalah seperti sebutir biji yang menumbuhkan tujuh tangkai, pada setiap tangkai ada seratus biji. Allah melipatgandakan bagi siapa yang Dia kehendaki."
        </p>
        <p class="text-sm text-gray-600 font-semibold">(QS. Al-Baqarah: 261)</p>
    </div>
</div>

<style>
    @media print {
        nav, footer, .no-print, button {
            display: none !important;
        }
    }
</style>
@endsection