@extends('layouts.app')

@section('title', 'Dashboard Petugas')
@section('page_title', 'Dashboard Operasional Parkir')
@section('page_subtitle', 'Panel kerja petugas operasional pencatatan kendaraan.')

@section('page_action')
    <a href="{{ route('petugas.transaksi.masuk') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2.5 px-4 rounded-lg shadow transition inline-flex items-center space-x-1.5">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
        <span>Catat Kendaraan Masuk</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kendaraan Parkir</p>
                <h3 class="text-2xl font-black text-amber-600 mt-1">{{ $transaksiAktif }}</h3>
                <span class="text-xs text-gray-500 font-semibold">Sedang di area</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                🅿️
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Masuk Hari Ini</p>
                <h3 class="text-2xl font-black text-indigo-600 mt-1">{{ $masukHariIni }}</h3>
                <span class="text-xs text-indigo-500 font-semibold">Transaksi masuk</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">
                📥
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Keluar Hari Ini</p>
                <h3 class="text-2xl font-black text-blue-600 mt-1">{{ $keluarHariIni }}</h3>
                <span class="text-xs text-blue-500 font-semibold">Selesai bayar</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                📤
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Penerimaan Hari Ini</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
                <span class="text-xs text-emerald-600 font-semibold">Uang parkir</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                💵
            </div>
        </div>
    </div>

    <!-- Area Parkir Occupancy -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-base font-bold text-gray-800 mb-4">Status Kapasitas Area Parkir</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($areas as $area)
                @php
                    $persen = $area->kapasitas > 0 ? round(($area->terisi / $area->kapasitas) * 100) : 0;
                    $color = $persen >= 90 ? 'bg-rose-500' : ($persen >= 60 ? 'bg-amber-500' : 'bg-emerald-500');
                @endphp
                <div class="p-4 rounded-xl border border-gray-200 bg-gray-50/50">
                    <div class="flex justify-between items-start mb-2">
                        <span class="font-bold text-gray-800 text-sm">{{ $area->nama_area }}</span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $area->isPenuh() ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $area->isPenuh() ? 'PENUH' : 'TERSEDIA' }}
                        </span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>Terisi: <strong class="text-gray-800">{{ $area->terisi }}</strong> / {{ $area->kapasitas }}</span>
                        <span>Sisa: <strong class="text-emerald-700">{{ $area->sisaKapasitas() }}</strong></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                        <div class="{{ $color }} h-2 rounded-full" style="width: {{ min(100, $persen) }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Active Parked Vehicles List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-bold text-gray-800">Kendaraan Sedang Parkir</h2>
            <a href="{{ route('petugas.transaksi.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-400 uppercase border-b border-gray-100">
                    <tr>
                        <th class="py-2.5 px-3">No. Tiket</th>
                        <th class="py-2.5 px-3">Plat Nomor</th>
                        <th class="py-2.5 px-3">Jenis</th>
                        <th class="py-2.5 px-3">Area</th>
                        <th class="py-2.5 px-3">Waktu Masuk</th>
                        <th class="py-2.5 px-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentTransactions as $trx)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="py-2.5 px-3 font-mono font-bold text-indigo-600">#TRX-{{ $trx->id_parkir }}</td>
                            <td class="py-2.5 px-3 font-black text-gray-900 tracking-wider">
                                {{ $trx->kendaraan->plat_nomor ?? '-' }}
                            </td>
                            <td class="py-2.5 px-3 uppercase text-gray-600">{{ $trx->tarif->jenis_kendaraan ?? '-' }}</td>
                            <td class="py-2.5 px-3 text-gray-600">{{ $trx->areaParkir->nama_area ?? '-' }}</td>
                            <td class="py-2.5 px-3 text-gray-500">{{ $trx->waktu_masuk->format('d/m/Y H:i') }}</td>
                            <td class="py-2.5 px-3 text-right space-x-2">
                                <a href="{{ route('petugas.transaksi.struk-masuk', $trx->id_parkir) }}" target="_blank"
                                   class="inline-block text-xs text-gray-600 hover:text-gray-900 font-medium">Tiket</a>
                                <a href="{{ route('petugas.transaksi.checkout', $trx->id_parkir) }}"
                                   class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-bold px-3 py-1 rounded text-xs shadow transition">
                                    Checkout
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-400">Tidak ada kendaraan parkir saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
