<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete();
            $t->string('gateway', 30)->default('ipaymu');
            $t->string('status', 20)->default('CREATED');
            $t->string('gateway_session_id')->nullable();
            $t->string('gateway_url')->nullable();
            $t->string('gateway_trx_id')->nullable();
            $t->json('raw_response')->nullable();
            $t->timestamps();
        });

        Schema::create('payment_events', function (Blueprint $t) {
            $t->id();
            $t->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();
            $t->string('event_type', 30);
            $t->json('payload');
            $t->boolean('is_valid')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_events');
        Schema::dropIfExists('payments');
    }
};
