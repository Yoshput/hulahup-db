@extends('layouts.admin')

@section('title', 'Vouchers Management')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#122C4F]">🎟️ Vouchers Management</h1>
            <p class="text-slate-500 mt-2">Kelola semua kode diskon dan promo</p>
        </div>
        <a href="/admin/vouchers/create" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-bold transition">
            + Buat Voucher
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-100 border-b">
                <tr>
                    <th class="text-left px-6 py-4 font-bold text-slate-700">Kode</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-700">Diskon</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-700">Valid</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-700">Penggunaan</th>
                    <th class="text-center px-6 py-4 font-bold text-slate-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $voucher)
                <tr class="border-b hover:bg-slate-50 transition">
                    <td class="px-6 py-4">
                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-lg font-bold">{{ $voucher->code }}</span>
                    </td>
                    <td class="px-6 py-4 font-bold text-[#122C4F]">
                        @if($voucher->discount_percentage)
                            {{ $voucher->discount_percentage }}% OFF
                        @else
                            Rp {{ number_format($voucher->discount_amount, 0, ',', '.') }}
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div>📅 {{ $voucher->valid_from->format('d/m/Y') }}</div>
                        <div class="text-slate-500">s/d {{ $voucher->valid_to->format('d/m/Y') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm">{{ $voucher->times_used }}/{{ $voucher->max_uses }}</span>
                        <div class="w-32 bg-slate-200 rounded-full h-2 mt-1">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ ($voucher->times_used / $voucher->max_uses) * 100 }}%"></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="/admin/vouchers/{{ $voucher->id }}/edit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold inline-block transition">
                            Edit
                        </a>
                        <form action="/admin/vouchers/{{ $voucher->id }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus voucher ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold transition">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-slate-500">
                        <i class="fa-solid fa-ticket text-3xl mb-3 block opacity-50"></i>
                        Belum ada voucher
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $vouchers->links() }}
    </div>
</div>
@endsection
