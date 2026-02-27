<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran Zakat - {{ $payment->kode_transaksi }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            font-size: 14px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #059669;
        }
        
        .header {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .kop-surat {
            text-align: center;
            padding: 20px;
            border-bottom: 3px solid #059669;
        }
        
        .kop-surat h2 {
            font-size: 20px;
            color: #059669;
            margin-bottom: 5px;
        }
        
        .kop-surat p {
            font-size: 12px;
            color: #666;
            margin: 2px 0;
        }
        
        .title {
            text-align: center;
            padding: 20px;
            background: #f0fdf4;
        }
        
        .title h3 {
            font-size: 18px;
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .content {
            padding: 30px;
        }
        
        .kode-box {
            background: #059669;
            color: white;
            padding: 15px;
            text-align: center;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        
        .kode-box .label {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        
        .kode-box .kode {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
        }
        
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .info-table td {
            padding: 8px 0;
            vertical-align: top;
        }
        
        .info-table td:first-child {
            width: 150px;
            font-weight: 600;
            color: #374151;
        }
        
        .info-table td:nth-child(2) {
            width: 20px;
            text-align: center;
        }
        
        .info-table td:last-child {
            color: #1f2937;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-lunas {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .total-box {
            background: #f0fdf4;
            border: 2px solid #059669;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        
        .total-box .label {
            font-size: 14px;
            color: #047857;
            margin-bottom: 5px;
        }
        
        .total-box .amount {
            font-size: 32px;
            font-weight: bold;
            color: #059669;
        }
        
        .footer {
            padding: 20px 30px;
            background: #f9fafb;
            border-top: 2px solid #e5e7eb;
        }
        
        .signature-area {
            margin-top: 40px;
            text-align: right;
        }
        
        .signature-area p {
            margin-bottom: 60px;
        }
        
        .signature-area .name {
            font-weight: bold;
            border-top: 2px solid #000;
            display: inline-block;
            padding-top: 5px;
            min-width: 200px;
        }
        
        .notes {
            font-size: 11px;
            color: #6b7280;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none !important;
            }
            
            .container {
                border: 2px solid #059669;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <h2>MASJID AT-TAQWA</h2>
            <p>Jl. Komplek Perumahan At-Taqwa</p>
            <p>Telp: (021) xxx-xxxx | Email: attaqwa@masjid.com</p>
        </div>
        
        <!-- Title -->
        <div class="title">
            <h3>Bukti Pembayaran Zakat Fitrah</h3>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Kode Transaksi -->
            <div class="kode-box">
                <div class="label">KODE TRANSAKSI</div>
                <div class="kode">{{ $payment->kode_transaksi }}</div>
            </div>
            
            <!-- Data Muzakki -->
            <table class="info-table">
                <tr>
                    <td>Nama Muzakki</td>
                    <td>:</td>
                    <td><strong>{{ $payment->muzakki->nama_lengkap }}</strong></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{ $payment->muzakki->nik }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $payment->muzakki->alamat }}</td>
                </tr>
                <tr>
                    <td>No. Telepon</td>
                    <td>:</td>
                    <td>{{ $payment->muzakki->no_telepon }}</td>
                </tr>
            </table>
            
            <hr style="margin: 20px 0; border: none; border-top: 1px solid #e5e7eb;">
            
            <!-- Detail Pembayaran -->
            <table class="info-table">
                <tr>
                    <td>Tanggal Bayar</td>
                    <td>:</td>
                    <td>{{ $payment->tanggal_bayar->format('d F Y, H:i') }} WIB</td>
                </tr>
                <tr>
                    <td>Jumlah Jiwa</td>
                    <td>:</td>
                    <td>{{ $payment->jumlah_jiwa }} jiwa</td>
                </tr>
                <tr>
                    <td>Jenis Pembayaran</td>
                    <td>:</td>
                    <td>{{ ucfirst($payment->jenis_bayar) }}</td>
                </tr>
                <tr>
                    <td>Nominal Per Jiwa</td>
                    <td>:</td>
                    <td>Rp {{ number_format($payment->nominal_per_jiwa, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Metode Pembayaran</td>
                    <td>:</td>
                    <td>{{ $payment->paymentMethod->nama_metode }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>
                        <span class="status-badge {{ $payment->status == 'lunas' ? 'status-lunas' : 'status-pending' }}">
                            {{ strtoupper($payment->status) }}
                        </span>
                    </td>
                </tr>
            </table>
            
            <!-- Total -->
            <div class="total-box">
                <div class="label">TOTAL PEMBAYARAN</div>
                <div class="amount">Rp {{ number_format($payment->total_bayar, 0, ',', '.') }}</div>
            </div>
            
            @if($payment->catatan)
            <div style="margin-top: 20px; padding: 15px; background: #f9fafb; border-radius: 8px;">
                <strong>Catatan:</strong><br>
                {{ $payment->catatan }}
            </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="signature-area">
                <p>{{ $payment->tanggal_bayar->format('d F Y') }}</p>
                <p>Petugas Penerima,</p>
                <br><br><br>
                <div class="name">
                    (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                </div>
            </div>
            
            <div class="notes">
                <p><strong>Catatan:</strong></p>
                <p>- Bukti pembayaran ini sah dan dapat digunakan sebagai bukti penyerahan zakat fitrah</p>
                <p>- Simpan bukti ini dengan baik</p>
                <p>- Untuk informasi lebih lanjut hubungi panitia zakat Masjid At-Taqwa</p>
                <p style="margin-top: 10px;">Dicetak pada: {{ now()->format('d F Y, H:i:s') }} WIB</p>
            </div>
        </div>
    </div>
    
    <!-- Button untuk close window (no-print) -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 30px; background: #059669; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-right: 10px;">
            <i class="fas fa-print"></i> Cetak
        </button>
        <button onclick="window.close()" style="padding: 10px 30px; background: #6b7280; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            Tutup
        </button>
    </div>
    
    <script>
        // Auto print saat halaman dibuka (optional)
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>
</html>