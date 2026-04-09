<?php

namespace App\Http\Controllers;

use App\Models\ParkingTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ParkingPrintController extends Controller
{
    public function print(ParkingTransaction $record)
    {
        if ($record->status !== 'completed') {
            abort(403, 'Transaksi belum selesai.');
        }

        if ($record) {
            $record->entry_time = Carbon::parse($record->entry_time);
            $record->exit_time = Carbon::parse($record->exit_time);
        }
        return view('parking.print-struk', compact('record'));
    }
}
