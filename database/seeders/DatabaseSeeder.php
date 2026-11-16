<?php

namespace Database\Seeders;

use App\Models\AreaParkir;
use App\Models\Tarif;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Master User
        $users = [
            [
                'nama_lengkap' => 'Administrator Parkir',
                'username' => 'admin',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'status_aktif' => 1,
            ],
            [
                'nama_lengkap' => 'Petugas Operator',
                'username' => 'petugas',
                'password' => Hash::make('petugas'),
                'role' => 'petugas',
                'status_aktif' => 1,
            ],
            [
                'nama_lengkap' => 'Pemilik Bisnis Parkir',
                'username' => 'owner',
                'password' => Hash::make('owner'),
                'role' => 'owner',
                'status_aktif' => 1,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['username' => $userData['username']],
                $userData
            );
        }

        // 2. Master Tarif
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

        // 3. Master Area Parkir
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
