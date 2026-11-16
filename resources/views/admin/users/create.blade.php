@extends('layouts.app')

@section('title', 'Tambah User Baru')
@section('page_title', 'Tambah User Baru')
@section('page_subtitle', 'Membuat akun pengguna baru untuk sistem.')

@section('content')
<div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" required
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Role</label>
            <select name="role" required class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Pengelola Master Data)</option>
                <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas (Operator Lapangan)</option>
                <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>Owner (Pemilik / Laporan)</option>
            </select>
        </div>

        <div class="pt-2">
            <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer">
                <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', '1') == '1' ? 'checked' : '' }}
                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span class="font-medium">Status Akun Aktif (Bisa Login)</span>
            </label>
        </div>

        <div class="pt-4 border-t flex space-x-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow transition">
                Simpan User
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg text-sm transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
