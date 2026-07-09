<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * গ্রাহক পণ্য ফেরত দিলে তার হিস্ট্রি/অডিট রাখার জন্য। আসল হিসাব
     * (sale_items.quantity, sales.total ইত্যাদি) সরাসরি আপডেট হয়ে যায়, এই টেবিলটা
     * শুধু "কবে কী কারণে কত টাকার রিটার্ন হলো" তার লগ হিসেবে থাকে।
     */
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_item_id')->constrained('sale_items')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('refund_amount', 12, 2);
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
