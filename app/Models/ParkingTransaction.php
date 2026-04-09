<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ParkingTransaction extends Model
{
    use LogsActivity;
    protected $table = 'parking_transactions';
    protected $fillable = [
        'vehicle_id',
        'parking_area_id',
        'parking_fee_id',
        'ticket_number',
        'plate_number',
        'entry_time',
        'exit_time',
        'total_price',
        'status',
        'entry_officer_id',
        'exit_officer_id',
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'total_price', 'exit_time']) // Kolom sensitif
            ->logOnlyDirty() // Hanya catat jika ada perubahan data
            ->dontSubmitEmptyLogs(); // Jangan simpan log jika tidak ada perubahan
    }

    public function transactions()
    {
        return $this->hasMany(ParkingTransaction::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function entryOfficer()
    {
        return $this->belongsTo(User::class, 'entry_officer_id', 'id');
    }

    public function exitOfficer()
    {
        return $this->belongsTo(User::class, 'exit_officer_id', 'id');
    }

    public function parkingArea()
    {
        return $this->belongsTo(ParkingArea::class, 'parking_area_id', 'id');
    }

    public function parkingFee()
    {
        return $this->belongsTo(ParkingFee::class, 'parking_fee_id', 'id');
    }

    public function calculateTotal()
    {
        if (!$this->entry_time || !$this->exit_time) {
            return 0;
        }

        $start = Carbon::parse($this->entry_time);
        $end = Carbon::parse($this->exit_time);

        $durationInMinutes = $start->diffInMinutes($end);
        $durationInHours = ceil($durationInMinutes / 60);
        if ($durationInHours < 1) $durationInHours = 1;

        $pricePerHour = $this->parkingFee->hourly_price;
        $totalPrice = $durationInHours * $pricePerHour;

        return $totalPrice;
    }
    protected static function booted()
    {
        // static::creating(function ($transaction) {
        //     $transaction->ticket_number = 'PRK-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        //     $transaction->entry_time = now();
        //     $transaction->entry_officer_id = Auth::user()->id;
        // });

        static::created(function ($transaction) {
            $transaction->parkingArea->increment('current_occupancy');
            $transaction->vehicle->update(['is_inside' => true]);
        });

        static::updated(function ($transaction) {
            if ($transaction->exit_time && $transaction->status === 'completed') {
                $transaction->vehicle->update(['is_inside' => false]);
                $transaction->parkingArea->decrement('current_occupancy');
            }
        });

        static::deleted(function ($transaction) {
            if ($transaction->status === 'parked') {
                $transaction->vehicle->update(['is_inside' => false]);
                $transaction->parkingArea->decrement('current_occupancy');
            }
        });
    }
}
