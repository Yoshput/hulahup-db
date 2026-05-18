@extends('layouts.admin')

@section('title', $menu ? 'Edit Menu' : 'Tambah Menu')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-[#122C4F]">
            {{ $menu ? 'Edit Menu' : '➕ Tambah Menu Baru' }}
        </h1>
        <p class="text-slate-500 mt-2">{{ $menu ? 'Perbarui informasi menu' : 'Tambahkan menu baru ke kantin' }}</p>
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
        <form action="{{ $menu ? '/admin/menus/' . $menu->id : '/admin/menus' }}" method="POST">
            @csrf
            @if($menu)
                @method('PUT')
            @endif

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Nama Menu *</label>
                <input type="text" name="name" value="{{ $menu?->name ?? old('name') }}" 
                       placeholder="Contoh: Mie Ayam Bakso"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Kategori *</label>
                <select name="category" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Pilih Kategori</option>
                    <option value="heavy" {{ $menu?->category === 'heavy' ? 'selected' : '' }}>🍱 Makanan Berat</option>
                    <option value="beverage" {{ $menu?->category === 'beverage' ? 'selected' : '' }}>🥤 Minuman</option>
                    <option value="snack" {{ $menu?->category === 'snack' ? 'selected' : '' }}>🍪 Cemilan</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Harga *</label>
                <div class="flex items-center">
                    <span class="text-slate-600 font-bold mr-3">Rp</span>
                    <input type="number" name="price" value="{{ $menu?->price ?? old('price') }}" 
                           placeholder="0"
                           class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           min="0" step="0.01" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Deskripsi</label>
                <textarea name="description" rows="4" placeholder="Deskripsi menu (opsional)"
                          class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $menu?->description ?? old('description') }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">URL Gambar</label>
                <input type="text" name="image_url" value="{{ $menu?->image_url ?? old('image_url') }}" 
                       placeholder="Contoh: images/mie-ayam.png"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Rating</label>
                <input type="number" name="rating" value="{{ $menu?->rating ?? old('rating') }}" 
                       placeholder="0"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       min="0" max="5" step="0.1">
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-bold transition">
                    {{ $menu ? '✓ Perbarui' : '✓ Tambahkan' }}
                </button>
                <a href="/admin/menus" class="bg-slate-500 hover:bg-slate-600 text-white px-6 py-3 rounded-lg font-bold transition">
                    ✕ Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
