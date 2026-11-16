<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AreaParkir;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AreaParkirController extends Controller
{
    public function index()
    {
        $areas = AreaParkir::latest('id_area')->paginate(10);
        return view('admin.areas.index', compact('areas'));
    }

    public function create()
    {
        return view('admin.areas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_area' => ['required', 'string', 'max:50'],
            'kapasitas' => ['required', 'integer', 'min:1'],
        ]);

        $area = AreaParkir::create($validated);

        LogAktivitas::catat(Auth::id(), "Menambahkan area parkir: {$area->nama_area} (Kapasitas: {$area->kapasitas})");

        return redirect()->route('admin.areas.index')->with('success', 'Area parkir berhasil ditambahkan.');
    }

    public function edit(AreaParkir $area)
    {
        return view('admin.areas.edit', compact('area'));
    }

    public function update(Request $request, AreaParkir $area)
    {
        $validated = $request->validate([
            'nama_area' => ['required', 'string', 'max:50'],
            'kapasitas' => ['required', 'integer', 'min:' . $area->terisi],
        ]);

        $area->update($validated);

        LogAktivitas::catat(Auth::id(), "Mengubah area parkir: {$area->nama_area}");

        return redirect()->route('admin.areas.index')->with('success', 'Area parkir berhasil diperbarui.');
    }

    public function destroy(AreaParkir $area)
    {
        if ($area->terisi > 0) {
            return back()->with('error', 'Area parkir tidak dapat dihapus karena masih ada kendaraan yang terparkir.');
        }

        $nama = $area->nama_area;
        $area->delete();

        LogAktivitas::catat(Auth::id(), "Menghapus area parkir: {$nama}");

        return redirect()->route('admin.areas.index')->with('success', 'Area parkir berhasil dihapus.');
    }
}
