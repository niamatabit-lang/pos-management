<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('shop_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('shop_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('shop_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('shop_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        // আগে থেকে থাকা ডাটার জন্য একটা ডিফল্ট শপ বানিয়ে সব ডাটা তার সাথে যুক্ত করা হচ্ছে,
        // যাতে পুরনো Product/Sale/Category/Stock রেকর্ড হারিয়ে না যায়।
        $hasOldData = DB::table('products')->exists()
            || DB::table('categories')->exists()
            || DB::table('sales')->exists();

        if ($hasOldData) {
            $defaultShopId = DB::table('shops')->insertGetId([
                'name' => 'আমার শপ',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('categories')->whereNull('shop_id')->update(['shop_id' => $defaultShopId]);
            DB::table('products')->whereNull('shop_id')->update(['shop_id' => $defaultShopId]);
            DB::table('sales')->whereNull('shop_id')->update(['shop_id' => $defaultShopId]);
            DB::table('stock_movements')->whereNull('shop_id')->update(['shop_id' => $defaultShopId]);
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('shop_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('shop_id');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropConstrainedForeignId('shop_id');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('shop_id');
        });
    }
};
