<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'shop_id', 'category_id', 'name', 'sku', 'unit',
        'buy_price', 'sell_price', 'commission', 'quantity', 'reorder_level',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // এই প্রোডাক্টের স্টক কি লো লেভেলে আছে কিনা
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->reorder_level;
    }

    // বর্তমান স্টকের মোট ক্রয়মূল্য (স্টক ভ্যালু)
    public function stockValue(): float
    {
        return $this->quantity * $this->buy_price;
    }
}
