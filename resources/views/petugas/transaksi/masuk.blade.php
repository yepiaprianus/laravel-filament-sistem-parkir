@extends('layouts.app')

@section('title', 'Kendaraan Masuk')
@section('page_title', 'Catat Kendaraan Masuk')
@section('page_subtitle', 'Masukkan nomor plat atau pilih dari master kendaraan terdaftar, lalu tentukan area parkir.')

@section('page_action')
    <a href="{{ route('petugas.transaksi.index', ['status' => 'masuk']) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold py-2 px-4 rounded-lg transition inline-flex items-center space-x-1.5">
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        <span>Lihat Kendaraan Parkir</span>
    </a>
@endsection

@section('content')
<div class="w-full bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
    <form action="{{ route('petugas.transaksi.masuk.store') }}" method="POST" id="form-masuk">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Identitas Plat & Master Kendaraan (Col 7) -->
            <div class="lg:col-span-7 space-y-5">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="text-base font-bold text-gray-900 flex items-center space-x-2">
                        <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-sm">1</span>
                        <span>Identifikasi Kendaraan</span>
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Ketik plat nomor atau pilih dari kendaraan langganan / terdaftar di master.</p>
                </div>

                <!-- Dropdown Quick Picker dari Master Kendaraan -->
                <div class="bg-indigo-50/60 border border-indigo-100 rounded-xl p-4">
                    <label class="block text-xs font-bold text-indigo-900 uppercase tracking-wide mb-1 flex items-center justify-between">
                        <span class="flex items-center space-x-1.5">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <span>Pilih Dari Master Kendaraan (Cepat)</span>
                        </span>
                        <span class="text-[10px] text-indigo-600 font-semibold bg-white px-2 py-0.5 rounded-full border border-indigo-200">
                            {{ $registeredVehicles->count() }} Terdaftar
                        </span>
                    </label>
                    <select id="quick_select_kendaraan" class="select2 w-full" data-placeholder="-- Cari plat / nama pemilik / jenis di master --">
                        <option value=""></option>
                        @foreach($registeredVehicles as $v)
                            <option value="{{ $v->plat_nomor }}"
                                    data-plat="{{ $v->plat_nomor }}"
                                    data-jenis="{{ $v->jenis_kendaraan }}"
                                    data-warna="{{ $v->warna }}"
                                    data-pemilik="{{ $v->pemilik }}">
                                {{ $v->plat_nomor }} — {{ ucfirst($v->jenis_kendaraan) }}{{ $v->warna ? ' (' . $v->warna . ')' : '' }}{{ $v->pemilik ? ' • ' . $v->pemilik : '' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-indigo-600/80 mt-1.5">
                        💡 Memilih dari dropdown ini akan langsung mengisi nomor plat, jenis, warna, dan pemilik otomatis.
                    </p>
                </div>

                <!-- Input Utama Nomor Plat -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">
                        Nomor Plat Kendaraan <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="plat_nomor" id="plat_nomor" value="{{ old('plat_nomor') }}" required autofocus
                               placeholder="Contoh: B 1234 ABC" autocomplete="off"
                               class="w-full px-4 py-3 text-xl font-black font-mono uppercase tracking-wider rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                            <span id="badge_status_icon" class="text-gray-300 font-bold text-sm">🔤</span>
                        </div>
                    </div>

                    <!-- Auto-detect Status Notification -->
                    <div id="box_auto_status" class="mt-2.5 hidden">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>

                <!-- Jenis Kendaraan -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">
                        Jenis Kendaraan <span class="text-rose-500">*</span>
                    </label>
                    <select name="jenis_kendaraan" id="jenis_kendaraan" required class="select2 w-full" data-placeholder="Pilih Jenis Kendaraan">
                        <option value=""></option>
                        @foreach($tarifs as $tarif)
                            <option value="{{ $tarif->jenis_kendaraan }}" {{ old('jenis_kendaraan') === $tarif->jenis_kendaraan ? 'selected' : '' }}>
                                {{ ucfirst($tarif->jenis_kendaraan) }} — Rp {{ number_format($tarif->tarif_per_jam, 0, ',', '.') }} / jam
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Right Column: Lokasi & Opsional Pelanggan (Col 5) -->
            <div class="lg:col-span-5 space-y-5">
                <div class="border-b border-gray-100 pb-3">
                    <h2 class="text-base font-bold text-gray-900 flex items-center space-x-2">
                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-sm">2</span>
                        <span>Lokasi & Detail Parkir</span>
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Tentukan area parkir yang tersedia dan catatan tambahan.</p>
                </div>

                <!-- Area Parkir -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">
                        Area Parkir Tujuan <span class="text-rose-500">*</span>
                    </label>
                    <select name="id_area" id="id_area" required class="select2 w-full" data-placeholder="Pilih Area Parkir">
                        <option value=""></option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id_area }}" {{ $area->isPenuh() ? 'disabled' : '' }} {{ old('id_area') == $area->id_area ? 'selected' : '' }}>
                                {{ $area->nama_area }} (Sisa: {{ $area->sisaKapasitas() }} / {{ $area->kapasitas }}) {{ $area->isPenuh() ? '— [PENUH]' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Live Area Capacity Card -->
                <div class="bg-gray-50 border border-gray-200/70 rounded-xl p-3.5 space-y-2">
                    <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status Kapasitas Area Saat Ini</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($areas as $a)
                            <div class="p-2.5 rounded-lg border text-xs flex justify-between items-center {{ $a->isPenuh() ? 'bg-rose-50 border-rose-200 text-rose-800' : 'bg-white border-gray-200 text-gray-700' }}">
                                <div>
                                    <p class="font-bold leading-tight">{{ $a->nama_area }}</p>
                                    <span class="text-[10px] {{ $a->isPenuh() ? 'text-rose-600' : 'text-gray-500' }}">
                                        Terisi {{ $a->terisi }} / {{ $a->kapasitas }}
                                    </span>
                                </div>
                                <span class="font-black px-2 py-0.5 rounded text-[11px] {{ $a->isPenuh() ? 'bg-rose-600 text-white' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ $a->isPenuh() ? 'PENUH' : 'Sisa ' . $a->sisaKapasitas() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Warna & Pemilik -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Warna Kendaraan</label>
                        <input type="text" name="warna" id="warna" value="{{ old('warna') }}" placeholder="Contoh: Hitam / Merah"
                               class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Nama Pemilik / Tamu</label>
                        <input type="text" name="pemilik" id="pemilik" value="{{ old('pemilik') }}" placeholder="Contoh: Ahmad Subagyo"
                               class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="mt-8 pt-5 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-2 text-xs text-gray-500">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Tiket masuk (karcis) barcode akan langsung digenerate dan siap dicetak thermal.</span>
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-xl text-sm shadow-md transition flex items-center space-x-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Cetak Tiket & Masuk</span>
                </button>
                <a href="{{ route('petugas.transaksi.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-5 rounded-xl text-sm transition">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Data master kendaraan terdaftar dalam format JSON
        var registeredVehicles = @json($registeredVehicles);

        // Map pencarian cepat berdasarkan plat nomor normal (tanpa spasi, huruf besar)
        var vehicleMap = {};
        registeredVehicles.forEach(function(v) {
            var normKey = (v.plat_nomor || '').replace(/\s+/g, '').toUpperCase();
            vehicleMap[normKey] = v;
        });

        function normalizePlate(val) {
            return (val || '').replace(/\s+/g, '').toUpperCase();
        }

        function checkAndApplyVehicle(plateText, source) {
            var norm = normalizePlate(plateText);
            var $statusBox = $('#box_auto_status');
            var $badgeIcon = $('#badge_status_icon');

            if (!norm || norm.length < 3) {
                $statusBox.addClass('hidden').html('');
                $badgeIcon.html('🔤');
                return;
            }

            if (vehicleMap[norm]) {
                var found = vehicleMap[norm];

                // Auto isi jenis kendaraan jika belum sama
                if ($('#jenis_kendaraan').val() !== found.jenis_kendaraan) {
                    $('#jenis_kendaraan').val(found.jenis_kendaraan).trigger('change');
                }

                // Auto isi warna & pemilik jika kosong atau berasal dari quick select
                if (source === 'quick_select' || !$('#warna').val()) {
                    $('#warna').val(found.warna || '');
                }
                if (source === 'quick_select' || !$('#pemilik').val()) {
                    $('#pemilik').val(found.pemilik || '');
                }

                // Update quick select jika triggered dari ketikan
                if (source !== 'quick_select') {
                    $('#quick_select_kendaraan').val(found.plat_nomor).trigger('change.select2');
                }

                // Tampilkan info badge terdaftar
                $badgeIcon.html('✅');
                $statusBox.removeClass('hidden').html(
                    '<div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-900 flex items-start space-x-2.5 animate-fade-in">' +
                        '<svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' +
                        '<div class="flex-1">' +
                            '<p class="font-bold text-emerald-800">✓ Kendaraan Terdaftar di Master Ditemukan!</p>' +
                            '<p class="text-[11px] text-emerald-700 mt-0.5 leading-relaxed">' +
                                'Jenis: <strong class="uppercase">' + (found.jenis_kendaraan || '-') + '</strong> | ' +
                                'Warna: <strong>' + (found.warna || 'Tidak tercatat') + '</strong> | ' +
                                'Pemilik: <strong>' + (found.pemilik || 'Tamu') + '</strong>' +
                            '</p>' +
                        '</div>' +
                    '</div>'
                );
            } else {
                $badgeIcon.html('🆕');
                $statusBox.removeClass('hidden').html(
                    '<div class="p-2.5 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-900 flex items-center space-x-2">' +
                        '<svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' +
                        '<span>Plat nomor baru (belum ada di master). Data akan otomatis tersimpan saat transaksi dicatat.</span>' +
                    '</div>'
                );

                if (source !== 'quick_select' && $('#quick_select_kendaraan').val()) {
                    $('#quick_select_kendaraan').val('').trigger('change.select2');
                }
            }
        }

        // Listener jika memilih dari dropdown master
        $('#quick_select_kendaraan').on('change', function() {
            var selectedPlate = $(this).val();
            if (selectedPlate) {
                $('#plat_nomor').val(selectedPlate);
                checkAndApplyVehicle(selectedPlate, 'quick_select');
            }
        });

        // Listener saat petugas mengetik plat nomor (input, blur, change)
        $('#plat_nomor').on('input', function() {
            var val = $(this).val();
            checkAndApplyVehicle(val, 'typing');
        });

        // Trigger awal jika ada old value
        if ($('#plat_nomor').val()) {
            checkAndApplyVehicle($('#plat_nomor').val(), 'init');
        }
    });
</script>
@endpush
