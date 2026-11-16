<?php

namespace Database\Seeders;

use App\Models\Tarif;
use Illuminate\Database\Seeder;

class TarifSeeder extends Seeder
{
    public function run(): void
    {
        $tarifs = [
            ['jenis_kendaraan' => 'motor', 'tarif_per_jam' => 2000],
            ['jenis_kendaraan' => 'mobil', 'tarif_per_jam' => 5000],
            ['jenis_kendaraan' => 'lainnya', 'tarif_per_jam' => 10000],
        ];

        foreach ($tarifs as $tarifData) {
            Tarif::updateOrCreate(
                ['jenis_kendaraan' => $tarifData['jenis_kendaraan']],
                $tarifData
            );
        }
    }
}
