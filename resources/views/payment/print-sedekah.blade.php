<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Penerimaan Sedekah - {{ $payment->kode_transaksi }}</title>
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
            border: 2px solid #ec4899;
        }
        
        .header {
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
            color: white;
            padding: 20px;
            text-align: center;
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
            border-bottom: 3px solid #ec4899;
        }
        
        .kop-surat h2 {
            font-size: 20px;
            color: #ec4899;
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
            background: #fdf2f8;
        }
        
        .title h3 {
            font-size: 18px;
            color: #db2777;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .content {
            padding: 30px;
        }
        
        .kode-box {
            background: #ec4899;
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
        
        .status-diterima {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .total-box {
            background: #fdf2f8;
            border: 2px solid #ec4899;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        
        .total-box .label {
            font-size: 14px;
            color: #db2777;
            margin-bottom: 5px;
        }
        
        .total-box .amount {
            font-size: 32px;
            font-weight: bold;
            color: #ec4899;
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
        
        .doa-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            font-style: italic;
            color: #92400e;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .no-print {
                display: none !important;
            }
            
            .container {
                border: 2px solid #ec4899;
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
            <h3>Bukti Penerimaan Sedekah</h3>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Kode Transaksi -->
            <div class="kode-box">
                <div class="label">KODE TRANSAKSI</div>
                <div class="kode">{{ $payment->kode_transaksi }}</div>
            </div>
            
            <!-- Data Donatur -->
            <table class="info-table">
                <tr>
                    <td>Nama Donatur</td>
                    <td>:</td>
                    <td><strong>{{ $payment->display_name }}</strong></td>
                </tr>
                @if(!$payment->is_anonim)
                    @if($payment->no_telepon)
                    <tr>
                        <td>No. Telepon</td>
                        <td>:</td>
                        <td>{{ $payment->no_telepon }}</td>
                    </tr>
                    @endif
                    @if($payment->email)
                    <tr>
                        <td>Email</td>
                        <td>:</td>
                        <td>{{ $payment->email }}</td>
                    </tr>
                    @endif
                @endif
            </table>
            
            <hr style="margin: 20px 0; border: none; border-top: 1px solid #e5e7eb;">
            
            <!-- Detail Pembayaran -->
            <table class="info-table">
                <tr>
                    <td>Tanggal Donasi</td>
                    <td>:</td>
                    <td>{{ $payment->tanggal_donasi->format('d F Y, H:i') }} WIB</td>
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
                        <span class="status-badge {{ $payment->status == 'diterima' ? 'status-diterima' : 'status-pending' }}">
                            {{ strtoupper($payment->status) }}
                        </span>
                    </td>
                </tr>
            </table>
            
            <!-- Total -->
            <div class="total-box">
                <div class="label">NOMINAL SEDEKAH</div>
                <div class="amount">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</div>
            </div>
            
            @if($payment->catatan)
            <div style="margin-top: 20px; padding: 15px; background: #f9fafb; border-radius: 8px;">
                <strong>Pesan dari Donatur:</strong><br>
                <em>"{{ $payment->catatan }}"</em>
            </div>
            @endif
            
            <!-- Doa -->
            <div class="doa-box">
                <p style="margin-bottom: 10px;">
                    <strong>Doa untuk Donatur:</strong>
                </p>
                <p>
                    "Jazakallahu khairan katsiran. Semoga Allah membalas kebaikan Anda dengan kebaikan yang lebih banyak, 
                    dan menjadikan sedekah ini sebagai amal jariyah yang terus mengalir pahalanya."
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="signature-area">
                <p>{{ $payment->tanggal_donasi->format('d F Y') }}</p>
                <p>Petugas Penerima,</p>
                <br><br><br>
                <div class="name">
                    (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                </div>
            </div>
            
            <div class="notes">
                <p><strong>Catatan:</strong></p>
                <p>- Bukti ini sah dan dapat digunakan sebagai bukti penerimaan sedekah</p>
                <p>- Simpan bukti ini dengan baik</p>
                <p>- Untuk informasi lebih lanjut hubungi panitia Masjid At-Taqwa</p>
                <p style="margin-top: 10px;">Dicetak pada: {{ now()->format('d F Y, H:i:s') }} WIB</p>
            </div>
        </div>
    </div>
    
    <!-- Button untuk close window (no-print) -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 30px; background: #ec4899; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-right: 10px;">
            <i class="fas fa-print"></i> Cetak
        </button>
        <button onclick="window.close()" style="padding: 10px 30px; background: #6b7280; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            Tutup
        </button>
    </div>
</body>
</html>