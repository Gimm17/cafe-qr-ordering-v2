<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cafe_tables', function (Blueprint $t) {
            $t->id();
            $t->unsignedInteger('table_no');
            $t->string('name')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->unique('table_no');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cafe_tables');
    }
};
