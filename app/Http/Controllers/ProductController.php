<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $query = Product::with('category')->where('shop_id', $shopId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('low_stock')) {
            $query->whereColumn('quantity', '<=', 'reorder_level');
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::where('shop_id', $shopId)->orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;
        $categories = Category::where('shop_id', $shopId)->orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'required', 'string', 'max:100',
                // SKU শুধু নিজের শপের মধ্যে ইউনিক হতে হবে, অন্য শপে একই SKU ব্যবহার করা যাবে
                Rule::unique('products', 'sku')->where('shop_id', $shopId),
            ],
            'category_id' => 'nullable|exists:categories,id',
            'unit' => 'required|string|max:50',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'commission' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
        ]);

        $data['shop_id'] = $shopId;
        $data['commission'] = $data['commission'] ?? 0;

        $product = Product::create($data);

        ActivityLog::log($shopId, 'created', 'Product', $product->id, "প্রোডাক্ট '{$product->name}' যোগ করা হয়েছে (স্টকঃ {$product->quantity})");

        return redirect()->route('products.index')->with('success', 'প্রোডাক্ট সফলভাবে যোগ হয়েছে।');
    }

    public function edit(Request $request, Product $product)
    {
        $shopId = $request->attributes->get('currentShop')->id;
        $categories = Category::where('shop_id', $shopId)->orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'required', 'string', 'max:100',
                Rule::unique('products', 'sku')->where('shop_id', $product->shop_id)->ignore($product->id),
            ],
            'category_id' => 'nullable|exists:categories,id',
            'unit' => 'required|string|max:50',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'commission' => 'nullable|numeric|min:0',
            'reorder_level' => 'required|integer|min:0',
        ]);

        $data['commission'] = $data['commission'] ?? 0;

        $product->update($data);

        ActivityLog::log($product->shop_id, 'updated', 'Product', $product->id, "প্রোডাক্ট '{$product->name}' আপডেট করা হয়েছে");

        return redirect()->route('products.index')->with('success', 'প্রোডাক্ট আপডেট হয়েছে।');
    }

    public function destroy(Product $product)
    {
        ActivityLog::log($product->shop_id, 'deleted', 'Product', $product->id, "প্রোডাক্ট '{$product->name}' মুছে ফেলা হয়েছে");
        $product->delete();
        return redirect()->route('products.index')->with('success', 'প্রোডাক্ট মুছে ফেলা হয়েছে।');
    }

    /**
     * নতুন প্রোডাক্ট এড করলে স্টক যোগ হয়, কিন্তু বিদ্যমান প্রোডাক্টের স্টক ফুরিয়ে গেলে/কমে গেলে
     * আবার মাল আনলে (রিস্টক) এখান থেকে স্টক বাড়ানো যাবে। সাথে চাইলে নতুন ক্রয়মূল্যও (দাম
     * ওঠানামা করলে) আপডেট করা যাবে।
     */
    public function restock(Request $request, Product $product)
    {
        $shopId = $request->attributes->get('currentShop')->id;
        abort_unless($product->shop_id === $shopId, 404);

        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'new_buy_price' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($data, $product) {
            \App\Models\StockMovement::create([
                'shop_id' => $product->shop_id,
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $data['quantity'],
                'note' => $data['note'] ?? 'রিস্টক',
            ]);

            $product->increment('quantity', $data['quantity']);

            if (isset($data['new_buy_price'])) {
                $product->update(['buy_price' => $data['new_buy_price']]);
            }
        });

        ActivityLog::log($product->shop_id, 'restocked', 'Product', $product->id, "'{$product->name}' এ {$data['quantity']} {$product->unit} রিস্টক করা হয়েছে");

        return redirect()->route('products.edit', $product)->with('success', 'স্টক যোগ করা হয়েছে।');
    }
}
