<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Return_ extends Model
{
    protected $table = 'returns';

    protected $fillable = ['shop_id', 'sale_id', 'sale_item_id', 'product_id', 'quantity', 'refund_amount', 'reason'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function saleItem()
    {
        return $this->belongsTo(SaleItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
