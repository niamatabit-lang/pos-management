<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payable;
use App\Models\Product;
use App\Models\ProfitWithdrawal;
use App\Models\Receivable;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\ServiceFee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FinanceController extends Controller
{
    /**
     * দেনা-পাওনার ড্যাশবোর্ড।
     *
     * হিসাবঃ বর্তমান ক্যাশ (মালিক নিজে গুণে আপডেট করেন) + বর্তমান স্টক ভ্যালু
     * + গ্রাহকের কাছে পাওনা (বকেয়া) - অন্যকে দেনা = নীট অবস্থান।
     *
     * এই নীট অবস্থান অনবোর্ডিং ক্যাশের (শুরুতে দেয়া মূলধন) সমান হওয়ার কথা যদি
     * ব্যবসায় কোনো লাভ/লস না হয়ে থাকে। এর চেয়ে কম হলে সেটা ঘাটতি (শর্ট),
     * বেশি হলে সেটা এক্সট্রা (লাভ/বৃদ্ধি)।
     */
    public function index(Request $request)
    {
        $shop = $request->attributes->get('currentShop');
        $shopId = $shop->id;

        // ১. বর্তমান স্টকের মোট ক্রয়মূল্য
        $stockValue = (float) Product::where('shop_id', $shopId)
            ->selectRaw('COALESCE(SUM(quantity * buy_price), 0) as total')
            ->value('total');

        // ২. গ্রাহকের কাছে পাওনা (বাকিতে বিক্রি করা টাকা, এখনো যা আদায় হয়নি) - প্রতিটা বিক্রয়ের তালিকাসহ
        $receivables = Sale::where('shop_id', $shopId)
            ->where('due_amount', '>', 0)
            ->orderByDesc('created_at')
            ->get();

        // ২ক. বিক্রয়ের বাইরে ম্যানুয়ালি যোগ করা পাওনা (কাউকে ধারে টাকা দেয়া থাকলে)
        $manualReceivables = Receivable::where('shop_id', $shopId)->orderByDesc('date')->get();

        $totalReceivable = (float) $receivables->sum('due_amount')
            + (float) $manualReceivables->sum(fn ($r) => $r->dueAmount());

        // ৩. এই শপ থেকে অন্য কাউকে দেনা (সাপ্লায়ার ইত্যাদি)
        $payables = Payable::where('shop_id', $shopId)->orderByDesc('date')->get();
        $totalPayable = (float) $payables->sum(fn ($p) => $p->dueAmount());

        // ৪. বর্তমান ক্যাশ - মালিক নিজে ক্যাশ বক্স গুণে যা এন্টার করেছেন
        $currentCash = (float) $shop->current_cash;

        // ৫. নীট অবস্থান = ক্যাশ + স্টক ভ্যালু + পাওনা - দেনা
        $netPosition = $currentCash + $stockValue + $totalReceivable - $totalPayable;

        // ৬. অনবোর্ডিং ক্যাশের সাথে তুলনা - কম হলে শর্ট, বেশি হলে এক্সট্রা
        $difference = $netPosition - (float) $shop->opening_cash;
        $isShort = $difference < -0.009;
        $isExtra = $difference > 0.009;

        // ৭. চলতি মাসের প্রফিট (প্রোডাক্ট প্রফিট + কমিশন + সার্ভিস ফি কমিশন)
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $monthSaleIds = Sale::where('shop_id', $shopId)
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->pluck('id');

        $currentMonthProductProfit = (float) SaleItem::whereIn('sale_id', $monthSaleIds)
            ->get()
            ->sum(fn ($item) => (($item->unit_price - $item->buy_price) + $item->commission) * $item->quantity);

        $currentMonthServiceFeeCommission = (float) ServiceFee::where('shop_id', $shopId)
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('commission');

        $currentMonthExpense = (float) Expense::where('shop_id', $shopId)
            ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->sum('amount');

        $currentMonthProfit = $currentMonthProductProfit + $currentMonthServiceFeeCommission - $currentMonthExpense;

        $currentMonthSales = (float) Sale::where('shop_id', $shopId)
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('total');

        // ৮. মালিকের প্রফিট উইথড্রয়াল (টাকা তোলা) - এটা খরচ না, তাই নীট প্রফিটের হিসাব থেকে বাদ যায় না
        //    বরং শুধু "কত লাভ তোলা হয়েছে, আর কত তোলা যায়" সেটা আলাদাভাবে দেখানো হয়।

        // সর্বমোট (অল-টাইম) নীট প্রফিট = প্রোডাক্ট প্রফিট + সার্ভিস ফি কমিশন - খরচ (সব সময়ের)
        $allSaleIds = Sale::where('shop_id', $shopId)->pluck('id');

        $totalProductProfit = (float) SaleItem::whereIn('sale_id', $allSaleIds)
            ->get()
            ->sum(fn ($item) => (($item->unit_price - $item->buy_price) + $item->commission) * $item->quantity);

        $totalServiceFeeCommission = (float) ServiceFee::where('shop_id', $shopId)->sum('commission');

        $totalExpense = (float) Expense::where('shop_id', $shopId)->sum('amount');

        $totalNetProfit = $totalProductProfit + $totalServiceFeeCommission - $totalExpense;

        $totalWithdrawn = (float) ProfitWithdrawal::where('shop_id', $shopId)->sum('amount');

        // এখনো কত লাভ তোলা বাকি আছে (মালিক আরো কত টাকা তুলতে পারবেন)
        $availableProfit = $totalNetProfit - $totalWithdrawn;

        $profitWithdrawals = ProfitWithdrawal::where('shop_id', $shopId)
            ->with('withdrawnBy')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        return view('finance.index', compact(
            'shop',
            'stockValue',
            'totalReceivable',
            'receivables',
            'manualReceivables',
            'totalPayable',
            'payables',
            'currentCash',
            'netPosition',
            'difference',
            'isShort',
            'isExtra',
            'currentMonthProfit',
            'currentMonthExpense',
            'currentMonthSales',
            'monthStart',
            'monthEnd',
            'totalNetProfit',
            'totalWithdrawn',
            'availableProfit',
            'profitWithdrawals'
        ));
    }

    // অনবোর্ডিং ক্যাশ ও বর্তমান ক্যাশ - দুটোই শুধু Super Admin/Shop Owner আপডেট করতে পারবে
    public function updateCash(Request $request)
    {
        $shop = $request->attributes->get('currentShop');

        $data = $request->validate([
            'opening_cash' => 'required|numeric|min:0',
            'current_cash' => 'required|numeric|min:0',
        ]);

        $shop->update($data);

        return redirect()->route('finance.index')->with('success', 'ক্যাশের তথ্য আপডেট করা হয়েছে।');
    }
}
