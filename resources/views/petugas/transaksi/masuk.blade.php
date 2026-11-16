@extends('layouts.app')

@section('title', 'Kendaraan Masuk')
@section('page_title', 'Catat Kendaraan Masuk')
@section('page_subtitle', 'Masukkan nomor plat, pilih jenis kendaraan, dan tentukan area parkir.')

@section('content')
<div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form action="{{ route('petugas.transaksi.masuk.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nomor Plat Kendaraan</label>
            <input type="text" name="plat_nomor" value="{{ old('plat_nomor') }}" required autofocus placeholder="Contoh: B 1234 ABC"
                   class="w-full px-4 py-3 text-lg font-black font-mono uppercase tracking-wider rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jenis Kendaraan</label>
                <select name="jenis_kendaraan" required class="select2 w-full" data-placeholder="Pilih Jenis Kendaraan">
                    <option value=""></option>
                    @foreach($tarifs as $tarif)
                        <option value="{{ $tarif->jenis_kendaraan }}" {{ old('jenis_kendaraan') === $tarif->jenis_kendaraan ? 'selected' : '' }}>
                            {{ ucfirst($tarif->jenis_kendaraan) }} (Rp {{ number_format($tarif->tarif_per_jam, 0, ',', '.') }}/jam)
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Area Parkir Tujuan</label>
                <select name="id_area" required class="select2 w-full" data-placeholder="Pilih Area Parkir">
                    <option value=""></option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id_area }}" {{ $area->isPenuh() ? 'disabled' : '' }} {{ old('id_area') == $area->id_area ? 'selected' : '' }}>
                            {{ $area->nama_area }} (Sisa: {{ $area->sisaKapasitas() }}) {{ $area->isPenuh() ? '- [PENUH]' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Warna Kendaraan (Opsional)</label>
                <input type="text" name="warna" value="{{ old('warna') }}" placeholder="Contoh: Merah / Hitam"
                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Pemilik / Pengemudi (Opsional)</label>
                <input type="text" name="pemilik" value="{{ old('pemilik') }}" placeholder="Contoh: Tamu Umum"
                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
        </div>

        <div class="pt-4 border-t flex space-x-2">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-lg text-sm shadow-md transition flex items-center space-x-2">
                <span>Cetak Tiket & Simpan Transaksi</span>
            </button>
            <a href="{{ route('petugas.transaksi.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-4 rounded-lg text-sm transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
