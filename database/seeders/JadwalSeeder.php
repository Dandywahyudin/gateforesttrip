<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $jadwals = [];
        
        // Create 3 schedules for each paket (8 pakets total = 24 schedules)
        for ($paketId = 1; $paketId <= 8; $paketId++) {
            for ($i = 0; $i < 3; $i++) {
                $berangkat = Carbon::now()->addDays(10 + ($paketId * 5) + ($i * 15));
                $kembali = $berangkat->clone()->addDays(3); // Mostly 3-day trips
                
                $jadwals[] = [
                    'paketId' => $paketId,
                    'tanggal_berangkat' => $berangkat,
                    'tanggal_kembali' => $kembali,
                    'kuota_max' => rand(15, 25),
                    'kuota_terisi' => 0,
                    'status' => 'open',
                    'harga_override' => null,
                    'cutoff_booking' => $berangkat->clone()->subDays(7),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        Jadwal::insert($jadwals);
    }
}
