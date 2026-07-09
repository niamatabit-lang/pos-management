<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * নির্দিষ্ট রোল ছাড়া কেউ এই রুটে ঢুকতে পারবে না।
     * ব্যবহার: ->middleware('role:super_admin,shop_owner')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'এই পেজ দেখার অনুমতি আপনার নেই।');
        }

        return $next($request);
    }
}
