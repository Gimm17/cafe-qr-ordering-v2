<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CafeTable;
use App\Models\TableToken;
use App\Models\Category;
use App\Models\Product;
use App\Models\ModGroup;
use App\Models\ModOption;
use App\Models\User;
use Illuminate\Support\Str;

class CafeDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@local.test'],
            ['name' => 'Admin', 'password' => bcrypt('admin123'), 'is_admin' => true]
        );
        // Tables
        for ($i = 1; $i <= 8; $i++) {
            $table = CafeTable::firstOrCreate(
                ['table_no' => $i],
                ['name' => "Meja {$i}", 'is_active' => true]
            );

            if (!$table->tokens()->where('is_active', true)->exists()) {
                TableToken::create([
                    'table_id' => $table->id,
                    'token' => Str::random(32),
                    'is_active' => true,
                ]);
            }
        }

        // Categories
        $kopi = Category::firstOrCreate(['name' => 'Kopi'], ['sort_order' => 1, 'is_active' => true]);
        $non = Category::firstOrCreate(['name' => 'Non-Kopi'], ['sort_order' => 2, 'is_active' => true]);
        $snack = Category::firstOrCreate(['name' => 'Snack'], ['sort_order' => 3, 'is_active' => true]);
        $dessert = Category::firstOrCreate(['name' => 'Dessert'], ['sort_order' => 4, 'is_active' => true]);

        // Modifier Groups
        $sizeGroup = ModGroup::firstOrCreate(
            ['name' => 'Size'],
            ['type' => 'SINGLE', 'is_required' => true, 'sort_order' => 1, 'is_active' => true]
        );

        $sugarGroup = ModGroup::firstOrCreate(
            ['name' => 'Sugar Level'],
            ['type' => 'SINGLE', 'is_required' => false, 'sort_order' => 2, 'is_active' => true]
        );

        $iceGroup = ModGroup::firstOrCreate(
            ['name' => 'Ice Level'],
            ['type' => 'SINGLE', 'is_required' => false, 'sort_order' => 3, 'is_active' => true]
        );

        $addOnGroup = ModGroup::firstOrCreate(
            ['name' => 'Add-ons'],
            ['type' => 'MULTIPLE', 'is_required' => false, 'sort_order' => 4, 'is_active' => true]
        );

        // Size Options
        ModOption::firstOrCreate(['mod_group_id' => $sizeGroup->id, 'name' => 'Small'], ['price_modifier' => -5000, 'sort_order' => 1, 'is_active' => true]);
        ModOption::firstOrCreate(['mod_group_id' => $sizeGroup->id, 'name' => 'Medium'], ['price_modifier' => 0, 'sort_order' => 2, 'is_active' => true]);
        ModOption::firstOrCreate(['mod_group_id' => $sizeGroup->id, 'name' => 'Large'], ['price_modifier' => 5000, 'sort_order' => 3, 'is_active' => true]);

        // Sugar Options
        ModOption::firstOrCreate(['mod_group_id' => $sugarGroup->id, 'name' => 'Normal Sugar'], ['price_modifier' => 0, 'sort_order' => 1, 'is_active' => true]);
        ModOption::firstOrCreate(['mod_group_id' => $sugarGroup->id, 'name' => 'Less Sugar'], ['price_modifier' => 0, 'sort_order' => 2, 'is_active' => true]);
        ModOption::firstOrCreate(['mod_group_id' => $sugarGroup->id, 'name' => 'No Sugar'], ['price_modifier' => 0, 'sort_order' => 3, 'is_active' => true]);

        // Ice Options
        ModOption::firstOrCreate(['mod_group_id' => $iceGroup->id, 'name' => 'Normal Ice'], ['price_modifier' => 0, 'sort_order' => 1, 'is_active' => true]);
        ModOption::firstOrCreate(['mod_group_id' => $iceGroup->id, 'name' => 'Less Ice'], ['price_modifier' => 0, 'sort_order' => 2, 'is_active' => true]);
        ModOption::firstOrCreate(['mod_group_id' => $iceGroup->id, 'name' => 'No Ice'], ['price_modifier' => 0, 'sort_order' => 3, 'is_active' => true]);

        // Add-on Options
        ModOption::firstOrCreate(['mod_group_id' => $addOnGroup->id, 'name' => 'Extra Shot'], ['price_modifier' => 5000, 'sort_order' => 1, 'is_active' => true]);
        ModOption::firstOrCreate(['mod_group_id' => $addOnGroup->id, 'name' => 'Oat Milk'], ['price_modifier' => 8000, 'sort_order' => 2, 'is_active' => true]);
        ModOption::firstOrCreate(['mod_group_id' => $addOnGroup->id, 'name' => 'Vanilla Syrup'], ['price_modifier' => 5000, 'sort_order' => 3, 'is_active' => true]);
        ModOption::firstOrCreate(['mod_group_id' => $addOnGroup->id, 'name' => 'Caramel Syrup'], ['price_modifier' => 5000, 'sort_order' => 4, 'is_active' => true]);
        ModOption::firstOrCreate(['mod_group_id' => $addOnGroup->id, 'name' => 'Whipped Cream'], ['price_modifier' => 3000, 'sort_order' => 5, 'is_active' => true]);

        // Products (sample with better data)
        $americano = $this->product($kopi->id, 'Americano', 22000, 'Espresso dengan air panas, bold dan rich', true);
        $cappuccino = $this->product($kopi->id, 'Cappuccino', 28000, 'Espresso dengan susu steamed dan foam tebal', true);
        $latte = $this->product($kopi->id, 'Caffe Latte', 30000, 'Espresso dengan susu steamed yang lembut', false);
        $mocha = $this->product($kopi->id, 'Mocha', 32000, 'Perpaduan espresso, coklat, dan susu', false);
        $espresso = $this->product($kopi->id, 'Espresso', 18000, 'Single shot espresso murni', false);

        $matcha = $this->product($non->id, 'Matcha Latte', 32000, 'Green tea premium dengan susu segar', true);
        $chocolate = $this->product($non->id, 'Hot Chocolate', 28000, 'Coklat premium dengan susu hangat', false);
        $taro = $this->product($non->id, 'Taro Latte', 30000, 'Minuman taro creamy dengan susu', false);

        $fries = $this->product($snack->id, 'French Fries', 25000, 'Kentang goreng crispy dengan saus', false);
        $nachos = $this->product($snack->id, 'Nachos', 35000, 'Tortilla chips dengan keju dan salsa', false);

        $cheesecake = $this->product($dessert->id, 'Cheesecake', 40000, 'New York style cheesecake', false);
        $brownie = $this->product($dessert->id, 'Chocolate Brownie', 35000, 'Brownie dengan ice cream vanilla', false);

        // Link modifiers to coffee products
        $coffeeModGroups = [$sizeGroup->id, $sugarGroup->id, $iceGroup->id, $addOnGroup->id];
        foreach ([$americano, $cappuccino, $latte, $mocha, $espresso] as $product) {
            if ($product) {
                $product->modGroups()->syncWithoutDetaching($coffeeModGroups);
            }
        }

        // Link modifiers to non-coffee drinks
        $drinkModGroups = [$sizeGroup->id, $sugarGroup->id, $iceGroup->id];
        foreach ([$matcha, $chocolate, $taro] as $product) {
            if ($product) {
                $product->modGroups()->syncWithoutDetaching($drinkModGroups);
            }
        }
    }

    private function product($catId, $name, $price, $description = null, $isBestSeller = false): ?Product
    {
        return Product::firstOrCreate(
            ['name' => $name],
            [
                'category_id' => $catId,
                'slug' => Str::slug($name) . '-' . Str::lower(Str::random(4)),
                'description' => $description,
                'base_price' => $price,
                'image_url' => null,
                'is_active' => true,
                'is_sold_out' => false,
                'is_best_seller' => $isBestSeller,
            ]
        );
    }
}
