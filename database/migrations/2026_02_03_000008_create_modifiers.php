<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Modifier groups (Size, Sugar, Ice, Add-ons)
        Schema::create('mod_groups', function (Blueprint $t) {
            $t->id();
            $t->string('name'); // Size, Sugar Level, Ice Level, Add-ons
            $t->enum('type', ['SINGLE', 'MULTIPLE'])->default('SINGLE'); // SINGLE = radio, MULTIPLE = checkbox
            $t->boolean('is_required')->default(false);
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        // Individual modifier options
        Schema::create('mod_options', function (Blueprint $t) {
            $t->id();
            $t->foreignId('mod_group_id')->constrained('mod_groups')->cascadeOnDelete();
            $t->string('name'); // Small, Medium, Large, etc.
            $t->bigInteger('price_modifier')->default(0); // Can be positive or negative
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        // Pivot: which modifier groups apply to which products
        Schema::create('product_mod_groups', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $t->foreignId('mod_group_id')->constrained('mod_groups')->cascadeOnDelete();
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();

            $t->unique(['product_id', 'mod_group_id']);
        });

        // Store selected modifiers for each order item
        Schema::create('order_item_mods', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $t->foreignId('mod_option_id')->constrained('mod_options')->cascadeOnDelete();
            $t->string('mod_group_name'); // Denormalized for display
            $t->string('mod_option_name'); // Denormalized for display
            $t->bigInteger('price_modifier')->default(0); // Denormalized price at time of order
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_mods');
        Schema::dropIfExists('product_mod_groups');
        Schema::dropIfExists('mod_options');
        Schema::dropIfExists('mod_groups');
    }
};
