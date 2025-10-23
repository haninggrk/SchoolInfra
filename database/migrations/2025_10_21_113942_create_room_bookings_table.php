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
        Schema::create('room_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Guru yang booking
            $table->string('subject'); // Mata pelajaran
            $table->text('description')->nullable(); // Keterangan
            $table->date('booking_date'); // Tanggal booking
            $table->time('start_time'); // Jam mulai
            $table->time('end_time'); // Jam selesai
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed', 'cancelled'])->default('approved');
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_bookings');
    }
};
