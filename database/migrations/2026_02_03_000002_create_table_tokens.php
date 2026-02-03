<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('table_tokens', function (Blueprint $t) {
            $t->id();
            $t->foreignId('table_id')->constrained('cafe_tables')->cascadeOnDelete();
            $t->string('token', 64)->unique();
            $t->boolean('is_active')->default(true);
            $t->timestamp('rotated_at')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_tokens');
    }
};
