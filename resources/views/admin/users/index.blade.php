@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-[#122C4F]">👥 Users Management</h1>
        <p class="text-slate-500 mt-2">Kelola semua pengguna aplikasi</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-100 border-b">
                <tr>
                    <th class="text-left px-6 py-4 font-bold text-slate-700">Nama</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-700">Email</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-700">Role</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-700">NIM</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-700">Phone</th>
                    <th class="text-center px-6 py-4 font-bold text-slate-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b hover:bg-slate-50 transition">
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        @if($user->role === 'mahasiswa')
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">🎓 Mahasiswa</span>
                        @else
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-bold">👤 User</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ $user->nim ?? '-' }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $user->phone ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <a href="/admin/users/{{ $user->id }}/edit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold inline-block transition">
                            Edit
                        </a>
                        <form action="/admin/users/{{ $user->id }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus user ini?')">
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
                    <td colspan="6" class="text-center py-8 text-slate-500">
                        <i class="fa-solid fa-users text-3xl mb-3 block opacity-50"></i>
                        Belum ada user
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
