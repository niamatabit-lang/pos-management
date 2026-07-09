<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{
    protected $fillable = ['shop_id', 'party_name', 'amount', 'paid_amount', 'date', 'note'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // এখনো কত টাকা দেনা বাকি আছে
    public function dueAmount(): float
    {
        return max((float) $this->amount - (float) $this->paid_amount, 0);
    }

    public function isPaidOff(): bool
    {
        return $this->dueAmount() <= 0;
    }
}
