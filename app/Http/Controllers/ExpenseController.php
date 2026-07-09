<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $query = Expense::where('shop_id', $shopId);

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $expenses = $query->orderByDesc('date')->paginate(15)->withQueryString();

        $totalThisMonth = Expense::where('shop_id', $shopId)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        return view('expenses.index', [
            'expenses' => $expenses,
            'totalThisMonth' => $totalThisMonth,
            'categories' => Expense::CATEGORIES,
        ]);
    }

    public function store(Request $request)
    {
        $shopId = $request->attributes->get('currentShop')->id;

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|in:' . implode(',', array_keys(Expense::CATEGORIES)),
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        $data['shop_id'] = $shopId;
        $data['created_by'] = Auth::id();

        $expense = Expense::create($data);

        ActivityLog::log($shopId, 'created', 'Expense', $expense->id, __('app.log_expense_added', ['title' => $expense->title, 'amount' => number_format($expense->amount, 2)]));

        return redirect()->route('expenses.index')->with('success', __('app.expense_added'));
    }

    public function destroy(Request $request, Expense $expense)
    {
        abort_unless($expense->shop_id === $request->attributes->get('currentShop')->id, 404);

        ActivityLog::log($expense->shop_id, 'deleted', 'Expense', $expense->id, __('app.log_expense_deleted', ['title' => $expense->title]));

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', __('app.expense_deleted'));
    }
}
