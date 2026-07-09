<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * আগে sku কলামটা পুরো ডাটাবেজ জুড়ে (সব শপ মিলিয়ে) ইউনিক ছিল, কিন্তু
     * ProductController এ ভ্যালিডেশন ধরা ছিল "শুধু নিজের শপের মধ্যে ইউনিক"।
     * ফলে দুইটা ভিন্ন শপে একই SKU ব্যবহার করলে ডাটাবেজ লেভেলে এরর হতো।
     * এখানে সেটা ঠিক করে shop_id + sku এর কম্বিনেশনে ইউনিক করা হলো।
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_sku_unique');
            $table->unique(['shop_id', 'sku']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['shop_id', 'sku']);
            $table->unique('sku');
        });
    }
};
