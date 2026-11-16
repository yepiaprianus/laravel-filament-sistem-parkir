<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TarifController extends Controller
{
    public function index()
    {
        $tarifs = Tarif::all();
        return view('admin.tarifs.index', compact('tarifs'));
    }

    public function create()
    {
        return view('admin.tarifs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_kendaraan' => ['required', Rule::in(['motor', 'mobil', 'lainnya']), 'unique:tb_tarif,jenis_kendaraan'],
            'tarif_per_jam' => ['required', 'numeric', 'min:0'],
        ]);

        $tarif = Tarif::create($validated);

        LogAktivitas::catat(Auth::id(), "Menambahkan tarif parkir: {$tarif->jenis_kendaraan} (Rp " . number_format($tarif->tarif_per_jam, 0, ',', '.') . "/jam)");

        return redirect()->route('admin.tarifs.index')->with('success', 'Tarif berhasil ditambahkan.');
    }

    public function edit(Tarif $tarif)
    {
        return view('admin.tarifs.edit', compact('tarif'));
    }

    public function update(Request $request, Tarif $tarif)
    {
        $validated = $request->validate([
            'tarif_per_jam' => ['required', 'numeric', 'min:0'],
        ]);

        $tarif->update($validated);

        LogAktivitas::catat(Auth::id(), "Memperbarui tarif parkir: {$tarif->jenis_kendaraan} menjadi Rp " . number_format($tarif->tarif_per_jam, 0, ',', '.') . "/jam");

        return redirect()->route('admin.tarifs.index')->with('success', 'Tarif berhasil diperbarui.');
    }

    public function destroy(Tarif $tarif)
    {
        $jenis = $tarif->jenis_kendaraan;
        $tarif->delete();

        LogAktivitas::catat(Auth::id(), "Menghapus tarif parkir jenis: {$jenis}");

        return redirect()->route('admin.tarifs.index')->with('success', 'Tarif berhasil dihapus.');
    }
}
