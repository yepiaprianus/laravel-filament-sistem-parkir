@extends('layouts.app')

@section('title', 'Edit Kendaraan')
@section('page_title', 'Edit Kendaraan: ' . $kendaraan->plat_nomor)
@section('page_subtitle', 'Perbarui data master kendaraan.')

@section('content')
<div class="max-w-xl bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form action="{{ route('admin.kendaraans.update', $kendaraan->id_kendaraan) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nomor Plat</label>
            <input type="text" name="plat_nomor" value="{{ old('plat_nomor', $kendaraan->plat_nomor) }}" required
                   class="w-full px-3 py-2 text-sm font-mono uppercase rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jenis Kendaraan</label>
            <select name="jenis_kendaraan" required class="select2 w-full" data-placeholder="Pilih Jenis Kendaraan">
                <option value="motor" {{ old('jenis_kendaraan', $kendaraan->jenis_kendaraan) === 'motor' ? 'selected' : '' }}>Motor</option>
                <option value="mobil" {{ old('jenis_kendaraan', $kendaraan->jenis_kendaraan) === 'mobil' ? 'selected' : '' }}>Mobil</option>
                <option value="lainnya" {{ old('jenis_kendaraan', $kendaraan->jenis_kendaraan) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Warna Kendaraan</label>
            <input type="text" name="warna" value="{{ old('warna', $kendaraan->warna) }}"
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Pemilik</label>
            <input type="text" name="pemilik" value="{{ old('pemilik', $kendaraan->pemilik) }}"
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div class="pt-4 border-t flex space-x-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow transition">
                Perbarui Kendaraan
            </button>
            <a href="{{ route('admin.kendaraans.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg text-sm transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
