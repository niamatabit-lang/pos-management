<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            // বিক্রির সময়কার ক্রয়মূল্য, যাতে প্রোডাক্টের দাম পরে পরিবর্তন হলেও
            // পুরনো বিক্রির প্রফিট হিসাব সঠিক থাকে
            $table->decimal('buy_price', 12, 2)->default(0)->after('unit_price');
        });
    }

    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn('buy_price');
        });
    }
};
