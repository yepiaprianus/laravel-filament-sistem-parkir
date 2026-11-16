@extends('layouts.app')

@section('title', 'Proses Checkout Kendaraan')
@section('page_title', 'Checkout Kendaraan Keluar')
@section('page_subtitle', 'Konfirmasi rincian durasi dan perhitungan tarif parkir.')

@section('content')
<div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="border-b border-gray-100 pb-4 mb-4 flex justify-between items-center">
        <div>
            <span class="text-xs font-bold text-gray-400 uppercase">Nomor Tiket</span>
            <h2 class="text-xl font-mono font-bold text-indigo-700">#TRX-{{ $transaksi->id_parkir }}</h2>
        </div>
        <div class="text-right">
            <span class="text-xs font-bold text-gray-400 uppercase">Plat Nomor</span>
            <p class="text-2xl font-black font-mono tracking-wider text-gray-900">{{ $transaksi->kendaraan->plat_nomor }}</p>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-2 gap-4 text-xs mb-6">
        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
            <span class="text-gray-400 block mb-0.5">Jenis Kendaraan:</span>
            <strong class="text-gray-800 text-sm uppercase">{{ $transaksi->tarif->jenis_kendaraan }}</strong>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
            <span class="text-gray-400 block mb-0.5">Area Parkir:</span>
            <strong class="text-gray-800 text-sm">{{ $transaksi->areaParkir->nama_area }}</strong>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
            <span class="text-gray-400 block mb-0.5">Waktu Masuk:</span>
            <strong class="text-gray-800">{{ $transaksi->waktu_masuk->format('d/m/Y H:i:s') }}</strong>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
            <span class="text-gray-400 block mb-0.5">Waktu Keluar (Sekarang):</span>
            <strong class="text-gray-800">{{ $estimasi['waktu_keluar']->format('d/m/Y H:i:s') }}</strong>
        </div>
    </div>

    <!-- Calculation Breakdown -->
    <div class="bg-indigo-50/60 p-4 rounded-xl border border-indigo-100 mb-6 space-y-2 text-sm">
        <div class="flex justify-between text-indigo-900">
            <span>Tarif Per Jam:</span>
            <strong>Rp {{ number_format($transaksi->tarif->tarif_per_jam, 0, ',', '.') }}</strong>
        </div>
        <div class="flex justify-between text-indigo-900">
            <span>Durasi Terhitung (Pembulatan ke atas):</span>
            <strong class="text-base text-indigo-700">{{ $estimasi['durasi_jam'] }} Jam</strong>
        </div>
        <div class="border-t border-indigo-200 pt-2 flex justify-between items-center text-lg">
            <span class="font-bold text-gray-800">Total Biaya Parkir:</span>
            <span class="text-2xl font-black text-emerald-600">
                Rp {{ number_format($estimasi['biaya_total'], 0, ',', '.') }}
            </span>
        </div>
    </div>

    <!-- Action Form -->
    <form action="{{ route('petugas.transaksi.checkout.store', $transaksi->id_parkir) }}" method="POST">
        @csrf
        <div class="flex space-x-3">
            <button type="submit"
                    class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-lg text-sm shadow-md transition text-center">
                Konfirmasi Pembayaran & Selesaikan Transaksi
            </button>
            <a href="{{ route('petugas.transaksi.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg text-sm transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
