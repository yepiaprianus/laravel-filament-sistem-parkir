@extends('layouts.app')

@section('title', 'Edit Area Parkir')
@section('page_title', 'Edit Area Parkir')
@section('page_subtitle', 'Perbarui informasi lokasi dan kapasitas area parkir.')

@section('content')
<div class="max-w-xl bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form action="{{ route('admin.areas.update', $area->id_area) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Area Parkir</label>
            <input type="text" name="nama_area" value="{{ old('nama_area', $area->nama_area) }}" required
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kapasitas Maksimal (Minimal: {{ $area->terisi }} - kendaraan terparkir)</label>
            <input type="number" name="kapasitas" value="{{ old('kapasitas', $area->kapasitas) }}" min="{{ $area->terisi }}" required
                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 text-xs text-gray-600">
            Kendaraan saat ini terisi: <strong class="text-amber-600">{{ $area->terisi }}</strong> unit.
        </div>

        <div class="pt-4 border-t flex space-x-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow transition">
                Perbarui Area
            </button>
            <a href="{{ route('admin.areas.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg text-sm transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
