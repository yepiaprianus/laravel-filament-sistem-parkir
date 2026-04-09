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

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique(); // Plat Nomor (Unik)
            $table->foreignId('vehicle_type_id')->constrained(); // Relasi ke Motor/Mobil
            $table->string('owner_name')->nullable(); // Opsional (untuk langganan/member)
            $table->boolean('is_inside')->default(false); // Status apakah sedang di dalam parkiran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
