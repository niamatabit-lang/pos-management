<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ProfitWithdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfitWithdrawalController extends Controller
{
    /**
     * মালিক প্রফিট থেকে টাকা তুললে সেটার এন্ট্রি এখানে হয়।
     *
     * এই টাকা "খরচ" না — তাই এটা কখনো নীট প্রফিটের হিসাব থেকে বাদ যাবে না,
     * শুধু "কত লাভ তোলা হলো" সেটার আলাদা লগ থাকবে। তবে দোকানের ক্যাশ বক্স
     * থেকে বাস্তবেই টাকা কমে যায়, তাই shops.current_cash থেকে এই পরিমাণ
     * বিয়োগ করে দেয়া হচ্ছে যাতে ক্যাশের হিসাব বাস্তবতার সাথে মিলে থাকে।
     */
    public function store(Request $request)
    {
        $shop = $request->attributes->get('currentShop');

        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'date' => 'nullable|date',
            'note' => 'nullable|string|max:255',
        ]);

        $data['shop_id'] = $shop->id;
        $data['date'] = $data['date'] ?? now()->toDateString();
        $data['withdrawn_by'] = Auth::id();

        $withdrawal = ProfitWithdrawal::create($data);

        // ক্যাশ বক্স থেকে টাকা কমে গেলো (মাইনাসে যেতে দেয়া হচ্ছে না, ০ পর্যন্ত সীমিত)
        $shop->update([
            'current_cash' => max(0, (float) $shop->current_cash - (float) $withdrawal->amount),
        ]);

        ActivityLog::log(
            $shop->id,
            'created',
            'ProfitWithdrawal',
            $withdrawal->id,
            __('app.log_profit_withdrawn', ['amount' => number_format($withdrawal->amount, 2)])
        );

        return redirect()->route('finance.index')->with('success', __('app.profit_withdrawal_added'));
    }

    public function destroy(Request $request, ProfitWithdrawal $profitWithdrawal)
    {
        $shop = $request->attributes->get('currentShop');
        abort_unless($profitWithdrawal->shop_id === $shop->id, 404);

        // এন্ট্রি ডিলিট করলে তোলা টাকাটা আবার ক্যাশে ফেরত যোগ হবে (ভুল এন্ট্রি ঠিক করার জন্য)
        $shop->update([
            'current_cash' => (float) $shop->current_cash + (float) $profitWithdrawal->amount,
        ]);

        $profitWithdrawal->delete();

        return redirect()->route('finance.index')->with('success', __('app.profit_withdrawal_deleted'));
    }
}
