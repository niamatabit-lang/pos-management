<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * কে (কোন ইউজার) কখন কী করলো তার অ্যাক্টিভিটি লগ - মালিক ও ম্যানেজার
     * একই ডেটা শেয়ার করে বলে এটা থাকলে কার কাজ কী তা সহজে ট্র্যাক করা যাবে।
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // created, updated, deleted, sold, returned, paid ইত্যাদি
            $table->string('subject_type')->nullable(); // যেমনঃ Product, Sale, Payable
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
