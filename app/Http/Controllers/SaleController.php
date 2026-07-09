<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $query = Sale::where('shop_id', $shopId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $sales = $query->latest()->paginate(15)->withQueryString();

        return view('sales.index', compact('sales'));
    }

    public function create(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;
        $products = Product::where('shop_id', $shopId)->orderBy('name')->get();

        $productsForJs = $products->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'price' => (float) $p->sell_price,
                'stock' => (int) $p->quantity,
                'unit' => $p->unit,
            ];
        })->values();

        return view('sales.create', compact('products', 'productsForJs'));
    }

    public function store(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $data = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $sale = DB::transaction(function () use ($data, $shopId) {
                $subtotal = 0;
                $lockedProducts = [];
                $lineItems = [];

                foreach ($data['items'] as $item) {
                    // পণ্যটি লক করা হচ্ছে যাতে একসাথে একাধিক সেলে স্টক ভুল না হয়
                    $product = Product::where('id', $item['product_id'])
                        ->where('shop_id', $shopId)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $existingQty = $lockedProducts[$product->id]['qty'] ?? 0;
                    $requestedQty = $existingQty + $item['quantity'];

                    if ($product->quantity < $requestedQty) {
                        throw new \RuntimeException($product->name . ' এর পর্যাপ্ত স্টক নেই। বর্তমান স্টকঃ ' . $product->quantity);
                    }

                    $lockedProducts[$product->id] = [
                        'product' => $product,
                        'qty' => $requestedQty,
                    ];

                    $lineSubtotal = $product->sell_price * $item['quantity'];
                    $subtotal += $lineSubtotal;

                    $lineItems[] = [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->sell_price,
                        'buy_price' => $product->buy_price,
                        'commission' => $product->commission,
                        'subtotal' => $lineSubtotal,
                    ];
                }

                $discount = $data['discount'] ?? 0;
                $total = max($subtotal - $discount, 0);
                $paidAmount = $data['paid_amount'] ?? $total;
                $dueAmount = max($total - $paidAmount, 0);

                if ($paidAmount <= 0) {
                    $paymentStatus = 'due';
                } elseif ($dueAmount > 0) {
                    $paymentStatus = 'partial';
                } else {
                    $paymentStatus = 'paid';
                }

                // shop_id বসানো হচ্ছে যাতে দুইটা ভিন্ন শপে একই দিনে প্রথম সেল করলে
                // ইনভয়েস নম্বর একই হয়ে গিয়ে unique constraint এরর না হয়
                $invoiceNo = 'INV-' . $shopId . '-' . now()->format('Ymd') . '-' .
                    str_pad((string) (Sale::where('shop_id', $shopId)->whereDate('created_at', today())->count() + 1), 4, '0', STR_PAD_LEFT);

                $sale = Sale::create([
                    'shop_id' => $shopId,
                    'invoice_no' => $invoiceNo,
                    'customer_name' => $data['customer_name'] ?? null,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'total' => $total,
                    'paid_amount' => $paidAmount,
                    'due_amount' => $dueAmount,
                    'payment_status' => $paymentStatus,
                ]);

                foreach ($lineItems as $line) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $line['product']->id,
                        'product_name' => $line['product']->name,
                        'unit_price' => $line['unit_price'],
                        'buy_price' => $line['buy_price'],
                        'commission' => $line['commission'],
                        'quantity' => $line['quantity'],
                        'subtotal' => $line['subtotal'],
                    ]);

                    $line['product']->decrement('quantity', $line['quantity']);

                    // স্টক মুভমেন্ট হিসেবে লগ রাখা হচ্ছে, যাতে Stock module এ ইতিহাস দেখা যায়
                    StockMovement::create([
                        'shop_id' => $shopId,
                        'product_id' => $line['product']->id,
                        'type' => 'out',
                        'quantity' => $line['quantity'],
                        'note' => 'বিক্রয় - ' . $invoiceNo,
                    ]);
                }

                return $sale;
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        ActivityLog::log($shopId, 'sold', 'Sale', $sale->id, "বিক্রয় {$sale->invoice_no} সম্পন্ন হয়েছে (মোটঃ ৳" . number_format($sale->total, 2) . ")");

        return redirect()->route('sales.show', $sale)->with('success', 'বিক্রয় সম্পন্ন হয়েছে।');
    }

    public function show(Request $request, Sale $sale)
    {
        abort_unless($sale->shop_id === $request->attributes->get('currentShop')->id, 404);

        $sale->load('items');
        return view('sales.show', compact('sale'));
    }

    // গ্রাহকের পাওনা (বকেয়া) থেকে টাকা আদায় হলে সেটা রেকর্ড করা
    public function recordDuePayment(Request $request, Sale $sale)
    {
        abort_unless($sale->shop_id === $request->attributes->get('currentShop')->id, 404);

        $data = $request->validate([
            'payment_amount' => 'required|numeric|min:0.01|max:' . $sale->due_amount,
        ]);

        $newPaid = $sale->paid_amount + $data['payment_amount'];
        $newDue = max($sale->due_amount - $data['payment_amount'], 0);

        $sale->update([
            'paid_amount' => $newPaid,
            'due_amount' => $newDue,
            'payment_status' => $newDue <= 0 ? 'paid' : 'partial',
        ]);

        ActivityLog::log($sale->shop_id, 'payment_received', 'Sale', $sale->id, "{$sale->invoice_no} এর পাওনা থেকে ৳" . number_format($data['payment_amount'], 2) . " আদায় হয়েছে");

        return redirect()->route('finance.index')->with('success', 'পাওনার টাকা আদায় রেকর্ড করা হয়েছে।');
    }
}