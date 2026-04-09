<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingArea extends Model
{
    protected $table = 'parking_areas';
    protected $fillable = [
        'name',
        'capacity',
        'current_occupancy'
    ];

    public function transactions()
    {
        return $this->hasMany(ParkingTransaction::class);
    }
}
