<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peserta_trips', function (Blueprint $table) {
            if (Schema::hasColumn('peserta_trips', 'jenis_identitas')) {
                $table->dropColumn('jenis_identitas');
            }

            if (Schema::hasColumn('peserta_trips', 'no_identitas')) {
                $table->dropColumn('no_identitas');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta_trips', function (Blueprint $table) {
            if (! Schema::hasColumn('peserta_trips', 'jenis_identitas')) {
                $table->enum('jenis_identitas', ['paspor', 'sim'])->default('paspor')->after('nama');
            }

            if (! Schema::hasColumn('peserta_trips', 'no_identitas')) {
                $table->string('no_identitas', 30)->after('jenis_identitas');
            }
        });
    }
};
