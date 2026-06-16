<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\PaketTrip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $jadwalTemplates = [
            'danau-alpin-kemilau' => [
                ['tanggal_berangkat' => now()->addDays(14)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(16)->format('Y-m-d'), 'kuota_max' => 12, 'kuota_terisi' => 3],
                ['tanggal_berangkat' => now()->addDays(35)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(37)->format('Y-m-d'), 'kuota_max' => 12, 'kuota_terisi' => 0],
            ],
            'jalur-puncak-embun' => [
                ['tanggal_berangkat' => now()->addDays(10)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(13)->format('Y-m-d'), 'kuota_max' => 15, 'kuota_terisi' => 6],
                ['tanggal_berangkat' => now()->addDays(28)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(31)->format('Y-m-d'), 'kuota_max' => 15, 'kuota_terisi' => 1],
            ],
            'sinar-hutan-emas-fotografi' => [
                ['tanggal_berangkat' => now()->addDays(8)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(10)->format('Y-m-d'), 'kuota_max' => 8, 'kuota_terisi' => 2],
            ],
            'ekspedisi-kayak-rimba-kabut' => [
                ['tanggal_berangkat' => now()->addDays(18)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(21)->format('Y-m-d'), 'kuota_max' => 10, 'kuota_terisi' => 4],
            ],
            'puncak-pinus-emas' => [
                ['tanggal_berangkat' => now()->addDays(12)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(13)->format('Y-m-d'), 'kuota_max' => 20, 'kuota_terisi' => 8],
            ],
            'ray-of-light-expedition' => [
                ['tanggal_berangkat' => now()->addDays(22)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(26)->format('Y-m-d'), 'kuota_max' => 6, 'kuota_terisi' => 1],
            ],
            'glacier-lake-camping' => [
                ['tanggal_berangkat' => now()->addDays(16)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(18)->format('Y-m-d'), 'kuota_max' => 14, 'kuota_terisi' => 5],
            ],
            'pantai-pink-snorkeling' => [
                ['tanggal_berangkat' => now()->addDays(20)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(20)->format('Y-m-d'), 'kuota_max' => 18, 'kuota_terisi' => 7],
            ],
        ];

        foreach ($jadwalTemplates as $slug => $jadwals) {
            $paket = PaketTrip::where('slug', $slug)->first();

            if (! $paket) {
                continue;
            }

            foreach ($jadwals as $jadwalData) {
                Jadwal::updateOrCreate([
                    'paketId' => $paket->paketId,
                    'tanggal_berangkat' => $jadwalData['tanggal_berangkat'],
                    'tanggal_kembali' => $jadwalData['tanggal_kembali'],
                ], [
                    'kuota_max' => $jadwalData['kuota_max'],
                    'kuota_terisi' => $jadwalData['kuota_terisi'],
                    'status' => $jadwalData['kuota_terisi'] >= $jadwalData['kuota_max'] ? 'full' : 'open',
                    'cutoff_booking' => now()->addDays(7),
                ]);
            }
        }
    }
}
