<?php

use App\Http\Controllers\ParkingPrintController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/admin/dashboard');
    }
    return redirect('/admin/login');
    // return view('welcome');
});

Route::get('/parking/print/{record}', [ParkingPrintController::class, 'print'])
    ->name('parking.print')
    ->middleware(['auth']);
