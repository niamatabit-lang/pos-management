<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * সেশনে সেভ করা ভাষা (বাংলা/ইংলিশ) অনুযায়ী অ্যাপের লোকেল সেট করে দেয়।
     * কিছু সিলেক্ট করা না থাকলে ডিফল্ট হিসেবে বাংলা (bn) ব্যবহার হবে।
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('locale', 'bn');

        if (! in_array($locale, ['bn', 'en'])) {
            $locale = 'bn';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
