<?php

namespace App\Http\Controllers;

use App\Models\Payable;
use Illuminate\Http\Request;

class PayableController extends Controller
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

        Payable::create($data);

        return redirect()->route('finance.index')->with('success', 'দেনার তথ্য যোগ হয়েছে।');
    }

    // দেনার বিপরীতে আংশিক বা পুরো টাকা পরিশোধ রেকর্ড করা
    public function recordPayment(Request $request, Payable $payable)
    {
        abort_unless($payable->shop_id === $request->attributes->get('currentShop')->id, 404);

        $data = $request->validate([
            'payment_amount' => 'required|numeric|min:0.01',
        ]);

        $newPaid = min((float) $payable->paid_amount + $data['payment_amount'], (float) $payable->amount);
        $payable->update(['paid_amount' => $newPaid]);

        return redirect()->route('finance.index')->with('success', 'পেমেন্ট রেকর্ড করা হয়েছে।');
    }

    public function destroy(Request $request, Payable $payable)
    {
        abort_unless($payable->shop_id === $request->attributes->get('currentShop')->id, 404);

        $payable->delete();

        return redirect()->route('finance.index')->with('success', 'দেনার এন্ট্রি মুছে ফেলা হয়েছে।');
    }
}
