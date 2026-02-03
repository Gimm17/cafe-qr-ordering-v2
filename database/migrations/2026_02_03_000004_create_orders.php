<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->string('order_code', 32)->unique();
            $t->foreignId('table_id')->nullable()->constrained('cafe_tables')->nullOnDelete();
            $t->string('customer_name');
            $t->enum('fulfillment_type', ['DINE_IN','PICKUP'])->default('DINE_IN');
            $t->enum('order_status', ['DITERIMA','DIPROSES','READY','SELESAI','DIBATALKAN'])->default('DITERIMA');
            $t->enum('payment_status', ['UNPAID','PENDING','PAID','FAILED','EXPIRED','REFUNDED'])->default('UNPAID');

            $t->unsignedBigInteger('subtotal');
            $t->unsignedBigInteger('tax_amount')->default(0);
            $t->unsignedBigInteger('service_amount')->default(0);
            $t->unsignedBigInteger('discount_amount')->default(0);
            $t->unsignedBigInteger('grand_total');

            $t->timestamps();
        });

        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $t->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $t->string('product_name');
            $t->unsignedBigInteger('unit_price');
            $t->unsignedInteger('qty');
            $t->string('note')->nullable();
            $t->unsignedBigInteger('line_total');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
