<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $categories = Category::withCount('products')
            ->where('shop_id', $shopId)
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('categories', 'name')->where('shop_id', $shopId),
            ],
        ]);

        Category::create([
            'shop_id' => $shopId,
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'ক্যাটাগরি যোগ হয়েছে।');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'ক্যাটাগরি মুছে ফেলা হয়েছে।');
    }
}
