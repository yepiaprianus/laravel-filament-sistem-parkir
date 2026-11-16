@extends('layouts.app')

@section('title', 'Kelola User')
@section('page_title', 'Manajemen Pengguna (User)')
@section('page_subtitle', 'Pengelolaan akun Admin, Petugas (Operator), dan Owner.')

@section('page_action')
    <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2 px-4 rounded-lg shadow transition flex items-center space-x-1">
        <span>➕ Tambah User Baru</span>
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Filter Bar -->
    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau username..."
                   class="px-3 py-1.5 text-xs rounded-lg border border-gray-300 focus:ring-1 focus:ring-indigo-500 outline-none flex-1">
            <select name="role" class="px-3 py-1.5 text-xs rounded-lg border border-gray-300 focus:ring-1 focus:ring-indigo-500 outline-none">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="petugas" {{ request('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                <option value="owner" {{ request('role') === 'owner' ? 'selected' : '' }}>Owner</option>
            </select>
            <button type="submit" class="bg-gray-800 hover:bg-black text-white text-xs font-bold px-4 py-1.5 rounded-lg transition">
                Filter
            </button>
            @if(request()->hasAny(['search', 'role']))
                <a href="{{ route('admin.users.index') }}" class="text-xs text-gray-500 hover:text-gray-800 self-center">Reset</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-gray-50 text-gray-400 uppercase border-b border-gray-100">
                <tr>
                    <th class="py-3 px-4">ID</th>
                    <th class="py-3 px-4">Nama Lengkap</th>
                    <th class="py-3 px-4">Username</th>
                    <th class="py-3 px-4">Role</th>
                    <th class="py-3 px-4 text-center">Status</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="py-3 px-4 font-mono text-gray-400">#{{ $user->id_user }}</td>
                        <td class="py-3 px-4 font-bold text-gray-800">{{ $user->nama_lengkap }}</td>
                        <td class="py-3 px-4 font-mono text-indigo-600">{{ $user->username }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                {{ $user->role === 'admin' ? 'bg-amber-100 text-amber-800' : ($user->role === 'petugas' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-block w-2 h-2 rounded-full mr-1 {{ $user->status_aktif ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                            <span class="{{ $user->status_aktif ? 'text-emerald-700 font-semibold' : 'text-gray-400' }}">
                                {{ $user->status_aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <a href="{{ route('admin.users.edit', $user->id_user) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Edit</a>
                            @if($user->id_user !== Auth::id())
                                <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-900 font-semibold">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400">Tidak ada user ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
</div>
@endsection
