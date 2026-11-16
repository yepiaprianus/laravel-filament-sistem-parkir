@extends('layouts.app')

@section('title', 'Rekap Laporan Transaksi')
@section('page_title', 'Rekapitulasi Laporan Transaksi Parkir')
@section('page_subtitle', 'Filter dan cetak laporan transaksi sesuai rentang tanggal dan kriteria.')

@section('page_action')
    <a href="{{ route('owner.laporan.cetak', request()->query()) }}" target="_blank"
       class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2.5 px-4 rounded-lg shadow transition inline-flex items-center space-x-1.5">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        <span>Cetak Laporan Rekap</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Filter Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <form action="{{ route('owner.laporan.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Dari Tanggal</label>
                <input type="date" name="dari_tanggal" value="{{ $dariTanggal }}"
                       class="w-full px-3 py-1.5 text-xs rounded-lg border border-gray-300 focus:ring-1 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai_tanggal" value="{{ $sampaiTanggal }}"
                       class="w-full px-3 py-1.5 text-xs rounded-lg border border-gray-300 focus:ring-1 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Jenis Kendaraan</label>
                <select name="jenis_kendaraan" class="select2 select2-sm w-full" data-placeholder="Semua Jenis">
                    <option value="">Semua Jenis</option>
                    @foreach($tarifs as $t)
                        <option value="{{ $t->jenis_kendaraan }}" {{ request('jenis_kendaraan') === $t->jenis_kendaraan ? 'selected' : '' }}>
                            {{ ucfirst($t->jenis_kendaraan) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Status Transaksi</label>
                <select name="status" class="select2 select2-sm w-full" data-placeholder="Semua Status">
                    <option value="">Semua Status</option>
                    <option value="keluar" {{ request('status') === 'keluar' ? 'selected' : '' }}>Selesai (Keluar)</option>
                    <option value="masuk" {{ request('status') === 'masuk' ? 'selected' : '' }}>Masih Parkir</option>
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full bg-gray-800 hover:bg-black text-white text-xs font-bold py-2 px-4 rounded-lg transition shadow-sm">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Metrics for Selected Filter -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <span class="text-xs text-gray-400 font-bold uppercase">Total Transaksi</span>
            <p class="text-2xl font-black text-gray-900 mt-1">{{ $totalTransaksi }}</p>
            <span class="text-[11px] text-gray-500">Rentang waktu dipilih</span>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <span class="text-xs text-gray-400 font-bold uppercase">Selesai / Keluar</span>
            <p class="text-2xl font-black text-blue-600 mt-1">{{ $totalSelesai }}</p>
            <span class="text-[11px] text-blue-500">Transaksi lunas</span>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <span class="text-xs text-gray-400 font-bold uppercase">Masih Parkir</span>
            <p class="text-2xl font-black text-amber-600 mt-1">{{ $totalMasuk }}</p>
            <span class="text-[11px] text-amber-500">Belum keluar</span>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <span class="text-xs text-gray-400 font-bold uppercase">Total Penerimaan</span>
            <p class="text-2xl font-black text-emerald-600 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            <span class="text-[11px] text-emerald-600 font-semibold">Total omset tercatat</span>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-400 uppercase border-b border-gray-100">
                    <tr>
                        <th class="py-3 px-4">No. Tiket</th>
                        <th class="py-3 px-4">Plat Nomor</th>
                        <th class="py-3 px-4">Jenis</th>
                        <th class="py-3 px-4">Area</th>
                        <th class="py-3 px-4">Waktu Masuk</th>
                        <th class="py-3 px-4">Waktu Keluar</th>
                        <th class="py-3 px-4 text-center">Durasi</th>
                        <th class="py-3 px-4 text-right">Biaya (Rp)</th>
                        <th class="py-3 px-4">Petugas</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transaksis as $trx)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="py-3 px-4 font-mono font-bold text-indigo-600">#TRX-{{ $trx->id_parkir }}</td>
                            <td class="py-3 px-4 font-black text-gray-900 tracking-wider">
                                {{ $trx->kendaraan->plat_nomor ?? '-' }}
                            </td>
                            <td class="py-3 px-4 uppercase text-gray-600">{{ $trx->tarif->jenis_kendaraan ?? '-' }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $trx->areaParkir->nama_area ?? '-' }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ $trx->waktu_masuk->format('d/m/Y H:i') }}</td>
                            <td class="py-3 px-4 text-gray-500">
                                {{ $trx->waktu_keluar ? $trx->waktu_keluar->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-center font-bold">
                                {{ $trx->status === 'keluar' ? $trx->durasi_jam . ' Jam' : '-' }}
                            </td>
                            <td class="py-3 px-4 text-right font-black text-emerald-600">
                                {{ $trx->status === 'keluar' ? number_format($trx->biaya_total, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-gray-500">{{ $trx->user->nama_lengkap ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                    {{ $trx->status === 'masuk' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $trx->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-gray-400">Tidak ada data transaksi pada rentang waktu ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $transaksis->links() }}
        </div>
    </div>

</div>
@endsection
