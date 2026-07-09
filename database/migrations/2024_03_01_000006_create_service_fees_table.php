<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * সার্ভিস ফি - যেমন মোবাইল রিচার্জ, বিল পে, ক্যাশ আউট ইত্যাদি সার্ভিস
     * (কোনো প্রোডাক্ট/স্টক জড়িত না) বিক্রি করার হিসাব। এখানকার কমিশন সরাসরি প্রফিটে যোগ হয়।
     */
    public function up(): void
    {
        Schema::create('service_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->string('service_name');
            $table->string('mobile_number')->nullable();
            $table->decimal('sale_price', 12, 2)->default(0);
            $table->decimal('commission', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_fees');
    }
};
