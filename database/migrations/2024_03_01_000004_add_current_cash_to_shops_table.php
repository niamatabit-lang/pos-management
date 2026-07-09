<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            // বর্তমানে হাতে/ক্যাশ বক্সে যত টাকা আছে তা মালিক নিজে গুণে এখানে আপডেট করবেন।
            // এটা সিস্টেম অনুমান করে না, কারণ ক্যাশের হিসাব সিস্টেমের বাইরের অনেক খরচ/আয়ের
            // উপরও নির্ভর করে যা POS ট্র্যাক করে না।
            $table->decimal('current_cash', 12, 2)->default(0)->after('opening_cash');
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('current_cash');
        });
    }
};
