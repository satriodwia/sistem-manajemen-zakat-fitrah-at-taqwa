@extends('layouts.app')

@section('title', 'Cek Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center">
            <div class="bg-green-100 p-4 rounded-full">
                <i class="fas fa-search text-green-600 text-3xl"></i>
            </div>
            <div class="ml-4">
                <h1 class="text-2xl font-bold text-gray-800">Cek Pembayaran</h1>
                <p class="text-gray-600">Cari nama Anda untuk melihat riwayat pembayaran zakat/sedekah</p>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('cek.pembayaran') }}">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama atau No. Telepon</label>
            <div class="flex gap-3">
                <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Contoh: Satrio atau 0881..."
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Hasil Pencarian -->
    @if($keyword)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Hasil pencarian untuk "{{ $keyword }}"
            </h2>

            @if($riwayat->isEmpty())
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-3"></i>
                    <p>Tidak ditemukan riwayat pembayaran dengan kata kunci tersebut.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="px-4 py-3 text-sm font-semibold text-gray-700">Jenis</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-700">Nama</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-700">Kode Transaksi</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-700">Tanggal</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-700">Nominal</th>
                                <th class="px-4 py-3 text-sm font-semibold text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayat as $item)
                                <tr class="border-b">
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            {{ $item['jenis'] === 'Zakat' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                            {{ $item['jenis'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ $item['nama'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $item['kode_transaksi'] }}</td>
                                    <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($item['tanggal'])->format('d M Y') }}</td>
                                    <td class="px-4 py-3 text-sm">Rp {{ number_format($item['nominal'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusColor = match($item['status']) {
                                                'lunas', 'diterima' => 'bg-green-100 text-green-700',
                                                'pending', 'waiting' => 'bg-yellow-100 text-yellow-700',
                                                default => 'bg-red-100 text-red-700',
                                            };
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                            {{ ucfirst($item['status']) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
