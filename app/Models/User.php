<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // রোল কনস্ট্যান্ট
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_SHOP_OWNER = 'shop_owner';
    const ROLE_MANAGER = 'manager';
    const ROLE_EMPLOYEE = 'employee';

    // Manager/Employee আইডি বানানোর সময় যেসব পারমিশন টিকমার্ক দেওয়া যাবে
    const PERMISSIONS = [
        'products' => 'প্রোডাক্ট (Add/Edit/View)',
        'sales' => 'সেল / POS',
        'stock' => 'স্টক ইন/আউট',
        'categories' => 'ক্যাটাগরি ম্যানেজমেন্ট',
        'reports' => 'রিপোর্ট দেখা',
        'service_fee' => 'সার্ভিস ফি',
        'expenses' => 'খরচ ম্যানেজমেন্ট',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'created_by',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    // এই ইউজার যেসব শপে এক্সেস পেয়েছে (Owner/Manager/Employee সবার জন্যই প্রযোজ্য)
    public function shops()
    {
        return $this->belongsToMany(Shop::class)->withPivot('permissions')->withTimestamps();
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isShopOwner(): bool
    {
        return $this->role === self::ROLE_SHOP_OWNER;
    }

    public function isManager(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function isEmployee(): bool
    {
        return $this->role === self::ROLE_EMPLOYEE;
    }

    // Super Admin ও Shop Owner এর নিজের শপে সবসময় ফুল এক্সেস থাকে, শুধু Manager/Employee এর
    // ক্ষেত্রে আইডি বানানোর সময় টিকমার্ক দেওয়া পারমিশন চেক করতে হয়
    public function hasPermission(string $permission, ?int $shopId = null): bool
    {
        if ($this->isSuperAdmin() || $this->isShopOwner()) {
            return true;
        }

        $shopId = $shopId ?? session('current_shop_id');

        $pivot = $this->shops()->where('shops.id', $shopId)->first()?->pivot;

        if (! $pivot) {
            return false;
        }

        $permissions = json_decode($pivot->permissions ?? '[]', true) ?: [];

        return in_array($permission, $permissions, true);
    }

    // এই ইউজার নির্দিষ্ট শপে এক্সেস পেয়েছে কিনা
    public function hasAccessToShop(int $shopId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->shops()->where('shops.id', $shopId)->exists();
    }
}
