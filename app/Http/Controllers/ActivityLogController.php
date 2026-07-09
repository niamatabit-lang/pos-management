<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $logs = ActivityLog::where('shop_id', $shopId)
            ->with('user')
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('activity-logs.index', compact('logs'));
    }
}
