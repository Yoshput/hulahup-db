@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-[#122C4F]">✏️ Edit User</h1>
        <p class="text-slate-500 mt-2">Update informasi user: {{ $user->name }}</p>
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
        <form action="/admin/users/{{ $user->id }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Nama *</label>
                <input type="text" name="name" value="{{ $user->name }}" 
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Email *</label>
                <input type="email" name="email" value="{{ $user->email }}" 
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">NIM (Khusus Mahasiswa)</label>
                <input type="text" name="nim" value="{{ $user->nim }}" 
                       placeholder="Contoh: 312410032"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       maxlength="12">
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Phone</label>
                <input type="text" name="phone" value="{{ $user->phone }}" 
                       placeholder="Contoh: 081234567890"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Alamat</label>
                <textarea name="address" rows="3"
                          class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $user->address }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Role *</label>
                <select name="role" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>👤 User Biasa</option>
                    <option value="mahasiswa" {{ $user->role === 'mahasiswa' ? 'selected' : '' }}>🎓 Mahasiswa</option>
                </select>
                <p class="text-xs text-slate-500 mt-2">💡 Ubah role untuk memberikan privilege khusus</p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-bold transition">
                    ✓ Simpan Perubahan
                </button>
                <a href="/admin/users" class="bg-slate-500 hover:bg-slate-600 text-white px-6 py-3 rounded-lg font-bold transition">
                    ✕ Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
