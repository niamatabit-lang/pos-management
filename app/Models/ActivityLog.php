<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $fillable = ['shop_id', 'user_id', 'action', 'subject_type', 'subject_id', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * সহজে লগ করার জন্য হেল্পার। যেকোনো কন্ট্রোলার থেকে এভাবে কল করা যাবেঃ
     * ActivityLog::log($shopId, 'created', 'Product', $product->id, "প্রোডাক্ট '{$product->name}' যোগ করা হয়েছে");
     */
    public static function log(int $shopId, string $action, ?string $subjectType, ?int $subjectId, string $description): void
    {
        static::create([
            'shop_id' => $shopId,
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'description' => $description,
        ]);
    }
}
