@extends('layouts.app')

@section('title', 'Transaksi Parkir')
@section('page_title', 'Operasional Transaksi Parkir')
@section('page_subtitle', 'Pencarian kendaraan parkir, pemrosesan checkout keluar, dan cetak struk.')

@section('page_action')
    <a href="{{ route('petugas.transaksi.masuk') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2.5 px-4 rounded-lg shadow transition">
        ➕ Input Kendaraan Masuk
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Filter Bar -->
    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
        <form action="{{ route('petugas.transaksi.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor plat kendaraan..."
                   class="px-3 py-1.5 text-xs font-mono uppercase rounded-lg border border-gray-300 focus:ring-1 focus:ring-indigo-500 outline-none flex-1">
            <select name="status" class="px-3 py-1.5 text-xs rounded-lg border border-gray-300 focus:ring-1 focus:ring-indigo-500 outline-none">
                <option value="masuk" {{ request('status', 'masuk') === 'masuk' ? 'selected' : '' }}>Kendaraan Parkir (Aktif)</option>
                <option value="keluar" {{ request('status') === 'keluar' ? 'selected' : '' }}>Kendaraan Keluar (Selesai)</option>
                <option value="" {{ request('status') === '' ? 'selected' : '' }}>Semua Status</option>
            </select>
            <select name="id_area" class="px-3 py-1.5 text-xs rounded-lg border border-gray-300 focus:ring-1 focus:ring-indigo-500 outline-none">
                <option value="">Semua Area</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id_area }}" {{ request('id_area') == $area->id_area ? 'selected' : '' }}>
                        {{ $area->nama_area }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-800 hover:bg-black text-white text-xs font-bold px-4 py-1.5 rounded-lg transition">
                Cari
            </button>
            @if(request()->hasAny(['search', 'id_area']) || request('status') !== 'masuk')
                <a href="{{ route('petugas.transaksi.index') }}" class="text-xs text-gray-500 hover:text-gray-800 self-center">Reset</a>
            @endif
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
                                   class="text-gray-600 hover:text-gray-900 font-medium">Tiket</a>
                                <a href="{{ route('petugas.transaksi.checkout', $trx->id_parkir) }}"
                                   class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-3 py-1 rounded text-xs shadow transition inline-block">
                                    Checkout
                                </a>
                            @else
                                <a href="{{ route('petugas.transaksi.struk-keluar', $trx->id_parkir) }}" target="_blank"
                                   class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold px-3 py-1 rounded text-xs border border-indigo-200 transition inline-block">
                                    🖨️ Struk
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="py-8 text-center text-gray-400">Tidak ada transaksi ditemukan.</td>
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
@endsection
