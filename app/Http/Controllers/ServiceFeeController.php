<?php

namespace App\Http\Controllers;

use App\Models\ServiceFee;
use Illuminate\Http\Request;

class ServiceFeeController extends Controller
{
    public function index(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $serviceFees = ServiceFee::where('shop_id', $shopId)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $todayTotal = ServiceFee::where('shop_id', $shopId)
            ->whereDate('created_at', today())
            ->sum('commission');

        return view('service-fees.index', compact('serviceFees', 'todayTotal'));
    }

    public function store(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $data = $request->validate([
            'service_name' => 'required|string|max:255',
            'mobile_number' => 'nullable|string|max:30',
            'sale_price' => 'required|numeric|min:0',
            'commission' => 'required|numeric|min:0',
        ]);

        $data['shop_id'] = $shopId;

        ServiceFee::create($data);

        return redirect()->route('service-fees.index')->with('success', 'সার্ভিস ফি যোগ হয়েছে।');
    }

    public function destroy(Request $request, ServiceFee $serviceFee)
    {
        abort_unless($serviceFee->shop_id === $request->attributes->get('currentShop')->id, 404);

        $serviceFee->delete();

        return redirect()->route('service-fees.index')->with('success', 'এন্ট্রি মুছে ফেলা হয়েছে।');
    }
}
