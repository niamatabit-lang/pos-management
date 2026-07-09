@extends('layouts.app')

@section('title', __('app.finance_title'))

@section('content')

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">
                {{ __('app.finance_title') }}
            </h1>
            <p class="page-subtitle">
                {{ $shop->name }} — {{ __('app.finance_subtitle') }}
            </p>
        </div>
    </div>

    @if (session('success'))
        <div style="background:#d1f4df;color:#198754;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    @if ($isShort)
        <div style="background:#fde2e2;color:#dc3545;padding:16px 20px;border-radius:10px;margin-bottom:20px;font-weight:700;">
            ⚠️ {{ __('app.short_warning', ['amount' => number_format(abs($difference), 2)]) }}
        </div>
    @elseif ($isExtra)
        <div style="background:#d1f4df;color:#198754;padding:16px 20px;border-radius:10px;margin-bottom:20px;font-weight:700;">
            ✅ {{ __('app.extra_notice', ['amount' => number_format($difference, 2)]) }}
        </div>
    @else
        <div style="background:#d1f4df;color:#198754;padding:16px 20px;border-radius:10px;margin-bottom:20px;font-weight:700;">
            ✅ {{ __('app.balance_ok') }}
        </div>
    @endif

    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.opening_cash') }}</div>
            <div class="kpi-value">৳ {{ number_format($shop->opening_cash, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.current_cash_kpi') }}</div>
            <div class="kpi-value">৳ {{ number_format($currentCash, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.stock_value_kpi') }}</div>
            <div class="kpi-value">৳ {{ number_format($stockValue, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.total_receivable') }}</div>
            <div class="kpi-value">৳ {{ number_format($totalReceivable, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.total_payable') }}</div>
            <div class="kpi-value">৳ {{ number_format($totalPayable, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.net_position') }}</div>
            <div class="kpi-value">৳ {{ number_format($netPosition, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.month_sales') }}</div>
            <div class="kpi-value">৳ {{ number_format($currentMonthSales, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.month_profit') }}</div>
            <div class="kpi-value">৳ {{ number_format($currentMonthProfit, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.month_expense') }}</div>
            <div class="kpi-value">৳ {{ number_format($currentMonthExpense, 2) }}</div>
        </div>

    </div>

    <p style="color:#888;font-size:13px;margin:15px 0 25px;">
        {{ __('app.finance_formula_note') }}
    </p>

    {{-- ========== মালিকের প্রফিট উইথড্রয়াল ========== --}}
    <div class="kpi-grid" style="margin-bottom:10px;">

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.total_net_profit') }}</div>
            <div class="kpi-value">৳ {{ number_format($totalNetProfit, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.total_withdrawn') }}</div>
            <div class="kpi-value">৳ {{ number_format($totalWithdrawn, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.available_profit') }}</div>
            <div class="kpi-value" style="color: {{ $availableProfit < 0 ? '#dc3545' : '#198754' }};">
                ৳ {{ number_format($availableProfit, 2) }}
            </div>
        </div>

    </div>

    <p style="color:#888;font-size:13px;margin:0 0 25px;">
        {{ __('app.withdrawal_note') }}
    </p>

    <div class="table-wrapper" style="margin-bottom:25px;">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;">{{ __('app.withdraw_profit') }}</h2>
        </div>

        <form method="POST" action="{{ route('profit-withdrawals.store') }}" style="padding:18px;">
            @csrf
            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label">{{ __('app.amount_taka') }} <span class="required">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="{{ old('amount') }}">
                    @error('amount') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('app.date') }}</label>
                    <input type="date" name="date" class="form-control" value="{{ old('date', now()->toDateString()) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('app.note') }}</label>
                    <input type="text" name="note" class="form-control" placeholder="{{ __('app.optional') }}" value="{{ old('note') }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('app.record_withdrawal') }}</button>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.date') }}</th>
                    <th class="text-right">{{ __('app.amount_taka') }}</th>
                    <th>{{ __('app.taken_by') }}</th>
                    <th>{{ __('app.note') }}</th>
                    <th class="text-right">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($profitWithdrawals as $withdrawal)
                    <tr>
                        <td>{{ optional($withdrawal->date)->format('d M Y') }}</td>
                        <td class="text-right">৳ {{ number_format($withdrawal->amount, 2) }}</td>
                        <td>{{ $withdrawal->withdrawnBy->name ?? '-' }}</td>
                        <td>{{ $withdrawal->note ?? '-' }}</td>
                        <td class="text-right">
                            <form method="POST" action="{{ route('profit-withdrawals.destroy', $withdrawal) }}" onsubmit="return confirm('{{ __('app.confirm_delete_withdrawal') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="table-empty">{{ __('app.no_withdrawals_yet') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- ========== প্রফিট উইথড্রয়াল সেকশন শেষ ========== --}}

    <div class="table-wrapper" style="margin-bottom:25px;">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;">{{ __('app.update_cash') }}</h2>
        </div>

        <form method="POST" action="{{ route('finance.update-cash') }}" style="padding:18px;display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
            @csrf
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">{{ __('app.opening_cash') }} (৳)</label>
                <input type="number" step="0.01" min="0" name="opening_cash" class="form-control" value="{{ $shop->opening_cash }}">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">{{ __('app.current_cash_kpi') }} (৳)</label>
                <input type="number" step="0.01" min="0" name="current_cash" class="form-control" value="{{ $shop->current_cash }}">
            </div>
            <button type="submit" class="btn btn-primary">{{ __('app.update') }}</button>
        </form>
    </div>

    <div class="table-wrapper" style="margin-bottom:25px;">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;">{{ __('app.receivables_list') }}</h2>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.date') }}</th>
                    <th>{{ __('app.invoice') }}</th>
                    <th>{{ __('app.customer_name') }}</th>
                    <th class="text-right">{{ __('app.total_bill') }}</th>
                    <th class="text-right">{{ __('app.paid') }}</th>
                    <th class="text-right">{{ __('app.due') }}</th>
                    <th class="text-right">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($receivables as $sale)
                    <tr>
                        <td>{{ $sale->created_at->format('d M Y') }}</td>
                        <td>{{ $sale->invoice_no }}</td>
                        <td>{{ $sale->customer_name ?? '-' }}</td>
                        <td class="text-right">৳ {{ number_format($sale->total, 2) }}</td>
                        <td class="text-right">৳ {{ number_format($sale->paid_amount, 2) }}</td>
                        <td class="text-right"><strong>৳ {{ number_format($sale->due_amount, 2) }}</strong></td>
                        <td class="text-right">
                            <form method="POST" action="{{ route('finance.receivables.payment', $sale) }}" style="display:inline-flex;gap:4px;">
                                @csrf
                                <input type="number" step="0.01" min="0.01" max="{{ $sale->due_amount }}" name="payment_amount" class="form-control" style="width:100px;height:34px;padding:0 6px;" placeholder="{{ __('app.amount') }}" required>
                                <button type="submit" class="btn btn-secondary btn-sm">{{ __('app.collect') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="table-empty">{{ __('app.no_receivables') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-wrapper" style="margin-bottom:25px;">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;">{{ __('app.add_new_receivable') }}</h2>
        </div>
        <p style="color:#888;font-size:13px;padding:0 18px;margin:6px 0 0;">
            {{ __('app.add_receivable_note') }}
        </p>

        <form method="POST" action="{{ route('receivables.store') }}" style="padding:18px;">
            @csrf
            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label">{{ __('app.owed_by') }} <span class="required">*</span></label>
                    <input type="text" name="party_name" class="form-control" placeholder="{{ __('app.person_customer_name') }}" value="{{ old('party_name') }}">
                    @error('party_name') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('app.amount_taka') }} <span class="required">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="{{ old('amount') }}">
                    @error('amount') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('app.date') }}</label>
                    <input type="date" name="date" class="form-control" value="{{ old('date', now()->toDateString()) }}">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('app.note') }}</label>
                <input type="text" name="note" class="form-control" placeholder="{{ __('app.optional') }}" value="{{ old('note') }}">
            </div>
            <button type="submit" class="btn btn-primary">{{ __('app.add_receivable') }}</button>
        </form>
    </div>

    <div class="table-wrapper" style="margin-bottom:25px;">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;">{{ __('app.manual_receivables_list') }}</h2>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.date') }}</th>
                    <th>{{ __('app.owed_by') }}</th>
                    <th class="text-right">{{ __('app.total') }}</th>
                    <th class="text-right">{{ __('app.collected') }}</th>
                    <th class="text-right">{{ __('app.due') }}</th>
                    <th>{{ __('app.note') }}</th>
                    <th class="text-right">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($manualReceivables as $receivable)
                    <tr>
                        <td>{{ optional($receivable->date)->format('d M Y') }}</td>
                        <td>{{ $receivable->party_name }}</td>
                        <td class="text-right">৳ {{ number_format($receivable->amount, 2) }}</td>
                        <td class="text-right">৳ {{ number_format($receivable->paid_amount, 2) }}</td>
                        <td class="text-right">
                            @if ($receivable->isPaidOff())
                                <span class="badge badge-success">{{ __('app.collection_done') }}</span>
                            @else
                                ৳ {{ number_format($receivable->dueAmount(), 2) }}
                            @endif
                        </td>
                        <td>{{ $receivable->note ?? '-' }}</td>
                        <td class="text-right">
                            <div style="display:flex;gap:6px;justify-content:flex-end;flex-wrap:wrap;">
                                @unless ($receivable->isPaidOff())
                                    <form method="POST" action="{{ route('receivables.payment', $receivable) }}" style="display:inline-flex;gap:4px;">
                                        @csrf
                                        <input type="number" step="0.01" min="0.01" max="{{ $receivable->dueAmount() }}" name="payment_amount" class="form-control" style="width:100px;height:34px;padding:0 6px;" placeholder="{{ __('app.amount') }}" required>
                                        <button type="submit" class="btn btn-secondary btn-sm">{{ __('app.collect') }}</button>
                                    </form>
                                @endunless
                                <form method="POST" action="{{ route('receivables.destroy', $receivable) }}" onsubmit="return confirm('{{ __('app.confirm_delete_receivable') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="table-empty">{{ __('app.no_manual_receivables') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-wrapper" style="margin-bottom:25px;">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;">{{ __('app.add_new_payable') }}</h2>
        </div>

        <form method="POST" action="{{ route('payables.store') }}" style="padding:18px;">
            @csrf
            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label">{{ __('app.owed_to') }} <span class="required">*</span></label>
                    <input type="text" name="party_name" class="form-control" placeholder="{{ __('app.supplier_name_eg') }}" value="{{ old('party_name') }}">
                    @error('party_name') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('app.amount_taka') }} <span class="required">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="{{ old('amount') }}">
                    @error('amount') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('app.date') }}</label>
                    <input type="date" name="date" class="form-control" value="{{ old('date', now()->toDateString()) }}">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('app.note') }}</label>
                <input type="text" name="note" class="form-control" placeholder="{{ __('app.optional') }}" value="{{ old('note') }}">
            </div>
            <button type="submit" class="btn btn-primary">{{ __('app.add_payable') }}</button>
        </form>
    </div>

    <div class="table-wrapper">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;">{{ __('app.payables_list') }}</h2>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.date') }}</th>
                    <th>{{ __('app.owed_to') }}</th>
                    <th class="text-right">{{ __('app.total') }}</th>
                    <th class="text-right">{{ __('app.paid') }}</th>
                    <th class="text-right">{{ __('app.due') }}</th>
                    <th>{{ __('app.note') }}</th>
                    <th class="text-right">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payables as $payable)
                    <tr>
                        <td>{{ optional($payable->date)->format('d M Y') }}</td>
                        <td>{{ $payable->party_name }}</td>
                        <td class="text-right">৳ {{ number_format($payable->amount, 2) }}</td>
                        <td class="text-right">৳ {{ number_format($payable->paid_amount, 2) }}</td>
                        <td class="text-right">
                            @if ($payable->isPaidOff())
                                <span class="badge badge-success">{{ __('app.paid_off') }}</span>
                            @else
                                ৳ {{ number_format($payable->dueAmount(), 2) }}
                            @endif
                        </td>
                        <td>{{ $payable->note ?? '-' }}</td>
                        <td class="text-right">
                            <div style="display:flex;gap:6px;justify-content:flex-end;flex-wrap:wrap;">
                                @unless ($payable->isPaidOff())
                                    <form method="POST" action="{{ route('payables.payment', $payable) }}" style="display:inline-flex;gap:4px;">
                                        @csrf
                                        <input type="number" step="0.01" min="0.01" max="{{ $payable->dueAmount() }}" name="payment_amount" class="form-control" style="width:100px;height:34px;padding:0 6px;" placeholder="{{ __('app.amount') }}" required>
                                        <button type="submit" class="btn btn-secondary btn-sm">{{ __('app.payment') }}</button>
                                    </form>
                                @endunless
                                <form method="POST" action="{{ route('payables.destroy', $payable) }}" onsubmit="return confirm('{{ __('app.confirm_delete_payable') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="table-empty">{{ __('app.no_payables') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
