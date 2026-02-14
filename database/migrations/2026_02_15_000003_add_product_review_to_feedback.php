<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_feedback', function (Blueprint $t) {
            // Must drop foreign key FIRST, then the unique index
            $t->dropForeign(['order_id']);
            $t->dropUnique(['order_id']);

            // Re-add foreign key without unique constraint
            $t->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
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
            $t->dropForeign(['order_id']);
            $t->unique('order_id');
            $t->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }
};
