<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds composite indexes to optimize common queries:
     * - order_status + created_at: Admin kanban filtering
     * - payment_status + created_at: Dashboard & reports
     * - table_id + created_at: History per table
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['order_status', 'created_at'], 'orders_status_created_index');
            $table->index(['payment_status', 'created_at'], 'orders_payment_created_index');
            $table->index(['table_id', 'created_at'], 'orders_table_created_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_status_created_index');
            $table->dropIndex('orders_payment_created_index');
            $table->dropIndex('orders_table_created_index');
        });
    }
};
