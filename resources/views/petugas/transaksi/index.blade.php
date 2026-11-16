@extends('layouts.app')

@section('title', 'Transaksi Parkir')
@section('page_title', 'Operasional Transaksi Parkir')
@section('page_subtitle', 'Kelola kendaraan parkir aktif, proses checkout keluar, dan riwayat transaksi.')

@section('page_action')
    <a href="{{ route('petugas.transaksi.masuk') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2.5 px-4 rounded-lg shadow transition inline-flex items-center space-x-1.5">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
        <span>Input Kendaraan Masuk</span>
    </a>
@endsection

@section('content')
<div class="space-y-4">

    <!-- Status Tabs (Aktif vs Riwayat) -->
    <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-3">
        <a href="{{ route('petugas.transaksi.index', ['status' => 'masuk']) }}"
           class="px-4 py-2 rounded-lg text-xs font-bold inline-flex items-center space-x-2 transition {{ $currentStatus === 'masuk' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
            <span>🚗 Kendaraan Parkir (Aktif)</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $currentStatus === 'masuk' ? 'bg-indigo-800 text-white' : 'bg-gray-100 text-gray-700 font-bold' }}">
                {{ $countAktif }}
            </span>
        </a>

        <a href="{{ route('petugas.transaksi.index', ['status' => 'keluar']) }}"
           class="px-4 py-2 rounded-lg text-xs font-bold inline-flex items-center space-x-2 transition {{ $currentStatus === 'keluar' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
            <span>📜 Riwayat Transaksi (Selesai)</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $currentStatus === 'keluar' ? 'bg-indigo-800 text-white' : 'bg-gray-100 text-gray-700 font-bold' }}">
                Hari Ini: {{ $countKeluarHariIni }}
            </span>
        </a>

        <a href="{{ route('petugas.transaksi.index', ['status' => 'all']) }}"
           class="px-4 py-2 rounded-lg text-xs font-bold inline-flex items-center space-x-1.5 transition {{ $currentStatus === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
            <span>📋 Semua Transaksi</span>
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-4 border-b border-gray-100 bg-gray-50/50">
            <form action="{{ route('petugas.transaksi.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <input type="hidden" name="status" value="{{ $currentStatus }}">

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-0.5">Cari Plat Nomor</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: B 1234 ABC"
                           class="w-full px-3 py-1.5 text-xs font-mono uppercase rounded-lg border border-gray-300 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-0.5">Filter Tanggal Masuk</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                           class="w-full px-3 py-1.5 text-xs rounded-lg border border-gray-300 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-0.5">Filter Area</label>
                    <select name="id_area" class="w-full px-3 py-1.5 text-xs rounded-lg border border-gray-300 focus:ring-1 focus:ring-indigo-500 outline-none">
                        <option value="">Semua Area</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id_area }}" {{ request('id_area') == $area->id_area ? 'selected' : '' }}>
                                {{ $area->nama_area }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-gray-800 hover:bg-black text-white text-xs font-bold py-1.5 px-4 rounded-lg transition">
                        Terapkan Filter
                    </button>
                    @if(request()->hasAny(['search', 'id_area', 'tanggal']))
                        <a href="{{ route('petugas.transaksi.index', ['status' => $currentStatus]) }}"
                           class="text-xs text-gray-500 hover:text-gray-800 self-center">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table -->
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
                        <th class="py-3 px-4 text-right">Biaya Total</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transaksis as $trx)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="py-3 px-4 font-mono font-bold text-indigo-600">#TRX-{{ $trx->id_parkir }}</td>
                            <td class="py-3 px-4">
                                <span class="font-black text-sm text-gray-900 tracking-wider">
                                    {{ $trx->kendaraan->plat_nomor ?? '-' }}
                                </span>
                                @if(!empty($trx->kendaraan->warna))
                                    <span class="text-[10px] text-gray-400 block">{{ $trx->kendaraan->warna }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 uppercase font-semibold text-gray-700">
                                {{ $trx->tarif->jenis_kendaraan ?? '-' }}
                            </td>
                            <td class="py-3 px-4 text-gray-600">{{ $trx->areaParkir->nama_area ?? '-' }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ $trx->waktu_masuk->format('d/m/Y H:i') }}</td>
                            <td class="py-3 px-4 text-gray-500">
                                {{ $trx->waktu_keluar ? $trx->waktu_keluar->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-center font-bold">
                                {{ $trx->status === 'keluar' ? $trx->durasi_jam . ' Jam' : '-' }}
                            </td>
                            <td class="py-3 px-4 text-right font-black text-emerald-600">
                                {{ $trx->status === 'keluar' ? 'Rp ' . number_format($trx->biaya_total, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                    {{ $trx->status === 'masuk' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $trx->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right space-x-2">
                                @if($trx->status === 'masuk')
                                    <a href="{{ route('petugas.transaksi.struk-masuk', $trx->id_parkir) }}" target="_blank"
                                       class="text-gray-600 hover:text-gray-900 font-medium text-xs">Tiket</a>
                                    <a href="{{ route('petugas.transaksi.checkout', $trx->id_parkir) }}"
                                       class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-3 py-1 rounded text-xs shadow transition inline-flex items-center space-x-1">
                                        <span>Checkout</span>
                                    </a>
                                @else
                                    <a href="{{ route('petugas.transaksi.struk-keluar', $trx->id_parkir) }}" target="_blank"
                                       class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold px-3 py-1 rounded text-xs border border-indigo-200 transition inline-flex items-center space-x-1">
                                        <svg class="w-3.5 h-3.5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        <span>Cetak Struk</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-gray-400">
                                @if($currentStatus === 'keluar')
                                    Belum ada riwayat transaksi yang keluar/selesai.
                                @elseif($currentStatus === 'masuk')
                                    Tidak ada kendaraan yang sedang parkir saat ini.
                                @else
                                    Tidak ada transaksi ditemukan.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-gray-100">
            {{ $transaksis->links() }}
        </div>
    </div>
</div>
@endsection
