<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id', 'product_id', 'product_name', 'unit_price', 'buy_price', 'commission', 'quantity', 'subtotal',
    ];

    // এই আইটেম বিক্রি করে কত টাকা লাভ হয়েছে (বিক্রয়মূল্য - ক্রয়মূল্য) + কোম্পানির কমিশন
    // কমিশন প্রফিটে যোগ হয় কিন্তু ইনভয়েসে/সাবটোটালে দেখানো হয় না
    public function profit(): float
    {
        return (($this->unit_price - $this->buy_price) + $this->commission) * $this->quantity;
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
