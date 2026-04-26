<?php

use App\Enums\PaketTripKategori;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $mappings = [
            'Camping & Hiking' => PaketTripKategori::Camping->value,
            'Camping' => PaketTripKategori::Camping->value,
            'Hiking' => PaketTripKategori::Hiking->value,
            'Pendakian' => PaketTripKategori::Pendakian->value,
            'Fotografi' => PaketTripKategori::Fotografi->value,
            'Adventure' => PaketTripKategori::Petualangan->value,
            'Eksplorasi' => PaketTripKategori::Eksplorasi->value,
            'Pantai' => PaketTripKategori::Pantai->value,
        ];

        foreach ($mappings as $legacyKategori => $enumValue) {
            DB::table('paket_trips')
                ->where('kategori', $legacyKategori)
                ->update(['kategori' => $enumValue]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $mappings = [
            PaketTripKategori::Camping->value => 'Camping',
            PaketTripKategori::Hiking->value => 'Hiking',
            PaketTripKategori::Pendakian->value => 'Pendakian',
            PaketTripKategori::Fotografi->value => 'Fotografi',
            PaketTripKategori::Petualangan->value => 'Adventure',
            PaketTripKategori::Eksplorasi->value => 'Eksplorasi',
            PaketTripKategori::Pantai->value => 'Pantai',
        ];

        foreach ($mappings as $enumValue => $legacyKategori) {
            DB::table('paket_trips')
                ->where('kategori', $enumValue)
                ->update(['kategori' => $legacyKategori]);
        }
    }
};