@extends('layouts.app')

@section('title', 'Dashboard Owner')
@section('page_title', 'Dashboard Owner')
@section('page_subtitle', 'Pemantauan performa bisnis, statistik okupansi, dan pendapatan parkir.')

@section('page_action')
    <a href="{{ route('owner.laporan.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2.5 px-4 rounded-lg shadow transition">
        📑 Lihat Rekap Laporan
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Financial & Operational Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pendapatan</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                <span class="text-xs text-gray-500 font-semibold">Keseluruhan waktu</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                💰
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pendapatan Bulan Ini</p>
                <h3 class="text-2xl font-black text-indigo-600 mt-1">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
                <span class="text-xs text-indigo-500 font-semibold">{{ now()->translatedFormat('F Y') }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">
                📈
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Transaksi</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $totalTransaksi }}</h3>
                <span class="text-xs text-blue-600 font-semibold">{{ $transaksiSelesai }} selesai</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                📋
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kendaraan Aktif</p>
                <h3 class="text-2xl font-black text-amber-600 mt-1">{{ $transaksiAktif }}</h3>
                <span class="text-xs text-amber-600 font-semibold">Sedang di area</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                🚗
            </div>
        </div>
    </div>

    <!-- Area Capacities -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-base font-bold text-gray-800 mb-4">Okupansi Area Parkir Saat Ini</h2>
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

    <!-- Recent Transactions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-bold text-gray-800">Transaksi Terbaru</h2>
            <a href="{{ route('owner.laporan.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Laporan Lengkap →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-400 uppercase border-b border-gray-100">
                    <tr>
                        <th class="py-2.5 px-3">No. Tiket</th>
                        <th class="py-2.5 px-3">Plat Nomor</th>
                        <th class="py-2.5 px-3">Jenis</th>
                        <th class="py-2.5 px-3">Waktu Masuk</th>
                        <th class="py-2.5 px-3">Waktu Keluar</th>
                        <th class="py-2.5 px-3 text-right">Biaya</th>
                        <th class="py-2.5 px-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentTransactions as $trx)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="py-2.5 px-3 font-mono font-bold text-indigo-600">#TRX-{{ $trx->id_parkir }}</td>
                            <td class="py-2.5 px-3 font-bold text-gray-800">{{ $trx->kendaraan->plat_nomor ?? '-' }}</td>
                            <td class="py-2.5 px-3 uppercase text-gray-600">{{ $trx->tarif->jenis_kendaraan ?? '-' }}</td>
                            <td class="py-2.5 px-3 text-gray-500">{{ $trx->waktu_masuk->format('d/m/Y H:i') }}</td>
                            <td class="py-2.5 px-3 text-gray-500">{{ $trx->waktu_keluar ? $trx->waktu_keluar->format('d/m/Y H:i') : '-' }}</td>
                            <td class="py-2.5 px-3 text-right font-black text-emerald-600">
                                {{ $trx->status === 'keluar' ? 'Rp ' . number_format($trx->biaya_total, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                    {{ $trx->status === 'masuk' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $trx->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-gray-400">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
