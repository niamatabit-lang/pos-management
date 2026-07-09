<?php

namespace App\Http\Middleware;

use App\Models\Shop;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentShop
{
    /**
     * লগইন করা ইউজার যেসব শপে এক্সেস পেয়েছে তার মধ্যে থেকে
     * বর্তমানে কোনটা সিলেক্ট করা আছে সেটা ঠিক করে দেয়।
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Super Admin এর সব শপে এক্সেস থাকে, বাকিদের শুধু যা এসাইন করা হয়েছে তাতে
        $shops = $user->isSuperAdmin()
            ? Shop::orderBy('name')->get()
            : $user->shops()->orderBy('name')->get();

        // Super Admin এর জন্য কোনো শপ না থাকলে একটা ডিফল্ট শপ বানিয়ে দেওয়া হচ্ছে
        if ($shops->isEmpty() && $user->isSuperAdmin()) {
            $shop = Shop::create(['name' => 'আমার শপ']);
            $shops = collect([$shop]);
        }

        if ($shops->isEmpty()) {
            abort(403, 'আপনার আইডিতে এখনো কোনো শপের এক্সেস দেওয়া হয়নি। শপ ওনার/এডমিনের সাথে যোগাযোগ করুন।');
        }

        $currentShopId = Session::get('current_shop_id');
        $currentShop = $shops->firstWhere('id', $currentShopId);

        // সেশনে যদি ভ্যালিড (এক্সেসযোগ্য) শপ না থাকে, তাহলে প্রথম শপটাকেই ডিফল্ট ধরা হচ্ছে
        if (! $currentShop) {
            $currentShop = $shops->first();
            Session::put('current_shop_id', $currentShop->id);
        }

        $request->attributes->set('currentShop', $currentShop);

        View::share('currentShop', $currentShop);
        View::share('allShops', $shops);

        return $next($request);
    }
}
