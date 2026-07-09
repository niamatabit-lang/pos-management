<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * পাওনা (Receivables) - বিক্রয়ের বাইরে কাউকে ধারে টাকা দেয়া থাকলে বা
     * অন্য কোনো কারণে কারো কাছে টাকা পাওনা থাকলে সেটার হিসাব।
     * এটা গ্রাহকের বাকি বিক্রয় (Sale.due_amount, যেটা ইতিমধ্যে আছে) থেকে আলাদা —
     * সেটা বিক্রয়ের সাথে জড়িত, এটা ম্যানুয়ালি যোগ করা যেকোনো পাওনা।
     */
    public function up(): void
    {
        Schema::create('receivables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->string('party_name'); // কার কাছে পাওনা
            $table->decimal('amount', 12, 2); // মোট পাওনার পরিমাণ
            $table->decimal('paid_amount', 12, 2)->default(0); // এখন পর্যন্ত যা আদায় হয়েছে
            $table->date('date')->nullable(); // পাওনা তৈরির তারিখ
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receivables');
    }
};
