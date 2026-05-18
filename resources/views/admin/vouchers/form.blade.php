@extends('layouts.admin')

@section('title', $voucher ? 'Edit Voucher' : 'Buat Voucher')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-[#122C4F]">
            {{ $voucher ? '✏️ Edit Voucher' : '🎟️ Buat Voucher Baru' }}
        </h1>
        <p class="text-slate-500 mt-2">{{ $voucher ? 'Perbarui informasi voucher' : 'Buat kode diskon baru untuk pelanggan' }}</p>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
        <strong>Error:</strong>
        <ul class="list-disc ml-5 mt-2">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-8 max-w-2xl">
        <form action="{{ $voucher ? '/admin/vouchers/' . $voucher->id : '/admin/vouchers' }}" method="POST">
            @csrf
            @if($voucher)
                @method('PUT')
            @endif

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Kode Voucher *</label>
                <input type="text" name="code" value="{{ $voucher?->code ?? old('code') }}" 
                       placeholder="Contoh: PROMO2024"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase"
                       required {{ $voucher ? 'disabled' : '' }}>
                @if($voucher)
                    <p class="text-xs text-slate-500 mt-2">⚠️ Kode tidak bisa diubah setelah dibuat</p>
                @endif
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Deskripsi</label>
                <textarea name="description" rows="3" placeholder="Contoh: Diskon khusus mahasiswa Tel-U"
                          class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $voucher?->description ?? old('description') }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Tipe Diskon *</label>
                <div class="flex gap-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="discount_type" value="percentage" class="mr-3" 
                               {{ ($voucher && $voucher->discount_percentage) || old('discount_type') === 'percentage' ? 'checked' : 'checked' }}>
                        <span>Persen (%)</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="discount_type" value="amount" class="mr-3"
                               {{ $voucher && $voucher->discount_amount ? 'checked' : '' }}>
                        <span>Nominal (Rp)</span>
                    </label>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Nilai Diskon *</label>
                <div class="flex items-center">
                    <input type="number" name="discount_value" value="{{ $voucher ? ($voucher->discount_percentage ?? $voucher->discount_amount) : old('discount_value') }}" 
                           placeholder="0"
                           class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           min="0" step="0.01" required>
                    <span class="ml-3 text-slate-600 font-bold" id="discountUnit">%</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-slate-700 font-bold mb-2">Valid Dari *</label>
                    <input type="date" name="valid_from" value="{{ $voucher?->valid_from->format('Y-m-d') ?? old('valid_from') }}" 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>
                <div>
                    <label class="block text-slate-700 font-bold mb-2">Valid Sampai *</label>
                    <input type="date" name="valid_to" value="{{ $voucher?->valid_to->format('Y-m-d') ?? old('valid_to') }}" 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Max Penggunaan *</label>
                <input type="number" name="max_uses" value="{{ $voucher?->max_uses ?? old('max_uses') ?? 100 }}" 
                       placeholder="Contoh: 100"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       min="1" required>
                <p class="text-xs text-slate-500 mt-2">Berapa kali voucher ini bisa dipakai</p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-bold transition">
                    {{ $voucher ? '✓ Perbarui' : '✓ Buat' }}
                </button>
                <a href="/admin/vouchers" class="bg-slate-500 hover:bg-slate-600 text-white px-6 py-3 rounded-lg font-bold transition">
                    ✕ Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('input[name="discount_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const unit = document.getElementById('discountUnit');
            unit.textContent = this.value === 'percentage' ? '%' : 'Rp';
        });
    });
</script>
@endsection
