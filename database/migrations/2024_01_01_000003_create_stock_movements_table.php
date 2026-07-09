<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['in', 'out']); // in = স্টক যোগ, out = স্টক বিয়োগ
            $table->integer('quantity');
            $table->string('note')->nullable(); // যেমনঃ "নতুন পারচেজ", "নষ্ট হয়ে গেছে" ইত্যাদি
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
