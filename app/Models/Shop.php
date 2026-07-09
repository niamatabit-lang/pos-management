<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'opening_cash', 'current_cash'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // এই শপের দেনা (সাপ্লায়ার/অন্য কাউকে যা টাকা দেয়া বাকি)
    public function payables()
    {
        return $this->hasMany(Payable::class);
    }

    // মালিক এই শপ থেকে যত টাকা প্রফিট হিসেবে তুলেছেন তার লগ
    public function profitWithdrawals()
    {
        return $this->hasMany(ProfitWithdrawal::class);
    }

    // এই শপে এক্সেস পাওয়া সব ইউজার (Owner, Manager, Employee)
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('permissions')->withTimestamps();
    }

    // এই শপের মালিক (Shop Owner রোলধারীরা)
    public function owners()
    {
        return $this->users()->where('role', User::ROLE_SHOP_OWNER);
    }

    // এই শপের ম্যানেজার ও এমপ্লয়ি
    public function staff()
    {
        return $this->users()->whereIn('role', [User::ROLE_MANAGER, User::ROLE_EMPLOYEE]);
    }
}
