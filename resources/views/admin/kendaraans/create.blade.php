@extends('layouts.app')

@section('title', 'Tambah Kendaraan')
@section('page_title', 'Tambah Data Kendaraan')
@section('page_subtitle', 'Pendaftaran master plat nomor dan identitas kendaraan.')

@section('content')
<div class="max-w-xl bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form action="{{ route('admin.kendaraans.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nomor Plat (Contoh: B 1234 ABC)</label>
            <input type="text" name="plat_nomor" value="{{ old('plat_nomor') }}" required placeholder="B 1234 ABC"
                   class="w-full px-3 py-2 text-sm font-mono uppercase rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jenis Kendaraan</label>
            <select name="jenis_kendaraan" required class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="motor" {{ old('jenis_kendaraan') === 'motor' ? 'selected' : '' }}>Motor</option>
                <option value="mobil" {{ old('jenis_kendaraan') === 'mobil' ? 'selected' : '' }}>Mobil</option>
                <option value="lainnya" {{ old('jenis_kendaraan') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Warna Kendaraan (Opsional)</label>
            <input type="text" name="warna" value="{{ old('warna') }}" placeholder="Contoh: Hitam"
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Pemilik / Pelanggan (Opsional)</label>
            <input type="text" name="pemilik" value="{{ old('pemilik') }}" placeholder="Contoh: Ahmad Subagyo"
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div class="pt-4 border-t flex space-x-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow transition">
                Simpan Kendaraan
            </button>
            <a href="{{ route('admin.kendaraans.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg text-sm transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
