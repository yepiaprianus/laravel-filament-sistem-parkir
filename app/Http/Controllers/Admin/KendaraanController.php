<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KendaraanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kendaraan::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('plat_nomor', 'like', "%{$search}%")
                  ->orWhere('pemilik', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis_kendaraan')) {
            $query->where('jenis_kendaraan', $request->jenis_kendaraan);
        }

        $kendaraans = $query->latest('id_kendaraan')->paginate(10)->withQueryString();

        return view('admin.kendaraans.index', compact('kendaraans'));
    }

    public function create()
    {
        return view('admin.kendaraans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plat_nomor' => ['required', 'string', 'max:15', 'unique:tb_kendaraan,plat_nomor'],
            'jenis_kendaraan' => ['required', Rule::in(['motor', 'mobil', 'lainnya'])],
            'warna' => ['nullable', 'string', 'max:20'],
            'pemilik' => ['nullable', 'string', 'max:100'],
        ]);

        $validated['plat_nomor'] = strtoupper(trim($validated['plat_nomor']));
        $validated['id_user'] = Auth::id();

        $kendaraan = Kendaraan::create($validated);

        LogAktivitas::catat(Auth::id(), "Menambahkan kendaraan: {$kendaraan->plat_nomor} ({$kendaraan->jenis_kendaraan})");

        return redirect()->route('admin.kendaraans.index')->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Kendaraan $kendaraan)
    {
        return view('admin.kendaraans.edit', compact('kendaraan'));
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        $validated = $request->validate([
            'plat_nomor' => ['required', 'string', 'max:15', Rule::unique('tb_kendaraan', 'plat_nomor')->ignore($kendaraan->id_kendaraan, 'id_kendaraan')],
            'jenis_kendaraan' => ['required', Rule::in(['motor', 'mobil', 'lainnya'])],
            'warna' => ['nullable', 'string', 'max:20'],
            'pemilik' => ['nullable', 'string', 'max:100'],
        ]);

        $validated['plat_nomor'] = strtoupper(trim($validated['plat_nomor']));
        $kendaraan->update($validated);

        LogAktivitas::catat(Auth::id(), "Mengubah data kendaraan: {$kendaraan->plat_nomor}");

        return redirect()->route('admin.kendaraans.index')->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(Kendaraan $kendaraan)
    {
        // Cek apakah ada transaksi aktif
        $masihParkir = $kendaraan->transaksi()->where('status', 'masuk')->exists();
        if ($masihParkir) {
            return back()->with('error', 'Kendaraan tidak dapat dihapus karena masih tercatat di area parkir.');
        }

        $plat = $kendaraan->plat_nomor;
        $kendaraan->delete();

        LogAktivitas::catat(Auth::id(), "Menghapus kendaraan: {$plat}");

        return redirect()->route('admin.kendaraans.index')->with('success', 'Kendaraan berhasil dihapus.');
    }
}
