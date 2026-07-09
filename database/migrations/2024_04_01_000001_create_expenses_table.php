<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * খরচ (দোকান ভাড়া, বেতন, বিদ্যুৎ বিল ইত্যাদি) - এগুলো এখন পর্যন্ত সিস্টেমে
     * কোথাও রেকর্ড হতো না, ফলে আসল প্রফিট/ক্যাশ হিসাব অসম্পূর্ণ থাকতো।
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->string('title'); // যেমনঃ দোকান ভাড়া, বিদ্যুৎ বিল, বেতন
            $table->string('category')->nullable(); // ভাড়া/বেতন/বিল/অন্যান্য
            $table->decimal('amount', 12, 2);
            $table->date('date');
            $table->string('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
