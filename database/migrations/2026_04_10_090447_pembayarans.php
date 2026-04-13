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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id('pembayaranId');
            $table->foreignId('reservasiId')
                  ->constrained('reservasis', 'reservasiId')
                  ->cascadeOnDelete();
            $table->string('orderId')->unique();
            $table->string('metode_pembayaran', 50)->nullable();
            $table->decimal('jumlah', 12, 2);
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            
            $table->index('reservasiId');
            $table->index('orderId');
            $table->enum('status', [
                'pending',
                'settlement',
                'capture',
                'deny',
                'cancel',
                'expire'
                ])->default('pending');
            $table->timestamps();
            $table->softDeletes();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
