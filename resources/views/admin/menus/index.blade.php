@extends('layouts.admin')

@section('title', 'Menu Management')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#122C4F]">🍜 Menu Management</h1>
            <p class="text-slate-500 mt-2">Kelola semua menu kantin</p>
        </div>
        <a href="/admin/menus/create" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-bold transition">
            + Tambah Menu
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
                    <th class="text-left px-6 py-4 font-bold text-slate-700">Nama Menu</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-700">Kategori</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-700">Harga</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-700">Rating</th>
                    <th class="text-center px-6 py-4 font-bold text-slate-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menus as $menu)
                <tr class="border-b hover:bg-slate-50 transition">
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $menu->name }}</td>
                    <td class="px-6 py-4">
                        @if($menu->category === 'heavy')
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-bold">🍱 Makanan Berat</span>
                        @elseif($menu->category === 'beverage')
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">🥤 Minuman</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-bold">🍪 Cemilan</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-bold text-[#122C4F]">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if($menu->rating > 0)
                            <span class="text-yellow-500">⭐ {{ $menu->rating }}</span>
                        @else
                            <span class="text-slate-400">Belum ada rating</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="/admin/menus/{{ $menu->id }}/edit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold inline-block transition">
                            Edit
                        </a>
                        <form action="/admin/menus/{{ $menu->id }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus menu ini?')">
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
                        <i class="fa-solid fa-inbox text-3xl mb-3 block opacity-50"></i>
                        Belum ada menu
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $menus->links() }}
    </div>
</div>
@endsection
