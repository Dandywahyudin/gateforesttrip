<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('paket_trips', function (Blueprint $table) {
            $table->id('paketId');
            $table->string('slug')->unique();
            $table->string('nama');
            $table->text('deskripsi');
            $table->text('fasilitas');
            $table->string('lokasi');
            $table->string('kategori')->nullable();
            $table->text('meeting_point')->nullable();
            $table->text('include')->nullable();
            $table->text('exclude')->nullable();
            $table->unsignedInteger('durasi_hari');
            $table->decimal('harga', 12, 2);
            $table->string('foto')->nullable();
            $table->string('foto2')->nullable();
            $table->string('foto3')->nullable();
            $table->string('foto4')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_trips');
    }
};
