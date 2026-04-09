<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingFee extends Model
{
    protected $table = 'parking_fees';
    protected $fillable = [
        'vehicle_type_id',
        'hourly_price',
    ];

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function transactions()
    {
        return $this->hasMany(ParkingTransaction::class);
    }
}
