@extends('layouts.app')

@section('title', 'Pembayaran Zakat Fitrah')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center">
            <div class="bg-emerald-100 p-4 rounded-full">
                <i class="fas fa-hand-holding-heart text-emerald-600 text-3xl"></i>
            </div>
            <div class="ml-4">
                <h1 class="text-2xl font-bold text-gray-800">Pembayaran Zakat Fitrah</h1>
                <p class="text-gray-600">Tunaikan zakat fitrah Anda dengan mudah</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form id="zakatForm">
            @csrf
            
            <!-- Data Muzakki -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-user mr-2"></i>Data Muzakki
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIK *</label>
                        <input type="text" name="nik" required maxlength="16"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="16 digit">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon *</label>
                        <input type="tel" name="no_telepon" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                        <textarea name="alamat" required rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Tanggungan *</label>
                        <input type="number" name="jumlah_tanggungan" required min="0" value="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <p class="text-xs text-gray-500 mt-1">Termasuk diri sendiri</p>
                    </div>
                </div>
            </div>

            <!-- Detail Pembayaran -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-calculator mr-2"></i>Detail Pembayaran
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pembayaran *</label>
                        <select name="jenis_bayar" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="uang">Uang (Tunai/Transfer)</option>
                            <option value="beras">Beras</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Jiwa *</label>
                        <input type="number" name="jumlah_jiwa" id="jumlahJiwa" required min="1" value="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nominal Per Jiwa *</label>
                        <input type="number" name="nominal_per_jiwa" id="nominalPerJiwa" required min="1000" value="35000"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <p class="text-xs text-gray-500 mt-1">Standar: Rp 35.000</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Bayar</label>
                        <input type="text" id="totalBayar" readonly
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 font-bold text-emerald-600"
                               value="Rp 35.000">
                    </div>
                </div>
            </div>

            <!-- Metode Pembayaran -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-credit-card mr-2"></i>Metode Pembayaran
                </h3>
                
                <div class="space-y-3">
                    @foreach($paymentMethods as $method)
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-emerald-500 transition">
                        <input type="radio" name="payment_method" value="{{ $method->id }}" required
                               class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                        <div class="ml-3 flex-1">
                            <div class="font-medium text-gray-900">{{ $method->nama_metode }}</div>
                            @if($method->keterangan)
                            <div class="text-sm text-gray-500">{{ $method->keterangan }}</div>
                            @endif
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" id="submitBtn"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-paper-plane mr-2"></i>
                    <span id="btnText">Bayar Zakat</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto calculate total
    function calculateTotal() {
        const jumlahJiwa = parseInt(document.getElementById('jumlahJiwa').value) || 0;
        const nominalPerJiwa = parseInt(document.getElementById('nominalPerJiwa').value) || 0;
        const total = jumlahJiwa * nominalPerJiwa;
        document.getElementById('totalBayar').value = 'Rp ' + total.toLocaleString('id-ID');
    }
    
    document.getElementById('jumlahJiwa').addEventListener('input', calculateTotal);
    document.getElementById('nominalPerJiwa').addEventListener('input', calculateTotal);
    
    // Form submit
    document.getElementById('zakatForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const originalText = btnText.textContent;
        
        // Disable button
        submitBtn.disabled = true;
        btnText.textContent = 'Memproses...';
        
        try {
            const formData = new FormData(this);
            const response = await fetch('/zakat/process', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                if (result.snap_token) {
                    // Pembayaran via Midtrans
                    snap.pay(result.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = '{{ url("/payment/success") }}/' + result.order_id;
                        },
                        onPending: function(result) {
                            window.location.href = '{{ url("/payment/success") }}/' + result.order_id;
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal!');
                            submitBtn.disabled = false;
                            btnText.textContent = originalText;
                        },
                        onClose: function() {
                            alert('Anda menutup popup pembayaran. Silakan coba lagi jika ingin melanjutkan.');
                            submitBtn.disabled = false;
                            btnText.textContent = originalText;
                        }
                    });
                } else {
                    // Pembayaran manual
                    alert(result.message);
                    window.location.href = '{{ url("/payment/success") }}/' + result.order_id;
                }
            } else {
                alert('Error: ' + result.message);
                submitBtn.disabled = false;
                btnText.textContent = originalText;
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
            submitBtn.disabled = false;
            btnText.textContent = originalText;
        }
    });
</script>
@endpush
@endsection