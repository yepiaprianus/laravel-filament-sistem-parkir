@extends('layouts.app')

@section('title', 'Tambah Tarif Parkir')
@section('page_title', 'Tambah Tarif Parkir')
@section('page_subtitle', 'Tentukan tarif per jam untuk jenis kendaraan.')

@section('content')
<div class="max-w-xl bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form action="{{ route('admin.tarifs.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jenis Kendaraan</label>
            <select name="jenis_kendaraan" required class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="motor" {{ old('jenis_kendaraan') === 'motor' ? 'selected' : '' }}>Motor</option>
                <option value="mobil" {{ old('jenis_kendaraan') === 'mobil' ? 'selected' : '' }}>Mobil</option>
                <option value="lainnya" {{ old('jenis_kendaraan') === 'lainnya' ? 'selected' : '' }}>Lainnya (Truk/Bus)</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tarif Per Jam (Rupiah)</label>
            <input type="number" name="tarif_per_jam" value="{{ old('tarif_per_jam', 2000) }}" min="0" step="500" required
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div class="pt-4 border-t flex space-x-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow transition">
                Simpan Tarif
            </button>
            <a href="{{ route('admin.tarifs.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg text-sm transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
