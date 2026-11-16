<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaParkir extends Model
{
    use HasFactory;

    protected $table = 'tb_area_parkir';
    protected $primaryKey = 'id_area';

    protected $fillable = [
        'nama_area',
        'kapasitas',
        'terisi',
    ];

    protected function casts(): array
    {
        return [
            'kapasitas' => 'integer',
            'terisi' => 'integer',
        ];
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_area', 'id_area');
    }

    public function sisaKapasitas(): int
    {
        return max(0, $this->kapasitas - $this->terisi);
    }

    public function isPenuh(): bool
    {
        return $this->terisi >= $this->kapasitas;
    }
}
