<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\ServiceFee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        // ইউজার তারিখ সিলেক্ট করলে সেই তারিখের হিসাব দেখাবে, না করলে আজকের তারিখ (ডিফল্ট)
        $selectedDate = $request->filled('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::today();

        // সিলেক্ট করা তারিখের বিক্রি হওয়া সেলগুলো
        $daySales = Sale::where('shop_id', $shopId)
            ->whereDate('created_at', $selectedDate)
            ->get();

        // সিলেক্ট করা তারিখের মোট বিক্রয়
        $daySalesTotal = $daySales->sum('total');

        // সেদিন ক্যাশে (হাতে) কত টাকা এসেছে
        $dayCash = $daySales->sum('paid_amount');

        // সেদিনের প্রফিট = (বিক্রয়মূল্য - ক্রয়মূল্য + কমিশন) x পরিমাণ, প্রতিটা আইটেমের জন্য
        $dayProductProfit = SaleItem::whereIn('sale_id', $daySales->pluck('id'))
            ->get()
            ->sum(fn ($item) => (($item->unit_price - $item->buy_price) + $item->commission) * $item->quantity);

        // সেদিনের সার্ভিস ফি (কমিশন সরাসরি প্রফিটে যোগ হয়)
        $dayServiceFees = ServiceFee::where('shop_id', $shopId)->whereDate('created_at', $selectedDate)->get();
        $dayServiceFeeCommission = $dayServiceFees->sum('commission');
        $dayCash += $dayServiceFees->sum('sale_price');

        // সেদিনের খরচ (ভাড়া, বেতন, বিল ইত্যাদি) প্রফিট থেকে বাদ যাবে
        $dayExpense = (float) Expense::where('shop_id', $shopId)->whereDate('date', $selectedDate)->sum('amount');

        $dayProfit = $dayProductProfit + $dayServiceFeeCommission - $dayExpense;

        // বর্তমানে স্টকে যা আছে (এটা সবসময় "এখনকার" স্টক দেখায়, তারিখ অনুযায়ী বদলায় না)
        $stockProducts = Product::where('shop_id', $shopId)
            ->orderBy('name')
            ->get();

        // স্টকে মোট কত টাকার প্রোডাক্ট আছে (quantity x buy_price যোগ করে)
        $totalStockValue = $stockProducts->sum(fn ($p) => $p->quantity * $p->buy_price);

        // কম স্টক / স্টক শেষ হয়ে যাওয়া প্রোডাক্ট - এলার্টের জন্য
        $lowStockProducts = $stockProducts->filter(fn ($p) => $p->isLowStock())->values();

        $isToday = $selectedDate->isToday();

        return view('dashboard.index', compact(
            'selectedDate',
            'isToday',
            'daySalesTotal',
            'dayCash',
            'dayProfit',
            'dayExpense',
            'stockProducts',
            'totalStockValue',
            'lowStockProducts'
        ));
    }
}
