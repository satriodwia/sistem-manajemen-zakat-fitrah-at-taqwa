<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #059669;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #059669;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info {
            margin-bottom: 20px;
        }
        .info table {
            width: 100%;
        }
        .info td {
            padding: 3px 0;
        }
        .section-title {
            background-color: #059669;
            color: white;
            padding: 8px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data th {
            background-color: #f3f4f6;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        table.data td {
            padding: 6px 8px;
            border: 1px solid #ddd;
        }
        table.data tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .total-row {
            font-weight: bold;
            background-color: #e5e7eb !important;
        }
        .grand-total {
            margin-top: 20px;
            padding: 15px;
            background-color: #059669;
            color: white;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
            color: #666;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN</h1>
        <p>Sistem Zakat & Sedekah Online</p>
        <p>
            Periode: 
            {{ $start_date ? \Carbon\Carbon::parse($start_date)->format('d M Y') : 'Awal' }} 
            s/d 
            {{ $end_date ? \Carbon\Carbon::parse($end_date)->format('d M Y') : 'Sekarang' }}
        </p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="150"><strong>Tanggal Cetak</strong></td>
                <td>: {{ $generated_at->format('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td><strong>Total Transaksi</strong></td>
                <td>: {{ $zakat_payments->count() + $sedekah_payments->count() }} transaksi</td>
            </tr>
        </table>
    </div>

    <!-- ZAKAT FITRAH -->
    <div class="section-title">ZAKAT FITRAH</div>
    <table class="data">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="12%">Tanggal</th>
                <th width="15%">Kode</th>
                <th width="20%">Muzakki</th>
                <th width="8%" class="text-center">Jiwa</th>
                <th width="12%">Jenis</th>
                <th width="15%" class="text-right">Nominal</th>
                <th width="13%">Metode</th>
            </tr>
        </thead>
        <tbody>
            @forelse($zakat_payments as $index => $payment)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $payment->tanggal_bayar->format('d/m/Y') }}</td>
                <td>{{ $payment->kode_transaksi }}</td>
                <td>{{ $payment->muzakki->nama_lengkap }}</td>
                <td class="text-center">{{ $payment->jumlah_jiwa }}</td>
                <td>{{ ucfirst($payment->jenis_bayar) }}</td>
                <td class="text-right">Rp {{ number_format($payment->total_bayar, 0, ',', '.') }}</td>
                <td>{{ $payment->paymentMethod->nama_metode }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
            @if($zakat_payments->count() > 0)
            <tr class="total-row">
                <td colspan="6" class="text-right"><strong>TOTAL ZAKAT FITRAH:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($total_zakat, 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- SEDEKAH -->
    <div class="section-title">SEDEKAH</div>
    <table class="data">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="12%">Tanggal</th>
                <th width="15%">Kode</th>
                <th width="25%">Donatur</th>
                <th width="20%">Catatan</th>
                <th width="15%" class="text-right">Nominal</th>
                <th width="8%">Metode</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sedekah_payments as $index => $payment)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $payment->tanggal_donasi->format('d/m/Y') }}</td>
                <td>{{ $payment->kode_transaksi }}</td>
                <td>{{ $payment->display_name }}</td>
                <td>{{ $payment->catatan ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</td>
                <td>{{ $payment->paymentMethod->nama_metode }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
            @if($sedekah_payments->count() > 0)
            <tr class="total-row">
                <td colspan="5" class="text-right"><strong>TOTAL SEDEKAH:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($total_sedekah, 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- GRAND TOTAL -->
    <div class="grand-total">
        TOTAL KESELURUHAN: Rp {{ number_format($grand_total, 0, ',', '.') }}
    </div>

    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'System' }}</p>
        <p>{{ now()->format('d F Y, H:i:s') }} WIB</p>
    </div>
</body>
</html>