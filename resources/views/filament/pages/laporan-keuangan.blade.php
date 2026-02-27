<x-filament-panels::page>
    <!-- Filter Form -->
    <div class="mb-6">
        <x-filament-panels::form wire:submit="applyFilter">
            {{ $this->form }}
            
            <div class="mt-4">
                <x-filament::button type="submit" color="primary">
                    <x-filament::icon icon="heroicon-o-funnel" class="w-4 h-4 mr-2" />
                    Terapkan Filter
                </x-filament::button>
            </div>
        </x-filament-panels::form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Total Debit -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-100 text-sm font-medium">Total Masuk (Debit)</p>
                    <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalDebit, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Kredit -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Total Keluar (Kredit)</p>
                    <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalKredit, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Saldo -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Saldo</p>
                    <p class="text-3xl font-bold mt-2">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white/20 rounded-full p-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Ledger Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kode Transaksi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Keterangan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Metode</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Debit</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kredit</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Saldo</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @php
                        $runningSaldo = 0;
                    @endphp
                    
                    @forelse($transactions as $index => $transaction)
                        @php
                            $runningSaldo += $transaction['debit'] - $transaction['kredit'];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ \Carbon\Carbon::parse($transaction['tanggal'])->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-gray-100">
                                {{ $transaction['kode'] }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $transaction['kategori'] == 'Zakat Fitrah' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' : 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200' }}">
                                    {{ $transaction['kategori'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                {{ $transaction['nama'] }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                {{ $transaction['keterangan'] }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                {{ $transaction['metode'] }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-emerald-600 dark:text-emerald-400">
                                {{ $transaction['debit'] > 0 ? 'Rp ' . number_format($transaction['debit'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium text-red-600 dark:text-red-400">
                                {{ $transaction['kredit'] > 0 ? 'Rp ' . number_format($transaction['kredit'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-bold text-blue-600 dark:text-blue-400">
                                Rp {{ number_format($runningSaldo, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm">Tidak ada transaksi pada periode ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                
                @if(count($transactions) > 0)
                <tfoot class="bg-gray-100 dark:bg-gray-900">
                    <tr class="font-bold">
                        <td colspan="7" class="px-4 py-3 text-right text-sm text-gray-900 dark:text-gray-100">
                            TOTAL
                        </td>
                        <td class="px-4 py-3 text-right text-sm text-emerald-600 dark:text-emerald-400">
                            Rp {{ number_format($totalDebit, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right text-sm text-red-600 dark:text-red-400">
                            Rp {{ number_format($totalKredit, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right text-sm text-blue-600 dark:text-blue-400">
                            Rp {{ number_format($saldo, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Info Footer -->
    <div class="mt-6 text-sm text-gray-500 dark:text-gray-400 text-center">
        <p>Total {{ count($transactions) }} transaksi ditampilkan</p>
        <p class="mt-1">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>
</x-filament-panels::page>