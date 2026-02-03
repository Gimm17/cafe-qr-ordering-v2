<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_feedback', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete();
            $t->tinyInteger('rating')->nullable();
            $t->text('comment')->nullable();
            $t->enum('status', ['VISIBLE','HIDDEN'])->default('VISIBLE');
            $t->boolean('is_flagged')->default(false);
            $t->text('admin_note')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_feedback');
    }
};
