@extends('layouts.app')

@section('title', 'Tarif Parkir')
@section('page_title', 'Master Tarif Parkir')
@section('page_subtitle', 'Penetapan biaya parkir per jam berdasarkan jenis kendaraan.')

@section('page_action')
    <a href="{{ route('admin.tarifs.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2 px-4 rounded-lg shadow transition">
        ➕ Tambah Tarif Baru
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-gray-50 text-gray-400 uppercase border-b border-gray-100">
                <tr>
                    <th class="py-3 px-4">ID</th>
                    <th class="py-3 px-4">Jenis Kendaraan</th>
                    <th class="py-3 px-4 text-right">Tarif / Jam</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tarifs as $tarif)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="py-3 px-4 font-mono text-gray-400">#{{ $tarif->id_tarif }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center space-x-1.5 font-bold uppercase text-gray-800">
                                <span>
                                    {{ $tarif->jenis_kendaraan === 'motor' ? '🛵' : ($tarif->jenis_kendaraan === 'mobil' ? '🚗' : '🚐') }}
                                </span>
                                <span>{{ $tarif->jenis_kendaraan }}</span>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right font-bold text-emerald-600 text-sm">
                            Rp {{ number_format($tarif->tarif_per_jam, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <a href="{{ route('admin.tarifs.edit', $tarif->id_tarif) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Edit</a>
                            <form action="{{ route('admin.tarifs.destroy', $tarif->id_tarif) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus tarif ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-900 font-semibold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-400">Belum ada tarif parkir.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
