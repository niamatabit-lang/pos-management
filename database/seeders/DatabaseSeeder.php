<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * প্রথমবার সিস্টেম চালু করার জন্য একটা ডিফল্ট Super Admin আইডি তৈরি করা হচ্ছে।
     * লগইনের পর অবশ্যই পাসওয়ার্ড পরিবর্তন করে নিবেন।
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@posmanagement.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('admin1234'),
                'role' => User::ROLE_SUPER_ADMIN,
                'is_active' => true,
            ]
        );
    }
}
