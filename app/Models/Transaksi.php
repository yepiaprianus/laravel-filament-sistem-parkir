<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'tb_transaksi';
    protected $primaryKey = 'id_parkir';

    protected $fillable = [
        'id_kendaraan',
        'waktu_masuk',
        'waktu_keluar',
        'id_tarif',
        'durasi_jam',
        'biaya_total',
        'status',
        'id_user',
        'id_area',
    ];

    protected function casts(): array
    {
        return [
            'waktu_masuk' => 'datetime',
            'waktu_keluar' => 'datetime',
            'durasi_jam' => 'integer',
            'biaya_total' => 'decimal:0',
        ];
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan', 'id_kendaraan');
    }

    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'id_tarif', 'id_tarif');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function areaParkir()
    {
        return $this->belongsTo(AreaParkir::class, 'id_area', 'id_area');
    }

    public function isMasuk(): bool
    {
        return $this->status === 'masuk';
    }

    public function isKeluar(): bool
    {
        return $this->status === 'keluar';
    }

    public function hitungEstimasiBiaya(?Carbon $waktuKeluar = null): array
    {
        $keluar = $waktuKeluar ?? now();
        $masuk = Carbon::parse($this->waktu_masuk);
        $diffMinutes = $masuk->diffInMinutes($keluar);
        $durasiJam = max(1, (int) ceil($diffMinutes / 60));
        $tarifPerJam = (float) ($this->tarif?->tarif_per_jam ?? 0);
        $biayaTotal = $durasiJam * $tarifPerJam;

        return [
            'durasi_jam' => $durasiJam,
            'biaya_total' => $biayaTotal,
            'waktu_keluar' => $keluar,
        ];
    }
}
