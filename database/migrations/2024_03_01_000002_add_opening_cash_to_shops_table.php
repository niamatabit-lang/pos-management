<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            // অনবোর্ডিং এর সময় শপ ওনারকে যে ক্যাশ (মূলধন) দেয়া হয়েছিল
            $table->decimal('opening_cash', 12, 2)->default(0)->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('opening_cash');
        });
    }
};
