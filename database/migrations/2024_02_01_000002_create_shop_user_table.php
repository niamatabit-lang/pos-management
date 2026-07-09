<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Manager/Employee এর জন্য - আইডি বানানোর সময় কোন কোন কাজ করতে পারবে তার তালিকা
            // (Shop Owner এর জন্য এটা null থাকবে, কারণ Owner এর নিজের শপে সব এক্সেস থাকে)
            $table->json('permissions')->nullable();

            $table->timestamps();

            $table->unique(['shop_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_user');
    }
};
