@extends('layouts.app')

@section('title', 'Sedekah')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center">
            <div class="bg-pink-100 p-4 rounded-full">
                <i class="fas fa-heart text-pink-600 text-3xl"></i>
            </div>
            <div class="ml-4">
                <h1 class="text-2xl font-bold text-gray-800">Sedekah</h1>
                <p class="text-gray-600">Berbagi kebaikan dengan sesama</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form id="sedekahForm">
            @csrf
            
            <!-- Toggle Anonim -->
            <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_anonim" id="isAnonim"
                           class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                    <span class="ml-3 text-gray-700 font-medium">
                        <i class="fas fa-user-secret mr-2"></i>Donasi sebagai Anonim
                    </span>
                </label>
                <p class="text-sm text-gray-600 mt-2 ml-8">Nama Anda tidak akan ditampilkan</p>
            </div>

            <!-- Data Donatur -->
            <div class="mb-6" id="donaturSection">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-user mr-2"></i>Data Donatur
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="nama_donatur" id="namaDonatur" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                        <input type="tel" name="no_telepon" id="noTelepon"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>
                </div>
            </div>

            <!-- Detail Sedekah -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-donate mr-2"></i>Detail Sedekah
                </h3>
                
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nominal Sedekah *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="number" name="nominal" id="nominal" required min="1000" 
                                   class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                                   placeholder="Minimal Rp 1.000">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Minimal sedekah Rp 1.000</p>
                    </div>
                    
                    <!-- Quick Amount Buttons -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nominal Cepat</label>
                        <div class="grid grid-cols-3 md:grid-cols-6 gap-2">
                            <button type="button" onclick="setNominal(10000)" 
                                    class="px-3 py-2 border-2 border-gray-300 rounded-lg hover:border-pink-500 hover:bg-pink-50 transition text-sm">
                                10K
                            </button>
                            <button type="button" onclick="setNominal(20000)" 
                                    class="px-3 py-2 border-2 border-gray-300 rounded-lg hover:border-pink-500 hover:bg-pink-50 transition text-sm">
                                20K
                            </button>
                            <button type="button" onclick="setNominal(50000)" 
                                    class="px-3 py-2 border-2 border-gray-300 rounded-lg hover:border-pink-500 hover:bg-pink-50 transition text-sm">
                                50K
                            </button>
                            <button type="button" onclick="setNominal(100000)" 
                                    class="px-3 py-2 border-2 border-gray-300 rounded-lg hover:border-pink-500 hover:bg-pink-50 transition text-sm">
                                100K
                            </button>
                            <button type="button" onclick="setNominal(200000)" 
                                    class="px-3 py-2 border-2 border-gray-300 rounded-lg hover:border-pink-500 hover:bg-pink-50 transition text-sm">
                                200K
                            </button>
                            <button type="button" onclick="setNominal(500000)" 
                                    class="px-3 py-2 border-2 border-gray-300 rounded-lg hover:border-pink-500 hover:bg-pink-50 transition text-sm">
                                500K
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan / Pesan Doa</label>
                        <textarea name="catatan" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                                  placeholder="Tuliskan pesan atau doa Anda..."></textarea>
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
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-pink-500 transition">
                        <input type="radio" name="payment_method" value="{{ $method->id }}" required
                               class="w-4 h-4 text-pink-600 focus:ring-pink-500">
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
                        class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-8 rounded-lg transition duration-200 flex items-center">
                    <i class="fas fa-heart mr-2"></i>
                    <span id="btnText">Sedekah Sekarang</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Toggle anonim
    document.getElementById('isAnonim').addEventListener('change', function() {
        const donaturSection = document.getElementById('donaturSection');
        const inputs = donaturSection.querySelectorAll('input');
        const namaInput = document.querySelector('input[name="nama_donatur"]');
        
        if (this.checked) {
            donaturSection.style.opacity = '0.5';
            inputs.forEach(input => {
                input.disabled = true;
                input.removeAttribute('required');
            });
            // Set default nama untuk anonim
            if (namaInput) {
                namaInput.value = 'Hamba Allah';
            }
        } else {
            donaturSection.style.opacity = '1';
            inputs.forEach(input => {
                input.disabled = false;
            });
            const namaField = document.querySelector('input[name="nama_donatur"]');
            if (namaField) {
                namaField.setAttribute('required', 'required');
                namaField.value = '';
            }
        }
    });
    
    // Set nominal quick button
    function setNominal(amount) {
        document.getElementById('nominal').value = amount;
    }
    
    // Form submit
    document.getElementById('sedekahForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const originalText = btnText.textContent;
        
        // Disable button
        submitBtn.disabled = true;
        btnText.textContent = 'Memproses...';
        
        try {
            const formData = new FormData(this);
            
            // Jika anonim, set nama default
            if (formData.get('is_anonim') === 'on' || formData.get('is_anonim') === '1') {
                formData.set('nama_donatur', 'Hamba Allah');
            }
            
            const response = await fetch('/sedekah/process', {
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
                            window.location.href = '/payment/success/' + result.order_id;
                        },
                        onPending: function(result) {
                            window.location.href = '/payment/success/' + result.order_id;
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
                    window.location.href = '/payment/success/' + result.order_id;
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