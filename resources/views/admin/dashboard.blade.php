@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Admin')
@section('page_subtitle', 'Ringkasan operasional dan pengelolaan sistem parkir.')

@section('content')
<div class="space-y-6">

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1 -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kendaraan Aktif</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $transaksiAktif }}</h3>
                <span class="text-xs text-amber-600 font-semibold">Sedang parkir</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                🚗
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pendapatan</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                <span class="text-xs text-gray-500 font-semibold">Akumulasi keluar</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                💰
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Area Parkir</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $totalArea }}</h3>
                <span class="text-xs text-indigo-600 font-semibold">Lokasi terdaftar</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">
                📍
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total User</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $totalUser }}</h3>
                <span class="text-xs text-blue-600 font-semibold">Admin, Petugas, Owner</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                👥
            </div>
        </div>
    </div>

    <!-- Area Parkir Occupancy -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center space-x-2">
            <span>Kapasitas Area Parkir Real-Time</span>
        </h2>
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

    <!-- Two Columns: Recent Transactions & Recent Activity Logs -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Transaksi Terakhir -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-base font-bold text-gray-800 mb-4">Transaksi Terkini</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b text-gray-400 uppercase">
                            <th class="pb-2">Plat Nomor</th>
                            <th class="pb-2">Area</th>
                            <th class="pb-2">Masuk</th>
                            <th class="pb-2">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentTransactions as $trx)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2.5 font-bold text-gray-800">{{ $trx->kendaraan->plat_nomor ?? '-' }}</td>
                                <td class="py-2.5 text-gray-600">{{ $trx->areaParkir->nama_area ?? '-' }}</td>
                                <td class="py-2.5 text-gray-500">{{ $trx->waktu_masuk->format('d/m H:i') }}</td>
                                <td class="py-2.5">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $trx->status === 'masuk' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ ucfirst($trx->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-400">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Log Aktivitas Terakhir -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-base font-bold text-gray-800 mb-4">Log Aktivitas Terbaru</h2>
            <div class="space-y-3">
                @forelse($recentLogs as $log)
                    <div class="flex items-start justify-between text-xs border-b border-gray-50 pb-2">
                        <div>
                            <span class="font-bold text-indigo-600">{{ $log->user->username ?? 'System' }}:</span>
                            <span class="text-gray-700 ml-1">{{ $log->aktivitas }}</span>
                        </div>
                        <span class="text-gray-400 whitespace-nowrap ml-2 text-[10px]">{{ $log->waktu_aktivitas->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-center text-gray-400 text-xs py-4">Belum ada aktivitas tercatat.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
