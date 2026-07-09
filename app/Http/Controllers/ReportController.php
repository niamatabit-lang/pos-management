<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\ServiceFee;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    /**
     * নির্দিষ্ট তারিখ রেঞ্জের সেল, প্রফিট এবং কি কি প্রোডাক্ট বিক্রি হলো তার রিপোর্ট।
     */
    public function index(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        [$dateFrom, $dateTo] = $this->resolveDateRange($request);

        $sales = Sale::where('shop_id', $shopId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        $saleIds = $sales->pluck('id');

        $totalSales = $sales->sum('total');
        $totalDiscount = $sales->sum('discount');
        $totalPaid = $sales->sum('paid_amount');
        $totalDue = $sales->sum('due_amount');
        $totalInvoices = $sales->count();

        $items = SaleItem::whereIn('sale_id', $saleIds)->get();

        $productProfit = $items->sum(fn ($item) => (($item->unit_price - $item->buy_price) + $item->commission) * $item->quantity);
        $totalItemsSold = $items->sum('quantity');

        // এই সময়ের সার্ভিস ফি (কমিশন সরাসরি প্রফিটে যোগ হয়)
        $serviceFees = ServiceFee::where('shop_id', $shopId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();
        $serviceFeeCommission = $serviceFees->sum('commission');
        $serviceFeeSales = $serviceFees->sum('sale_price');

        // এই সময়ের খরচ - প্রফিট থেকে বাদ যাবে
        $totalExpense = (float) Expense::where('shop_id', $shopId)
            ->whereBetween('date', [$dateFrom->toDateString(), $dateTo->toDateString()])
            ->sum('amount');

        $totalProfit = $productProfit + $serviceFeeCommission - $totalExpense;

        // প্রোডাক্ট অনুযায়ী গ্রুপ করে - কোন প্রোডাক্ট কত বিক্রি হলো, কত লাভ হলো
        $productWise = $items->groupBy('product_id')->map(function ($group) {
            $first = $group->first();

            return [
                'product_name' => $first->product_name,
                'quantity' => $group->sum('quantity'),
                'revenue' => $group->sum('subtotal'),
                'profit' => $group->sum(fn ($item) => (($item->unit_price - $item->buy_price) + $item->commission) * $item->quantity),
            ];
        })->sortByDesc('quantity')->values();

        // দৈনিক সেলস ট্রেন্ড (চার্টের জন্য) - date_from থেকে date_to পর্যন্ত প্রতিদিনের মোট বিক্রি
        $dailyTrend = $sales->groupBy(fn ($sale) => $sale->created_at->format('Y-m-d'))
            ->map(fn ($group) => (float) $group->sum('total'));

        $trendLabels = [];
        $trendValues = [];
        $cursor = $dateFrom->copy()->startOfDay();
        while ($cursor->lte($dateTo)) {
            $key = $cursor->format('Y-m-d');
            $trendLabels[] = $cursor->format('d M');
            $trendValues[] = (float) ($dailyTrend[$key] ?? 0);
            $cursor->addDay();
        }

        return view('reports.index', compact(
            'dateFrom',
            'dateTo',
            'totalSales',
            'totalDiscount',
            'totalPaid',
            'totalDue',
            'totalInvoices',
            'totalProfit',
            'totalItemsSold',
            'totalExpense',
            'productWise',
            'sales',
            'serviceFeeCommission',
            'serviceFeeSales',
            'trendLabels',
            'trendValues'
        ));
    }

    // রিপোর্ট CSV আকারে এক্সপোর্ট করা
    public function export(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        [$dateFrom, $dateTo] = $this->resolveDateRange($request);

        $sales = Sale::where('shop_id', $shopId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at')
            ->get();

        $filename = 'sales-report-' . $dateFrom->format('Y-m-d') . '-to-' . $dateTo->format('Y-m-d') . '.csv';

        $callback = function () use ($sales) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Invoice No', 'Date', 'Customer', 'Subtotal', 'Discount', 'Total', 'Paid', 'Due', 'Status']);

            foreach ($sales as $sale) {
                fputcsv($handle, [
                    $sale->invoice_no,
                    $sale->created_at->format('Y-m-d H:i'),
                    $sale->customer_name ?? 'Walk-in',
                    $sale->subtotal,
                    $sale->discount,
                    $sale->total,
                    $sale->paid_amount,
                    $sale->due_amount,
                    $sale->payment_status,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    private function resolveDateRange(Request $request): array
    {
        // ডিফল্ট হিসেবে এই মাসের শুরু থেকে আজকে পর্যন্ত দেখানো হচ্ছে
        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : Carbon::now()->startOfMonth();

        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : Carbon::now()->endOfDay();

        return [$dateFrom, $dateTo];
    }
}
