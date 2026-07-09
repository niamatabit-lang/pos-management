<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'shop_id', 'invoice_no', 'customer_name', 'subtotal', 'discount',
        'total', 'paid_amount', 'due_amount', 'payment_status',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
