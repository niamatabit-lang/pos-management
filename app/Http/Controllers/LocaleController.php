<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    // হেডারের বাটনে ক্লিক করলে ভাষা বদলে যাবে (bn <-> en), যে পেজে ছিলো সেখানেই ফিরে যাবে
    public function switch(Request $request, string $locale)
    {
        if (in_array($locale, ['bn', 'en'])) {
            Session::put('locale', $locale);
        }

        return back();
    }
}
