<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    const CATEGORIES = [
        'rent' => 'দোকান ভাড়া',
        'salary' => 'বেতন',
        'utility' => 'বিদ্যুৎ/পানি/গ্যাস বিল',
        'transport' => 'যাতায়াত/পরিবহন',
        'other' => 'অন্যান্য',
    ];

    protected $fillable = ['shop_id', 'title', 'category', 'amount', 'date', 'note', 'created_by'];

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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
