<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $authUser = Auth::user();

        if ($authUser->isSuperAdmin()) {
            // সুপার এডমিন সব Shop Owner দেখতে পাবে, তাদের এসাইন করা শপসহ
            $users = User::where('role', User::ROLE_SHOP_OWNER)
                ->with('shops')
                ->latest()
                ->get();
        } else {
            // Shop Owner শুধু নিজের তৈরি করা Manager/Employee দেখতে পাবে
            $users = User::where('created_by', $authUser->id)
                ->whereIn('role', [User::ROLE_MANAGER, User::ROLE_EMPLOYEE])
                ->with('shops')
                ->latest()
                ->get();
        }

        return view('users.index', compact('users'));
    }

    public function create(Request $request)
    {
        $authUser = Auth::user();

        if ($authUser->isSuperAdmin()) {
            $shops = Shop::orderBy('name')->get();
            return view('users.create', ['mode' => 'shop_owner', 'shops' => $shops]);
        }

        if ($authUser->isShopOwner()) {
            $shops = $authUser->shops()->orderBy('name')->get();
            return view('users.create', ['mode' => 'staff', 'shops' => $shops, 'permissions' => User::PERMISSIONS]);
        }

        abort(403, 'আপনি নতুন আইডি তৈরি করতে পারবেন না।');
    }

    public function store(Request $request)
    {
        $authUser = Auth::user();

        if ($authUser->isSuperAdmin()) {
            return $this->storeShopOwner($request, $authUser);
        }

        if ($authUser->isShopOwner()) {
            return $this->storeStaff($request, $authUser);
        }

        abort(403, 'আপনি নতুন আইডি তৈরি করতে পারবেন না।');
    }

    // সুপার এডমিন কর্তৃক নতুন Shop Owner তৈরি
    protected function storeShopOwner(Request $request, User $authUser)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(6)],
            'shop_ids' => 'required|array|min:1',
            'shop_ids.*' => 'exists:shops,id',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_SHOP_OWNER,
            'created_by' => $authUser->id,
        ]);

        // Shop Owner এর নিজের এসাইন করা শপে ফুল এক্সেস থাকে, তাই permissions null
        $user->shops()->sync(collect($data['shop_ids'])->mapWithKeys(fn ($id) => [$id => ['permissions' => null]]));

        return redirect()->route('users.index')->with('success', 'নতুন Shop Owner তৈরি হয়েছে।');
    }

    // Shop Owner কর্তৃক নতুন Manager/Employee তৈরি
    protected function storeStaff(Request $request, User $authUser)
    {
        $ownedShopIds = $authUser->shops()->pluck('shops.id')->toArray();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(6)],
            'role' => ['required', Rule::in([User::ROLE_MANAGER, User::ROLE_EMPLOYEE])],
            'shop_id' => ['required', Rule::in($ownedShopIds)],
            'permissions' => 'nullable|array',
            'permissions.*' => Rule::in(array_keys(User::PERMISSIONS)),
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'created_by' => $authUser->id,
        ]);

        $user->shops()->attach($data['shop_id'], [
            'permissions' => json_encode($data['permissions'] ?? []),
        ]);

        return redirect()->route('users.index')->with('success', 'নতুন আইডি তৈরি হয়েছে।');
    }

    public function edit(Request $request, User $user)
    {
        $authUser = Auth::user();
        $this->authorizeManaging($authUser, $user);

        if ($authUser->isSuperAdmin()) {
            $shops = Shop::orderBy('name')->get();
            $assignedShopIds = $user->shops()->pluck('shops.id')->toArray();
            return view('users.edit', ['mode' => 'shop_owner', 'user' => $user, 'shops' => $shops, 'assignedShopIds' => $assignedShopIds]);
        }

        $shops = $authUser->shops()->orderBy('name')->get();
        $currentShopPivot = $user->shops()->first();
        $currentPermissions = $currentShopPivot ? (json_decode($currentShopPivot->pivot->permissions ?? '[]', true) ?: []) : [];

        return view('users.edit', [
            'mode' => 'staff',
            'user' => $user,
            'shops' => $shops,
            'permissions' => User::PERMISSIONS,
            'currentShopId' => $currentShopPivot?->id,
            'currentPermissions' => $currentPermissions,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $authUser = Auth::user();
        $this->authorizeManaging($authUser, $user);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(6)],
            'is_active' => 'nullable|boolean',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->is_active = $request->boolean('is_active');

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        if ($authUser->isSuperAdmin()) {
            $shopIds = $request->input('shop_ids', []);
            $user->shops()->sync(collect($shopIds)->mapWithKeys(fn ($id) => [$id => ['permissions' => null]]));
        } else {
            $ownedShopIds = $authUser->shops()->pluck('shops.id')->toArray();
            $shopId = $request->input('shop_id');

            if ($shopId && in_array((int) $shopId, $ownedShopIds, true)) {
                $permissions = $request->input('permissions', []);
                $user->shops()->sync([$shopId => ['permissions' => json_encode($permissions)]]);
            }
        }

        return redirect()->route('users.index')->with('success', 'আইডি আপডেট করা হয়েছে।');
    }

    public function destroy(Request $request, User $user)
    {
        $authUser = Auth::user();
        $this->authorizeManaging($authUser, $user);

        // হার্ড ডিলিট না করে ডিএক্টিভেট করা হচ্ছে, যাতে পুরনো সেল/স্টকের রেকর্ড ঠিক থাকে
        $user->update(['is_active' => false]);

        return redirect()->route('users.index')->with('success', 'আইডি বন্ধ করে দেওয়া হয়েছে।');
    }

    // এই ইউজারকে ম্যানেজ করার অধিকার আছে কিনা যাচাই করে
    protected function authorizeManaging(User $authUser, User $target): void
    {
        if ($authUser->isSuperAdmin() && $target->isShopOwner()) {
            return;
        }

        if ($authUser->isShopOwner() && $target->created_by === $authUser->id) {
            return;
        }

        abort(403, 'এই আইডিটি ম্যানেজ করার অনুমতি আপনার নেই।');
    }
}
