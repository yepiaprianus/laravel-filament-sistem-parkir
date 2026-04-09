<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::upsert(
            [
                [
                    'name' => 'Super Admin',
                    'username' => 'admin',
                    'password' => bcrypt('admin'),
                    'role' => 'admin',
                ],
                [
                    'name' => 'Petugas Parkir',
                    'username' => 'officer',
                    'password' => bcrypt('officer'),
                    'role' => 'officer',
                ],
                [
                    'name' => 'Pemilik Parkir',
                    'username' => 'owner',
                    'password' => bcrypt('owner'),
                    'role' => 'owner',
                ]
            ],
            ['username'],
            ['name', 'password', 'role']
        );
    }
}
