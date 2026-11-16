<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest('id_user')->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:50', 'unique:tb_user,username'],
            'password' => ['required', 'string', 'min:4', 'max:100'],
            'role' => ['required', Rule::in(['admin', 'petugas', 'owner'])],
            'status_aktif' => ['nullable', 'boolean'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status_aktif'] = $request->has('status_aktif') ? 1 : 0;

        $user = User::create($validated);

        LogAktivitas::catat(Auth::id(), "Menambahkan user baru: {$user->username} ({$user->role})");

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:50', Rule::unique('tb_user', 'username')->ignore($user->id_user, 'id_user')],
            'password' => ['nullable', 'string', 'min:4', 'max:100'],
            'role' => ['required', Rule::in(['admin', 'petugas', 'owner'])],
            'status_aktif' => ['nullable', 'boolean'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['status_aktif'] = $request->has('status_aktif') ? 1 : 0;

        $user->update($validated);

        LogAktivitas::catat(Auth::id(), "Mengubah data user: {$user->username}");

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id_user === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $username = $user->username;
        $user->delete();

        LogAktivitas::catat(Auth::id(), "Menghapus user: {$username}");

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
