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
                ['tanggal_berangkat' => now()->addDays(14)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(16)->format('Y-m-d'), 'kuota_max' => 12, 'kuota_terisi' => 3, 'harga_override' => null],
                ['tanggal_berangkat' => now()->addDays(35)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(37)->format('Y-m-d'), 'kuota_max' => 12, 'kuota_terisi' => 0, 'harga_override' => 4700000],
            ],
            'jalur-puncak-embun' => [
                ['tanggal_berangkat' => now()->addDays(10)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(13)->format('Y-m-d'), 'kuota_max' => 15, 'kuota_terisi' => 6, 'harga_override' => null],
                ['tanggal_berangkat' => now()->addDays(28)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(31)->format('Y-m-d'), 'kuota_max' => 15, 'kuota_terisi' => 1, 'harga_override' => 1150000],
            ],
            'sinar-hutan-emas-fotografi' => [
                ['tanggal_berangkat' => now()->addDays(8)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(10)->format('Y-m-d'), 'kuota_max' => 8, 'kuota_terisi' => 2, 'harga_override' => null],
            ],
            'ekspedisi-kayak-rimba-kabut' => [
                ['tanggal_berangkat' => now()->addDays(18)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(21)->format('Y-m-d'), 'kuota_max' => 10, 'kuota_terisi' => 4, 'harga_override' => 3600000],
            ],
            'puncak-pinus-emas' => [
                ['tanggal_berangkat' => now()->addDays(12)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(13)->format('Y-m-d'), 'kuota_max' => 20, 'kuota_terisi' => 8, 'harga_override' => null],
            ],
            'ray-of-light-expedition' => [
                ['tanggal_berangkat' => now()->addDays(22)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(26)->format('Y-m-d'), 'kuota_max' => 6, 'kuota_terisi' => 1, 'harga_override' => null],
            ],
            'glacier-lake-camping' => [
                ['tanggal_berangkat' => now()->addDays(16)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(18)->format('Y-m-d'), 'kuota_max' => 14, 'kuota_terisi' => 5, 'harga_override' => 4400000],
            ],
            'pantai-pink-snorkeling' => [
                ['tanggal_berangkat' => now()->addDays(20)->format('Y-m-d'), 'tanggal_kembali' => now()->addDays(20)->format('Y-m-d'), 'kuota_max' => 18, 'kuota_terisi' => 7, 'harga_override' => null],
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
                    'harga_override' => $jadwalData['harga_override'],
                    'cutoff_booking' => now()->addDays(7),
                ]);
            }
        }
    }
}
