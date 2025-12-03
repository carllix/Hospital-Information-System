@extends('layouts.dashboard')

@section('title', 'Proses Pembayaran Lab - Kasir Lab')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        Proses Pembayaran Laboratorium
                    </h2>
                    <p class="opacity-90 text-sm mt-1">Tagihan Lab #{{ $tagihan->tagihan_id }}</p>
                </div>
                <div>
                    <a href="{{ route('kasir-lab.dashboard') }}" class="px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-md transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <x-toast type="error" :message="session('error')" />
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 h-full">
            <h5 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Detail Tagihan Laboratorium
            </h5>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-4 border-l-4 border-purple-500">
                <h6 class="font-semibold text-gray-900 mb-2">Informasi Pasien</h6>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <p class="text-gray-600">Pasien:</p>
                        <span class="font-medium text-gray-900">{{ $tagihan->pasien->nama ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <p class="text-gray-600">No. RM:</p>
                        <span class="font-medium text-gray-900">{{ $tagihan->pasien->no_rm ?? '-' }}</span>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-600">Jenis Tagihan:</p>
                        <span class="px-2 py-0.5 inline-flex text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                            LABORATORIUM
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-600">Tanggal:</p>
                        <span class="font-medium text-gray-900">{{ $tagihan->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
            
            <h6 class="font-semibold text-gray-800 mt-4 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Detail Pemeriksaan Lab:
            </h6>
            
            <div class="space-y-2">
                @foreach($tagihan->detailTagihan as $detail)
                    <div class="bg-white border border-gray-200 rounded-lg p-3 hover:shadow-sm transition-shadow">
                        <div class="flex justify-between items-center text-sm">
                            <div class="flex-1">
                                <strong class="text-gray-900">{{ $detail->deskripsi_item }}</strong>
                                @if($detail->catatan ?? false)
                                    <p class="text-xs text-gray-500">{{ $detail->catatan }}</p>
                                @endif
                            </div>
                            <div class="text-right ml-4">
                                <div class="text-xs text-gray-500">@ Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</div>
                                <strong class="text-purple-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6 p-4 rounded-lg text-center bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg">
                <div class="text-sm opacity-90 mb-1">TOTAL HARUS DIBAYAR</div>
                <div class="text-3xl font-bold mb-0">
                    Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h5 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v4m0 0h-6m6 0H9m10 0V9a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2h6m4-10a2 2 0 012 2v4m-2-6V9a2 2 0 00-2-2h-4a2 2 0 00-2 2v4"/></svg>
                Form Pembayaran Lab
            </h5>
            <form action="{{ route('kasir-lab.processPayment', $tagihan->tagihan_id) }}" method="POST" id="paymentForm">
                @csrf
                
                <input type="hidden" name="required_amount" value="{{ $tagihan->total_tagihan }}">
                
                <div class="mb-6">
                    <label for="jumlah_bayar" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V9a1 1 0 00-1-1H4a1 1 0 00-1 1v6a1 1 0 001 1h7a1 1 0 001-1v-1m0-8h6a1 1 0 011 1v6a1 1 0 01-1 1h-6"/></svg>
                        Jumlah Uang Diterima <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 font-medium">Rp</span>
                        <input type="number" 
                               step="1000" 
                               name="jumlah_bayar" 
                               id="jumlah_bayar" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg pl-10 focus:ring-2 focus:ring-purple-500 focus:border-purple-500" 
                               required 
                               min="{{ $tagihan->total_tagihan }}" 
                               value="{{ $tagihan->total_tagihan }}"
                               placeholder="Masukkan jumlah uang yang diterima">
                    </div>
                    <small class="text-gray-500 mt-1 block">
                        <svg class="w-3 h-3 inline mr-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Minimal: Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}
                    </small>
                    <div class="text-red-500 text-sm mt-1 hidden" id="validationFeedback">Jumlah pembayaran harus minimal sama dengan total tagihan</div>
                </div>
                
                <div id="kembalianDisplay" class="hidden transition-all duration-300 transform scale-0 origin-center mb-6">
                    <div class="bg-green-500 text-white p-4 rounded-lg text-center shadow-lg">
                        <div class="text-sm mb-1 opacity-90">KEMBALIAN</div>
                        <div id="kembalianAmount" class="text-2xl font-bold">Rp 0</div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <svg class="w-4 h-4 inline mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Metode Pembayaran <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="grid grid-cols-1 gap-3">
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-300 transition-colors">
                            <input type="radio" name="metode_pembayaran" value="Tunai" required class="w-4 h-4 text-purple-600 focus:ring-purple-500">
                            <div class="ml-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V9a1 1 0 00-1-1H4a1 1 0 00-1 1v6a1 1 0 001 1h7a1 1 0 001-1v-1m0-8h6a1 1 0 011 1v6a1 1 0 01-1 1h-6"/></svg>
                                <span class="font-medium">Tunai</span>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-300 transition-colors">
                            <input type="radio" name="metode_pembayaran" value="Debit" required class="w-4 h-4 text-purple-600 focus:ring-purple-500">
                            <div class="ml-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                <span class="font-medium">Kartu Debit</span>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-300 transition-colors">
                            <input type="radio" name="metode_pembayaran" value="Transfer" required class="w-4 h-4 text-purple-600 focus:ring-purple-500">
                            <div class="ml-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18M13 16l4-4m0 0l-4-4m4 4H3"/></svg>
                                <span class="font-medium">Transfer Bank</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Catatan (Opsional)
                    </label>
                    <textarea name="catatan" id="catatan" rows="2" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500 resize-none"
                              placeholder="Catatan khusus untuk pembayaran lab ini..."></textarea>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <h6 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v4m0 0h-6m6 0H9m10 0V9a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2h6m4-10a2 2 0 012 2v4m-2-6V9a2 2 0 00-2-2h-4a2 2 0 00-2 2v4"/></svg>
                        Ringkasan Perhitungan Lab
                    </h6>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Tagihan Lab:</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Uang Diterima:</span>
                            <span class="font-bold text-gray-900" id="uangDiterimaDisplay">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</span>
                        </div>
                        <hr class="my-2 border-gray-300">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kembalian:</span>
                            <strong class="text-lg text-green-600" id="kembalianCalculation">Rp 0</strong>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-purple-600 text-white font-semibold py-3 rounded-lg hover:bg-purple-700 transition-colors shadow-md disabled:bg-gray-400" id="submitBtn" disabled>
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Proses Pembayaran Lab Lunas</span>
                </button>
                
                <a href="{{ route('kasir-lab.dashboard') }}" class="mt-3 block w-full text-center px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const totalTagihan = {{ $tagihan->total_tagihan }};
    const jumlahBayarInput = document.getElementById('jumlah_bayar');
    const kembalianDisplay = document.getElementById('kembalianDisplay');
    const kembalianAmount = document.getElementById('kembalianAmount');
    const kembalianCalculation = document.getElementById('kembalianCalculation');
    const uangDiterimaDisplay = document.getElementById('uangDiterimaDisplay');
    const submitBtn = document.getElementById('submitBtn');
    const paymentForm = document.getElementById('paymentForm');
    const validationFeedback = document.getElementById('validationFeedback');
    
    function formatRupiah(number) {
        return 'Rp ' + Math.max(0, number).toLocaleString('id-ID');
    }
    
    function updateCalculation() {
        const jumlahBayar = parseFloat(jumlahBayarInput.value) || 0;
        const kembalian = Math.max(0, jumlahBayar - totalTagihan);
        const metodePembayaran = document.querySelector('input[name="metode_pembayaran"]:checked');
        
        // Update displays
        kembalianAmount.textContent = formatRupiah(kembalian);
        kembalianCalculation.textContent = formatRupiah(kembalian);
        uangDiterimaDisplay.textContent = formatRupiah(jumlahBayar);
        
        // Show/hide change display
        if (kembalian > 0) {
            kembalianDisplay.classList.remove('hidden', 'scale-0');
            kembalianDisplay.classList.add('scale-100');
        } else {
            kembalianDisplay.classList.remove('scale-100');
            kembalianDisplay.classList.add('scale-0');
            setTimeout(() => {
                if(kembalian <= 0) kembalianDisplay.classList.add('hidden');
            }, 300);
        }
        
        // Validation and button status
        if (jumlahBayar < totalTagihan) {
            jumlahBayarInput.classList.add('border-red-500');
            validationFeedback.classList.remove('hidden');
            submitBtn.disabled = true;
        } else {
            jumlahBayarInput.classList.remove('border-red-500');
            validationFeedback.classList.add('hidden');
            submitBtn.disabled = !metodePembayaran;
        }
        
        // Final check on method selection
        if (jumlahBayar >= totalTagihan && metodePembayaran) {
            submitBtn.disabled = false;
        }
    }
    
    jumlahBayarInput.addEventListener('input', updateCalculation);
    document.querySelectorAll('input[name="metode_pembayaran"]').forEach(radio => {
        radio.addEventListener('change', updateCalculation);
    });
    
    // Initial calculation on load
    updateCalculation();

    // Form submission confirmation
    paymentForm.addEventListener('submit', function(e) {
        const jumlahBayar = parseFloat(jumlahBayarInput.value) || 0;
        const metodePembayaran = document.querySelector('input[name="metode_pembayaran"]:checked')?.value;
        const kembalian = Math.max(0, jumlahBayar - totalTagihan);
        
        if (jumlahBayar < totalTagihan || !metodePembayaran) {
            e.preventDefault();
            alert('Mohon lengkapi data dan pastikan jumlah bayar mencukupi.');
            return false;
        }
        
        let confirmMessage = `Konfirmasi Pembayaran Laboratorium:\n\n`;
        confirmMessage += `Total Tagihan Lab: ${formatRupiah(totalTagihan)}\n`;
        confirmMessage += `Uang Diterima: ${formatRupiah(jumlahBayar)}\n`;
        confirmMessage += `Kembalian: ${formatRupiah(kembalian)}\n`;
        confirmMessage += `Metode Pembayaran: ${metodePembayaran}\n\n`;
        confirmMessage += `Apakah data pembayaran lab sudah benar?`;
        
        if (!confirm(confirmMessage)) {
            e.preventDefault();
            return false;
        }
        
        submitBtn.disabled = true;
        submitBtn.querySelector('span').textContent = 'Memproses...';
        submitBtn.querySelector('svg').outerHTML = '<svg class="w-5 h-5 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 13m4.356 2H3m10-7h5.414M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/></svg>';
    });
});
</script>
@endpush