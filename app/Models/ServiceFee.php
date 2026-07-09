<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceFee extends Model
{
    protected $fillable = ['shop_id', 'service_name', 'mobile_number', 'sale_price', 'commission'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
