<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tandai sumber order: 'QR' = dari customer scan, 'POS' = dari kasir
            $table->string('order_source', 10)->default('QR')->after('order_code');
        });

        Schema::table('payments', function (Blueprint $table) {
            // Untuk pembayaran tunai di kasir
            $table->integer('cash_received')->nullable()->after('raw_response');
            $table->integer('cash_change')->nullable()->after('cash_received');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_source');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['cash_received', 'cash_change']);
        });
    }
};
