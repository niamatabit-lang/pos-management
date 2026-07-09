<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Return_;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $sale = null;

        if ($request->filled('invoice')) {
            $sale = Sale::where('shop_id', $shopId)
                ->where('invoice_no', $request->invoice)
                ->with('items')
                ->first();
        }

        $recentReturns = Return_::where('shop_id', $shopId)
            ->with(['sale', 'product'])
            ->latest()
            ->limit(20)
            ->get();

        return view('returns.index', compact('sale', 'recentReturns'));
    }

    /**
     * প্রোডাক্ট রিটার্ন প্রসেস করা হচ্ছে - স্টক ফিরিয়ে দেয়া হয়, সেলের হিসাব
     * (সাবটোটাল/টোটাল/পেইড/ডিউ) নতুন করে সমন্বয় করা হয়।
     */
    public function store(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $data = $request->validate([
            'sale_item_id' => 'required|exists:sale_items,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        $saleItem = SaleItem::with('sale')->findOrFail($data['sale_item_id']);
        abort_unless($saleItem->sale->shop_id === $shopId, 404);

        if ($data['quantity'] > $saleItem->quantity) {
            return back()->with('error', 'এত পরিমাণ রিটার্ন করা যাবে না। সর্বোচ্চ ' . $saleItem->quantity . ' টা ফেরত দেয়া যাবে।');
        }

        DB::transaction(function () use ($saleItem, $data, $shopId) {
            $sale = $saleItem->sale;
            $returnAmount = $saleItem->unit_price * $data['quantity'];

            // ১. সেল আইটেম থেকে পরিমাণ কমানো
            $saleItem->quantity -= $data['quantity'];
            $saleItem->subtotal = $saleItem->unit_price * $saleItem->quantity;
            $saleItem->save();

            // ২. সেলের সামগ্রিক হিসাব সমন্বয় করা - আগে বকেয়া থেকে বাদ, তারপর পেইড থেকে (ক্যাশ রিফান্ড)
            $reduceDue = min($sale->due_amount, $returnAmount);
            $remaining = $returnAmount - $reduceDue;

            $sale->due_amount -= $reduceDue;
            $sale->paid_amount -= $remaining;
            $sale->subtotal -= $returnAmount;
            $sale->total = max($sale->subtotal - $sale->discount, 0);
            $sale->payment_status = $sale->due_amount <= 0 ? 'paid' : 'partial';
            $sale->save();

            // ৩. স্টকে প্রোডাক্ট ফেরত যোগ করা
            if ($saleItem->product_id) {
                $product = $saleItem->product;
                if ($product) {
                    $product->increment('quantity', $data['quantity']);

                    StockMovement::create([
                        'shop_id' => $shopId,
                        'product_id' => $product->id,
                        'type' => 'in',
                        'quantity' => $data['quantity'],
                        'note' => 'রিটার্ন - ' . $sale->invoice_no,
                    ]);
                }
            }

            // ৪. অডিট/হিস্ট্রির জন্য রিটার্ন এন্ট্রি
            Return_::create([
                'shop_id' => $shopId,
                'sale_id' => $sale->id,
                'sale_item_id' => $saleItem->id,
                'product_id' => $saleItem->product_id,
                'quantity' => $data['quantity'],
                'refund_amount' => $returnAmount,
                'reason' => $data['reason'] ?? null,
            ]);

            ActivityLog::log($shopId, 'returned', 'Sale', $sale->id,
                "{$sale->invoice_no} থেকে '{$saleItem->product_name}' এর {$data['quantity']} টা রিটার্ন করা হয়েছে (৳" . number_format($returnAmount, 2) . ")");
        });

        return redirect()->route('returns.index', ['invoice' => $saleItem->sale->invoice_no])
            ->with('success', 'রিটার্ন সফলভাবে প্রসেস করা হয়েছে।');
    }
}
