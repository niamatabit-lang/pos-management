<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{
    public function index()
    {
        $authUser = Auth::user();

        // Super Admin সব শপ দেখবে, Shop Owner শুধু নিজের এসাইন করা শপ দেখবে
        $shops = $authUser->isSuperAdmin()
            ? Shop::withCount(['products', 'sales'])->orderBy('name')->get()
            : $authUser->shops()->withCount(['products', 'sales'])->orderBy('name')->get();

        // শপ বানানোর সময় ওনার সিলেক্ট করার জন্য - শুধু সুপার এডমিনের জন্য দরকার
        $owners = $authUser->isSuperAdmin()
            ? User::where('role', User::ROLE_SHOP_OWNER)->orderBy('name')->get()
            : collect();

        return view('shops.index', compact('shops', 'owners'));
    }

    public function store(Request $request)
    {
        // নতুন শপ শুধু সুপার এডমিনই বানাতে পারবে
        abort_unless(Auth::user()->isSuperAdmin(), 403, 'নতুন শপ তৈরি করার অনুমতি শুধু সুপার এডমিনের আছে।');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'owner_ids' => 'nullable|array',
            'owner_ids.*' => 'exists:users,id',
        ]);

        $shop = Shop::create([
            'name' => $data['name'],
            'address' => $data['address'] ?? null,
            'phone' => $data['phone'] ?? null,
        ]);

        if (! empty($data['owner_ids'])) {
            $shop->users()->attach($data['owner_ids'], ['permissions' => null]);
        }

        return redirect()->route('shops.index')->with('success', 'নতুন শপ যোগ হয়েছে।');
    }

    public function destroy(Shop $shop)
    {
        abort_unless(Auth::user()->isSuperAdmin(), 403, 'শপ ডিলিট করার অনুমতি শুধু সুপার এডমিনের আছে।');

        if (Shop::count() <= 1) {
            return back()->with('error', 'অন্তত একটা শপ থাকতেই হবে, এটা মুছা যাবে না।');
        }

        $shop->delete();

        if (Session::get('current_shop_id') == $shop->id) {
            Session::forget('current_shop_id');
        }

        return redirect()->route('shops.index')->with('success', 'শপ মুছে ফেলা হয়েছে।');
    }

    public function switch(Request $request)
    {
        $request->validate(['shop_id' => 'required|exists:shops,id']);

        // ইউজার যেন নিজের এক্সেস নেই এমন শপে জোর করে সুইচ করতে না পারে
        abort_unless(Auth::user()->hasAccessToShop((int) $request->shop_id), 403);

        Session::put('current_shop_id', $request->shop_id);

        return back()->with('success', 'শপ পরিবর্তন হয়েছে।');
    }
}
