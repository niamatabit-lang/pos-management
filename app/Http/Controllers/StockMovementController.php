<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $query = StockMovement::with('product')->where('shop_id', $shopId);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $movements = $query->latest()->paginate(15)->withQueryString();
        $products = Product::where('shop_id', $shopId)->orderBy('name')->get();

        return view('stock.index', compact('movements', 'products'));
    }

    public function create(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;
        $products = Product::where('shop_id', $shopId)->orderBy('name')->get();

        return view('stock.create', compact('products'));
    }

    public function store(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        // নতুন প্রোডাক্ট এড করলেই সেটা স্টকে যোগ হয়ে যায়, তাই এখান থেকে আলাদা করে
        // "স্টক ইন" করার দরকার নেই। এই ফর্ম দিয়ে শুধু স্টক কমানো (নষ্ট/চুরি/সমন্বয়) যাবে।
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'note' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($data, $shopId) {
                // পণ্যটি লক করা হচ্ছে যাতে একসাথে একাধিক রিকোয়েস্টে স্টক ভুল না হয়
                $product = Product::where('id', $data['product_id'])
                    ->where('shop_id', $shopId)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($product->quantity < $data['quantity']) {
                    throw new \RuntimeException('পর্যাপ্ত স্টক নেই। বর্তমান স্টকঃ ' . $product->quantity);
                }

                StockMovement::create([
                    'shop_id' => $shopId,
                    'product_id' => $data['product_id'],
                    'type' => 'out',
                    'quantity' => $data['quantity'],
                    'note' => $data['note'],
                ]);

                $product->decrement('quantity', $data['quantity']);
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('stock.index')->with('success', 'স্টক সমন্বয় সফল হয়েছে।');
    }

    /**
     * নির্দিষ্ট তারিখের জন্য প্রতিটা প্রোডাক্টের ওপেনিং স্টক (দিনের শুরুতে) ও
     * ক্লোজিং স্টক (দিনের শেষে) দেখায়। যেহেতু প্রোডাক্টে শুধু "বর্তমান" quantity
     * সংরক্ষিত থাকে, তাই বর্তমান quantity থেকে ওই তারিখের পরের সব movement
     * উল্টে দিয়ে (reverse করে) হিসাব করা হয়।
     */
    public function dailyLedger(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $date = $request->filled('date')
            ? \Illuminate\Support\Carbon::parse($request->date)->startOfDay()
            : \Illuminate\Support\Carbon::today();

        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        $products = Product::where('shop_id', $shopId)
            ->where('created_at', '<=', $endOfDay)
            ->orderBy('name')
            ->get();

        $rows = $products->map(function ($product) use ($startOfDay, $endOfDay) {
            // দিনের শেষের পরের movement গুলো উল্টিয়ে closing বের করা হচ্ছে
            $netAfterEnd = StockMovement::where('product_id', $product->id)
                ->where('created_at', '>', $endOfDay)
                ->selectRaw("COALESCE(SUM(CASE WHEN type = 'out' THEN quantity ELSE -quantity END), 0) as net")
                ->value('net');

            $closingStock = $product->quantity + $netAfterEnd;

            // প্রোডাক্টটি যদি এই দিনের শুরুর আগেই তৈরি হয়ে থাকে, তাহলে দিনের শুরুর
            // movement গুলোও উল্টিয়ে opening বের করা হবে। নাহলে (এই দিনেই তৈরি হলে) opening = 0
            if ($product->created_at->lt($startOfDay)) {
                $netAfterStart = StockMovement::where('product_id', $product->id)
                    ->where('created_at', '>=', $startOfDay)
                    ->selectRaw("COALESCE(SUM(CASE WHEN type = 'out' THEN quantity ELSE -quantity END), 0) as net")
                    ->value('net');

                $openingStock = $product->quantity + $netAfterStart;
            } else {
                $openingStock = 0;
            }

            // ওই দিনে যত পরিমাণ স্টক কমেছে (বিক্রি + সমন্বয় মিলিয়ে)
            $soldOrAdjustedDuring = StockMovement::where('product_id', $product->id)
                ->where('type', 'out')
                ->whereBetween('created_at', [$startOfDay, $endOfDay])
                ->sum('quantity');

            return [
                'product' => $product,
                'opening' => $openingStock,
                'closing' => $closingStock,
                'out_during' => $soldOrAdjustedDuring,
            ];
        });

        return view('stock.ledger', [
            'date' => $date,
            'rows' => $rows,
        ]);
    }
}
