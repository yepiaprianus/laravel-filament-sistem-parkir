<?php

namespace Database\Seeders;

use App\Models\AreaParkir;
use Illuminate\Database\Seeder;

class AreaParkirSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['nama_area' => 'Area A - Gedung Utama', 'kapasitas' => 50, 'terisi' => 0],
            ['nama_area' => 'Area B - Basement', 'kapasitas' => 80, 'terisi' => 0],
            ['nama_area' => 'Area C - Khusus Mobil', 'kapasitas' => 30, 'terisi' => 0],
        ];

        foreach ($areas as $areaData) {
            AreaParkir::updateOrCreate(
                ['nama_area' => $areaData['nama_area']],
                $areaData
            );
        }
    }
}
