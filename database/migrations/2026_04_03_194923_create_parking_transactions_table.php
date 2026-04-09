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
        Schema::create('parking_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();

            // Berubah: Mengacu ke ID di tabel vehicles
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('parking_area_id')->constrained('parking_areas')->cascadeOnDelete();
            $table->foreignId('parking_fee_id')->constrained('parking_fees')->cascadeOnDelete();

            $table->dateTime('entry_time');
            $table->dateTime('exit_time')->nullable();
            $table->decimal('total_price', 10, 2)->default(0);
            $table->enum('status', ['parked', 'completed', 'lost_ticket'])->default('parked');

            $table->foreignId('entry_officer_id')->constrained('users');
            $table->foreignId('exit_officer_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_transactions');
    }
};
