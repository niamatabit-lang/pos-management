<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // কোম্পানি থেকে পণ্য বিক্রির উপর প্রতি ইউনিটে যে কমিশন পাওয়া যায় (দিনে দিনে
            // পরিবর্তনশীল)। এটা প্রফিটে যোগ হবে কিন্তু ইনভয়েসে দেখানো হবে না।
            $table->decimal('commission', 12, 2)->default(0)->after('sell_price');
        });

        Schema::table('sale_items', function (Blueprint $table) {
            // বিক্রির সময়কার কমিশন (প্রতি ইউনিট) স্ন্যাপশট করে রাখা হচ্ছে, যাতে পরে
            // প্রোডাক্টের কমিশন পরিবর্তন হলেও পুরনো বিক্রির প্রফিট হিসাব ঠিক থাকে।
            $table->decimal('commission', 12, 2)->default(0)->after('buy_price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('commission');
        });

        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn('commission');
        });
    }
};
