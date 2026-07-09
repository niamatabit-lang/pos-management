<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfitWithdrawal extends Model
{
    protected $fillable = ['shop_id', 'amount', 'date', 'note', 'withdrawn_by'];

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

    public function withdrawnBy()
    {
        return $this->belongsTo(User::class, 'withdrawn_by');
    }
}
