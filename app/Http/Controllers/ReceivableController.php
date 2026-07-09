<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Receivable;
use Illuminate\Http\Request;

class ReceivableController extends Controller
{
    public function store(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $data = $request->validate([
            'party_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'nullable|date',
            'note' => 'nullable|string|max:255',
        ]);

        $data['shop_id'] = $shopId;
        $data['paid_amount'] = 0;
        $data['date'] = $data['date'] ?? now()->toDateString();

        $receivable = Receivable::create($data);

        ActivityLog::log($shopId, 'receivable_added', 'Receivable', $receivable->id, "{$receivable->party_name} এর কাছে ৳" . number_format($receivable->amount, 2) . ' পাওনা যোগ হয়েছে');

        return redirect()->route('finance.index')->with('success', 'পাওনার তথ্য যোগ হয়েছে।');
    }

    // পাওনার বিপরীতে আংশিক বা পুরো টাকা আদায় রেকর্ড করা
    public function recordPayment(Request $request, Receivable $receivable)
    {
        abort_unless($receivable->shop_id === $request->attributes->get('currentShop')->id, 404);

        $data = $request->validate([
            'payment_amount' => 'required|numeric|min:0.01|max:' . $receivable->dueAmount(),
        ]);

        $newPaid = min((float) $receivable->paid_amount + $data['payment_amount'], (float) $receivable->amount);
        $receivable->update(['paid_amount' => $newPaid]);

        ActivityLog::log($receivable->shop_id, 'receivable_payment', 'Receivable', $receivable->id, "{$receivable->party_name} এর পাওনা থেকে ৳" . number_format($data['payment_amount'], 2) . ' আদায় হয়েছে');

        return redirect()->route('finance.index')->with('success', 'পাওনার টাকা আদায় রেকর্ড করা হয়েছে।');
    }

    public function destroy(Request $request, Receivable $receivable)
    {
        abort_unless($receivable->shop_id === $request->attributes->get('currentShop')->id, 404);

        $receivable->delete();

        return redirect()->route('finance.index')->with('success', 'পাওনার এন্ট্রি মুছে ফেলা হয়েছে।');
    }
}
