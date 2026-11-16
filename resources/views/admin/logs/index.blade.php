@extends('layouts.app')

@section('title', 'Log Aktivitas')
@section('page_title', 'Audit & Log Aktivitas')
@section('page_subtitle', 'Pencatatan riwayat seluruh aktivitas penting pengguna dalam sistem.')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Filter -->
    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
        <form action="{{ route('admin.logs.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas..."
                   class="w-full sm:flex-1 px-3 py-1.5 text-xs rounded-lg border border-gray-300 focus:ring-1 focus:ring-indigo-500 outline-none">
            <div class="w-full sm:w-52">
                <select name="id_user" class="select2 select2-sm w-full" data-placeholder="Semua User">
                    <option value="">Semua User</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id_user }}" {{ request('id_user') == $u->id_user ? 'selected' : '' }}>
                            {{ $u->username }} ({{ $u->role }})
                        </option>
                    @endforeach
                </select>
            </div>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                   class="w-full sm:w-auto px-3 py-1.5 text-xs rounded-lg border border-gray-300 focus:ring-1 focus:ring-indigo-500 outline-none">
            <button type="submit" class="w-full sm:w-auto bg-gray-800 hover:bg-black text-white text-xs font-bold px-4 py-1.5 rounded-lg transition">
                Filter
            </button>
            @if(request()->hasAny(['search', 'id_user', 'tanggal']))
                <a href="{{ route('admin.logs.index') }}" class="text-xs text-gray-500 hover:text-gray-800 self-center">Reset</a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-gray-50 text-gray-400 uppercase border-b border-gray-100">
                <tr>
                    <th class="py-3 px-4">Waktu</th>
                    <th class="py-3 px-4">User</th>
                    <th class="py-3 px-4">Role</th>
                    <th class="py-3 px-4">Aktivitas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="py-3 px-4 font-mono text-gray-500 whitespace-nowrap">
                            {{ $log->waktu_aktivitas->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="py-3 px-4 font-bold text-gray-800">
                            {{ $log->user->nama_lengkap ?? 'User Dihapus' }} ({{ $log->user->username ?? '-' }})
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                {{ ($log->user->role ?? '') === 'admin' ? 'bg-amber-100 text-amber-800' : (($log->user->role ?? '') === 'petugas' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ $log->user->role ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-700">
                            {{ $log->aktivitas }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-400">Belum ada riwayat aktivitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">
        {{ $logs->links() }}
    </div>
</div>
@endsection
