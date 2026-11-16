@extends('layouts.app')

@section('title', 'Tambah Area Parkir')
@section('page_title', 'Tambah Area Parkir')
@section('page_subtitle', 'Tentukan nama lokasi area dan kapasitas parkir.')

@section('content')
<div class="max-w-xl bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form action="{{ route('admin.areas.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Area Parkir</label>
            <input type="text" name="nama_area" value="{{ old('nama_area') }}" required placeholder="Contoh: Area A - Depan"
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kapasitas Maksimal Kendaraan</label>
            <input type="number" name="kapasitas" value="{{ old('kapasitas', 50) }}" min="1" required
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div class="pt-4 border-t flex space-x-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow transition">
                Simpan Area
            </button>
            <a href="{{ route('admin.areas.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg text-sm transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
