@extends('layouts.app')

@section('title', 'Area Parkir')
@section('page_title', 'Area & Kapasitas Parkir')
@section('page_subtitle', 'Manajemen lokasi area parkir dan pemantauan kapasitas.')

@section('page_action')
    <a href="{{ route('admin.areas.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2 px-4 rounded-lg shadow transition inline-flex items-center space-x-1.5">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
        <span>Tambah Area Parkir</span>
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-gray-50 text-gray-400 uppercase border-b border-gray-100">
                <tr>
                    <th class="py-3 px-4">ID</th>
                    <th class="py-3 px-4">Nama Area</th>
                    <th class="py-3 px-4 text-center">Kapasitas</th>
                    <th class="py-3 px-4 text-center">Terisi</th>
                    <th class="py-3 px-4 text-center">Sisa</th>
                    <th class="py-3 px-4 text-center">Status</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($areas as $area)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="py-3 px-4 font-mono text-gray-400">#{{ $area->id_area }}</td>
                        <td class="py-3 px-4 font-bold text-gray-800">{{ $area->nama_area }}</td>
                        <td class="py-3 px-4 text-center font-bold">{{ $area->kapasitas }}</td>
                        <td class="py-3 px-4 text-center text-amber-600 font-bold">{{ $area->terisi }}</td>
                        <td class="py-3 px-4 text-center text-emerald-600 font-bold">{{ $area->sisaKapasitas() }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $area->isPenuh() ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $area->isPenuh() ? 'Penuh' : 'Tersedia' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <a href="{{ route('admin.areas.edit', $area->id_area) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Edit</a>
                            <form action="{{ route('admin.areas.destroy', $area->id_area) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus area ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-900 font-semibold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">Belum ada area parkir terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">
        {{ $areas->links() }}
    </div>
</div>
@endsection
