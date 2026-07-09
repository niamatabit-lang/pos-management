<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckShopPermission
{
    /**
     * Manager/Employee এর ক্ষেত্রে আইডি বানানোর সময় টিকমার্ক দেওয়া পারমিশন চেক করে।
     * Super Admin ও Shop Owner সবসময় এক্সেস পায়।
     * ব্যবহার: ->middleware('shop.permission:products')
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();
        $currentShop = $request->attributes->get('currentShop');

        if (! $user->hasPermission($permission, $currentShop?->id)) {
            abort(403, 'এই ফিচারটি ব্যবহারের অনুমতি আপনাকে দেওয়া হয়নি।');
        }

        return $next($request);
    }
}
