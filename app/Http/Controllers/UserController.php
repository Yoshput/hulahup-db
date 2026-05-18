<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // List all users
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->paginate(10);
        return view('admin.users.index', ['users' => $users]);
    }

    // Show edit form
    public function edit(User $user)
    {
        // Prevent editing other admins
        if ($user->role === 'admin') {
            return redirect('/admin/users')->with('error', 'Tidak bisa edit admin user!');
        }
        return view('admin.users.form', ['user' => $user]);
    }

    // Update user
    public function update(Request $request, User $user)
    {
        // Prevent updating other admins
        if ($user->role === 'admin') {
            return redirect('/admin/users')->with('error', 'Tidak bisa edit admin user!');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|digits_between:10,13',
            'address' => 'nullable|string|max:500',
            'role' => 'required|in:user,mahasiswa',
            'nim' => 'nullable|digits:12|unique:users,nim,' . $user->id,
        ]);

        $user->update($validated);

        return redirect('/admin/users')->with('success', 'User berhasil diperbarui!');
    }

    // Delete user
    public function destroy(User $user)
    {
        // Prevent deleting admins
        if ($user->role === 'admin') {
            return redirect('/admin/users')->with('error', 'Tidak bisa hapus admin user!');
        }

        $user->delete();
        return redirect('/admin/users')->with('success', 'User berhasil dihapus!');
    }

    // Change user role
    public function changeRole(Request $request, User $user)
    {
        // Prevent changing admin role
        if ($user->role === 'admin') {
            return redirect('/admin/users')->with('error', 'Tidak bisa ubah role admin!');
        }

        $validated = $request->validate([
            'role' => 'required|in:user,mahasiswa',
        ]);

        $user->update(['role' => $validated['role']]);

        return redirect('/admin/users')->with('success', 'Role user berhasil diubah!');
    }
}
