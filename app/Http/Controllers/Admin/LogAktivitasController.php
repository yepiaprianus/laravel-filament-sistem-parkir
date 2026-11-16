<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user');

        if ($request->filled('id_user')) {
            $query->where('id_user', $request->id_user);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_aktivitas', $request->tanggal);
        }

        if ($request->filled('search')) {
            $query->where('aktivitas', 'like', "%{$request->search}%");
        }

        $logs = $query->latest('waktu_aktivitas')->paginate(15)->withQueryString();
        $users = User::all();

        return view('admin.logs.index', compact('logs', 'users'));
    }
}
