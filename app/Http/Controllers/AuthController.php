<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withInput($request->only('email'))
                ->with('error', 'ইমেইল অথবা পাসওয়ার্ড ভুল হয়েছে।');
        }

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();
            return back()->with('error', 'আপনার আইডি বর্তমানে বন্ধ আছে। এডমিনের সাথে যোগাযোগ করুন।');
        }

        $request->session()->regenerate();

        // লগইনের সাথে সাথে এই ইউজারের এক্সেসযোগ্য প্রথম শপটাকে current shop হিসেবে সেট করা হচ্ছে
        Session::forget('current_shop_id');

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        // আগের পাসওয়ার্ডটা সঠিক কিনা যাচাই করা হচ্ছে
        if (! \Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'বর্তমান পাসওয়ার্ড সঠিক নয়।');
        }

        $user->update([
            'password' => bcrypt($request->new_password),
        ]);

        return back()->with('success', 'পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে।');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // ইমেইলে পাসওয়ার্ড রিসেট লিংক পাঠানো হয় (mail সার্ভার কনফিগার করা থাকতে হবে)
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'পাসওয়ার্ড রিসেট লিংক আপনার ইমেইলে পাঠানো হয়েছে।')
            : back()->with('error', 'এই ইমেইলে কোনো একাউন্ট পাওয়া যায়নি।');
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill(['password' => bcrypt($request->password)])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'পাসওয়ার্ড রিসেট হয়েছে, এখন লগইন করুন।')
            : back()->with('error', 'লিংকটি সঠিক নয় অথবা মেয়াদ শেষ হয়ে গেছে।');
    }
}
