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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade'); // Ruangan
            $table->string('item_category'); // Kategori (Elektronik, Furnitur, dll.)
            $table->string('item_type'); // Jenis item
            $table->string('item_name'); // Nama barang
            $table->string('barcode')->unique(); // Kode unik atau QR
            $table->string('prefix'); // Kode prefix unik
            $table->integer('qty')->default(1); // Jumlah
            $table->string('unit')->default('pcs'); // Satuan (pcs, unit, set)
            $table->date('date_added'); // Tanggal ditambahkan
            $table->enum('barcode_status', ['active', 'inactive'])->default('active'); // Status barcode
            $table->enum('status', ['baik', 'rusak', 'dalam_perbaikan'])->default('baik'); // Status barang
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
