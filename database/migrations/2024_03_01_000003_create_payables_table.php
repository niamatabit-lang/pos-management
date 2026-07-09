<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * দেনা (Payables) - শপ থেকে সাপ্লায়ার/অন্য কাউকে যে টাকা দেয়া বাকি আছে।
     * এটা গ্রাহকের পাওনা (Sale.due_amount, যেটা ইতিমধ্যে আছে) থেকে আলাদা।
     */
    public function up(): void
    {
        Schema::create('payables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->string('party_name'); // কাকে দেনা (সাপ্লায়ার/ব্যক্তির নাম)
            $table->decimal('amount', 12, 2); // মোট দেনার পরিমাণ
            $table->decimal('paid_amount', 12, 2)->default(0); // এখন পর্যন্ত যা শোধ করা হয়েছে
            $table->date('date')->nullable(); // দেনা তৈরির তারিখ
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payables');
    }
};
