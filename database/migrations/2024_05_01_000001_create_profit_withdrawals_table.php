<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * মালিক দোকানের লাভ থেকে যে টাকা নিজের জন্য তুলে নেন, তার হিসাব।
     *
     * গুরুত্বপূর্ণঃ এই টাকা তোলা কোনো "খরচ" (expense) না — এটা দোকানের ব্যবসায়িক
     * লাভ-ক্ষতির হিসাব থেকে আলাদা, তাই এটা কখনোই expenses টেবিলে যাবে না এবং
     * নীট প্রফিট (sales - cogs - expense) হিসাবেও এটা যোগ/বিয়োগ হবে না।
     * শুধু "কত লাভ তোলা হয়েছে, কত এখনো তোলা বাকি (available)" — এটা বোঝার জন্য
     * আলাদা টেবিলে রাখা হচ্ছে। টাকা তোলার সাথে সাথে shops.current_cash থেকে
     * সমপরিমাণ বিয়োগ হয়ে যাবে (কারণ ক্যাশ বক্স থেকে সত্যিকারের টাকা কমে গেলো)।
     */
    public function up(): void
    {
        Schema::create('profit_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('date');
            $table->string('note')->nullable();
            $table->foreignId('withdrawn_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profit_withdrawals');
    }
};
