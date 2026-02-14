<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $t) {
            $t->time('close_order_time')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $t) {
            $t->dropColumn('close_order_time');
        });
    }
};
