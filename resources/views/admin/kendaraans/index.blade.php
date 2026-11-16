@extends('layouts.app')

@section('title', 'Master Kendaraan')
@section('page_title', 'Master Data Kendaraan')
@section('page_subtitle', 'Pencatatan data kendaraan terdaftar.')

@section('page_action')
    <a href="{{ route('admin.kendaraans.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2 px-4 rounded-lg shadow transition">
        ➕ Tambah Kendaraan
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Filter -->
    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
        <form action="{{ route('admin.kendaraans.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari plat nomor atau pemilik..."
                   class="px-3 py-1.5 text-xs rounded-lg border border-gray-300 focus:ring-1 focus:ring-indigo-500 outline-none flex-1">
            <select name="jenis_kendaraan" class="px-3 py-1.5 text-xs rounded-lg border border-gray-300 focus:ring-1 focus:ring-indigo-500 outline-none">
                <option value="">Semua Jenis</option>
                <option value="motor" {{ request('jenis_kendaraan') === 'motor' ? 'selected' : '' }}>Motor</option>
                <option value="mobil" {{ request('jenis_kendaraan') === 'mobil' ? 'selected' : '' }}>Mobil</option>
                <option value="lainnya" {{ request('jenis_kendaraan') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            <button type="submit" class="bg-gray-800 hover:bg-black text-white text-xs font-bold px-4 py-1.5 rounded-lg transition">
                Filter
            </button>
            @if(request()->hasAny(['search', 'jenis_kendaraan']))
                <a href="{{ route('admin.kendaraans.index') }}" class="text-xs text-gray-500 hover:text-gray-800 self-center">Reset</a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-gray-50 text-gray-400 uppercase border-b border-gray-100">
                <tr>
                    <th class="py-3 px-4">ID</th>
                    <th class="py-3 px-4">Plat Nomor</th>
                    <th class="py-3 px-4">Jenis</th>
                    <th class="py-3 px-4">Warna</th>
                    <th class="py-3 px-4">Pemilik</th>
                    <th class="py-3 px-4">Didaftarkan Oleh</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($kendaraans as $k)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="py-3 px-4 font-mono text-gray-400">#{{ $k->id_kendaraan }}</td>
                        <td class="py-3 px-4 font-black tracking-wider text-gray-900 bg-gray-50/50 inline-block my-1 px-2 py-0.5 rounded border border-gray-200">
                            {{ $k->plat_nomor }}
                        </td>
                        <td class="py-3 px-4 uppercase font-semibold text-gray-700">
                            {{ $k->jenis_kendaraan }}
                        </td>
                        <td class="py-3 px-4 text-gray-500">{{ $k->warna ?? '-' }}</td>
                        <td class="py-3 px-4 font-medium text-gray-700">{{ $k->pemilik ?? 'Umum' }}</td>
                        <td class="py-3 px-4 text-gray-400">{{ $k->user->nama_lengkap ?? '-' }}</td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <a href="{{ route('admin.kendaraans.edit', $k->id_kendaraan) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Edit</a>
                            <form action="{{ route('admin.kendaraans.destroy', $k->id_kendaraan) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-900 font-semibold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">Belum ada data kendaraan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">
        {{ $kendaraans->links() }}
    </div>
</div>
@endsection
