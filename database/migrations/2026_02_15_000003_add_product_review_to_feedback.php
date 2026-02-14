<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Drop the unique constraint on order_id so one order can have multiple feedback rows
        Schema::table('order_feedback', function (Blueprint $t) {
            $t->dropUnique(['order_id']);
        });

        Schema::table('order_feedback', function (Blueprint $t) {
            $t->foreignId('product_id')->nullable()->after('order_id')->constrained('products')->nullOnDelete();
            $t->foreignId('order_item_id')->nullable()->after('product_id')->constrained('order_items')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('order_feedback', function (Blueprint $t) {
            $t->dropForeign(['product_id']);
            $t->dropForeign(['order_item_id']);
            $t->dropColumn(['product_id', 'order_item_id']);
        });

        Schema::table('order_feedback', function (Blueprint $t) {
            $t->unique('order_id');
        });
    }
};
