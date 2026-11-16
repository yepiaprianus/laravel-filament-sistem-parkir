@extends('layouts.app')

@section('title', 'Edit Tarif Parkir')
@section('page_title', 'Edit Tarif: ' . ucfirst($tarif->jenis_kendaraan))
@section('page_subtitle', 'Perbarui tarif per jam kendaraan.')

@section('content')
<div class="max-w-xl bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form action="{{ route('admin.tarifs.update', $tarif->id_tarif) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jenis Kendaraan</label>
            <input type="text" value="{{ ucfirst($tarif->jenis_kendaraan) }}" disabled
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tarif Per Jam (Rupiah)</label>
            <input type="number" name="tarif_per_jam" value="{{ old('tarif_per_jam', $tarif->tarif_per_jam) }}" min="0" step="500" required
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div class="pt-4 border-t flex space-x-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow transition">
                Perbarui Tarif
            </button>
            <a href="{{ route('admin.tarifs.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg text-sm transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
