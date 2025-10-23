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
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade'); // Ruangan
            $table->foreignId('inventory_item_id')->nullable()->constrained()->onDelete('set null'); // Item yang rusak
            $table->string('reporter_name'); // Nama pelapor
            $table->string('reporter_class')->nullable(); // Kelas pelapor
            $table->text('description'); // Deskripsi kerusakan
            $table->json('photos')->nullable(); // Foto kerusakan (array of file paths)
            $table->enum('urgency_level', ['rendah', 'sedang', 'tinggi'])->default('sedang'); // Tingkat urgensi
            $table->enum('status', ['baru', 'sedang_diproses', 'selesai'])->default('baru'); // Status laporan
            $table->text('admin_notes')->nullable(); // Catatan admin
            $table->timestamp('reported_at'); // Waktu laporan
            $table->timestamp('resolved_at')->nullable(); // Waktu selesai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
    }
};
