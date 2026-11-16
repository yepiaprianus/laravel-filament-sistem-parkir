<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
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
    }
}
