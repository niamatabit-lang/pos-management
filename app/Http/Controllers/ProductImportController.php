<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductImportController extends Controller
{
    public function create()
    {
        return view('products.import');
    }

    // নমুনা CSV ফাইল ডাউনলোড করার জন্য
    public function sample()
    {
        $csv = "name,sku,category,unit,buy_price,sell_price,commission,quantity,reorder_level\n";
        $csv .= "চাল (মিনিকেট) ৫ কেজি,RICE-001,খাদ্যপণ্য,pcs,350,380,5,20,5\n";
        $csv .= "সয়াবিন তেল ১ লিটার,OIL-001,খাদ্যপণ্য,pcs,160,175,3,30,10\n";

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="product-import-sample.csv"',
        ]);
    }

    public function store(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file')->getRealPath();
        $rows = array_map('str_getcsv', file($path));
        $header = array_map('trim', array_shift($rows));

        $required = ['name', 'sku', 'unit', 'buy_price', 'sell_price', 'quantity', 'reorder_level'];
        foreach ($required as $col) {
            if (! in_array($col, $header)) {
                return back()->with('error', "CSV ফাইলে '{$col}' কলামটা পাওয়া যায়নি। নমুনা ফাইল ডাউনলোড করে ফরম্যাট মিলিয়ে নিন।");
            }
        }

        $created = 0;
        $skipped = 0;
        $errors = [];

        DB::transaction(function () use ($rows, $header, $shopId, &$created, &$skipped, &$errors) {
            foreach ($rows as $i => $row) {
                if (count($row) < count($header) || trim(implode('', $row)) === '') {
                    continue;
                }

                $line = array_combine($header, $row);

                if (empty($line['name']) || empty($line['sku'])) {
                    $skipped++;
                    $errors[] = "লাইন " . ($i + 2) . ": নাম বা SKU খালি, স্কিপ করা হলো।";
                    continue;
                }

                // একই শপে একই SKU থাকলে স্কিপ করা হচ্ছে (ডুপ্লিকেট এড়াতে)
                if (Product::where('shop_id', $shopId)->where('sku', trim($line['sku']))->exists()) {
                    $skipped++;
                    $errors[] = "লাইন " . ($i + 2) . ": SKU '{$line['sku']}' ইতিমধ্যে আছে, স্কিপ করা হলো।";
                    continue;
                }

                $categoryId = null;
                if (! empty($line['category'])) {
                    $category = Category::firstOrCreate(
                        ['shop_id' => $shopId, 'name' => trim($line['category'])]
                    );
                    $categoryId = $category->id;
                }

                Product::create([
                    'shop_id' => $shopId,
                    'category_id' => $categoryId,
                    'name' => trim($line['name']),
                    'sku' => trim($line['sku']),
                    'unit' => trim($line['unit'] ?? 'pcs'),
                    'buy_price' => (float) ($line['buy_price'] ?? 0),
                    'sell_price' => (float) ($line['sell_price'] ?? 0),
                    'commission' => (float) ($line['commission'] ?? 0),
                    'quantity' => (int) ($line['quantity'] ?? 0),
                    'reorder_level' => (int) ($line['reorder_level'] ?? 5),
                ]);

                $created++;
            }
        });

        ActivityLog::log($shopId, 'imported', 'Product', null, "CSV থেকে {$created} টা প্রোডাক্ট ইমপোর্ট করা হয়েছে ({$skipped} টা স্কিপ)");

        return redirect()->route('products.index')
            ->with('success', "{$created} টা প্রোডাক্ট যোগ হয়েছে। " . ($skipped > 0 ? "{$skipped} টা স্কিপ করা হয়েছে।" : ''))
            ->with('importErrors', $errors);
    }
}
