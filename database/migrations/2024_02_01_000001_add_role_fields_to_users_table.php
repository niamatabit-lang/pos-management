<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // super_admin = সবকিছুর মালিক, shop_owner = এক বা একাধিক শপের মালিক,
            // manager/employee = নির্দিষ্ট শপে টিকমার্ক করা পারমিশন অনুযায়ী কাজ করবে
            $table->enum('role', ['super_admin', 'shop_owner', 'manager', 'employee'])
                ->default('employee')
                ->after('email');

            // কে এই ইউজারটা তৈরি করেছে (super_admin শপ ওনার বানালে, শপ ওনার ম্যানেজার/এমপ্লয়ি বানালে)
            $table->foreignId('created_by')->nullable()->after('role')
                ->constrained('users')->nullOnDelete();

            // অ্যাকাউন্ট বন্ধ/চালু রাখার জন্য (ডিলিট না করে ডিএক্টিভেট করা যাবে)
            $table->boolean('is_active')->default(true)->after('created_by');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
            $table->dropColumn(['role', 'is_active']);
        });
    }
};
