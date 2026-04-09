<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicles';
    protected $fillable = ['plate_number', 'vehicle_type_id', 'owner_name', 'is_inside'];

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function transactions()
    {
        return $this->hasMany(ParkingTransaction::class);
    }
}
