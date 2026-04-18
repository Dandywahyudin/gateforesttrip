<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'nama' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'wisatawan',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@gateforesttrip.test'],
            [
                'nama' => 'Admin GateForestTrip',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $this->call([
            PaketTripSeeder::class,
            JadwalSeeder::class,
        ]);
    }
}
