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
        Schema::table('inventory_items', function (Blueprint $table) {
            // Add maintenance dates (only for monitored items)
            $table->date('purchase_date')->nullable()->after('date_added');
            $table->date('last_maintenance_date')->nullable()->after('purchase_date');
            $table->date('next_maintenance_date')->nullable()->after('last_maintenance_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropColumn(['purchase_date', 'last_maintenance_date', 'next_maintenance_date']);
        });
    }
};
