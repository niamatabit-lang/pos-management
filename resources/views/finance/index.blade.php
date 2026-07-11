@extends('layouts.app')

@section('title', __('app.finance_title'))

@section('content')

<div class="page">

    <x-page-header :title="__('app.finance_title')" :subtitle="$shop->name . ' — ' . __('app.finance_subtitle')" />

    @if (session('success'))
        <x-alert variant="success">{{ session('success') }}</x-alert>
    @endif

    @if ($isShort)
        <x-alert variant="danger" size="lg">
            ⚠️ {{ __('app.short_warning', ['amount' => number_format(abs($difference), 2)]) }}
        </x-alert>
    @elseif ($isExtra)
        <x-alert variant="success" size="lg">
            ✅ {{ __('app.extra_notice', ['amount' => number_format($difference, 2)]) }}
        </x-alert>
    @else
        <x-alert variant="success" size="lg">
            ✅ {{ __('app.balance_ok') }}
        </x-alert>
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

    <p class="text-muted-note mt-15 mb-25">
        {{ __('app.finance_formula_note') }}
    </p>

    {{-- ========== মালিকের প্রফিট উইথড্রয়াল ========== --}}
    <div class="kpi-grid mb-10">

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
            <div class="kpi-value {{ $availableProfit < 0 ? 'text-danger' : 'text-primary' }}">
                ৳ {{ number_format($availableProfit, 2) }}
            </div>
        </div>

    </div>

    <p class="text-muted-note mb-25">
        {{ __('app.withdrawal_note') }}
    </p>

    <x-table-wrapper class="mb-25">
        <x-page-header flat class="mb-0">
            <x-slot:heading>
                <h2 class="page-title text-lg">{{ __('app.withdraw_profit') }}</h2>
            </x-slot:heading>
        </x-page-header>

        <form method="POST" action="{{ route('profit-withdrawals.store') }}" class="p-18">
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
            <x-button variant="primary">{{ __('app.record_withdrawal') }}</x-button>
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
                                <x-button variant="danger" size="sm">{{ __('app.delete') }}</x-button>
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
    </x-table-wrapper>
    {{-- ========== প্রফিট উইথড্রয়াল সেকশন শেষ ========== --}}

    <x-table-wrapper class="mb-25">
        <x-page-header flat class="mb-0">
            <x-slot:heading>
                <h2 class="page-title text-lg">{{ __('app.update_cash') }}</h2>
            </x-slot:heading>
        </x-page-header>

        <form method="POST" action="{{ route('finance.update-cash') }}" class="form-inline-panel">
            @csrf
            <div class="form-group form-group-flush">
                <label class="form-label">{{ __('app.opening_cash') }} (৳)</label>
                <input type="number" step="0.01" min="0" name="opening_cash" class="form-control" value="{{ $shop->opening_cash }}">
            </div>
            <div class="form-group form-group-flush">
                <label class="form-label">{{ __('app.current_cash_kpi') }} (৳)</label>
                <input type="number" step="0.01" min="0" name="current_cash" class="form-control" value="{{ $shop->current_cash }}">
            </div>
            <x-button variant="primary">{{ __('app.update') }}</x-button>
        </form>
    </x-table-wrapper>

    <x-table-wrapper class="mb-25">
        <x-page-header flat class="mb-0">
            <x-slot:heading>
                <h2 class="page-title text-lg">{{ __('app.receivables_list') }}</h2>
            </x-slot:heading>
        </x-page-header>

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
                            <form method="POST" action="{{ route('finance.receivables.payment', $sale) }}" class="d-inline-flex gap-4">
                                @csrf
                                <input type="number" step="0.01" min="0.01" max="{{ $sale->due_amount }}" name="payment_amount" class="form-control input-amount" placeholder="{{ __('app.amount') }}" required>
                                <x-button variant="secondary" size="sm">{{ __('app.collect') }}</x-button>
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
    </x-table-wrapper>

    <x-table-wrapper class="mb-25">
        <x-page-header flat class="mb-0">
            <x-slot:heading>
                <h2 class="page-title text-lg">{{ __('app.add_new_receivable') }}</h2>
            </x-slot:heading>
        </x-page-header>
        <p class="section-note">
            {{ __('app.add_receivable_note') }}
        </p>

        <form method="POST" action="{{ route('receivables.store') }}" class="p-18">
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
            <x-button variant="primary">{{ __('app.add_receivable') }}</x-button>
        </form>
    </x-table-wrapper>

    <x-table-wrapper class="mb-25">
        <x-page-header flat class="mb-0">
            <x-slot:heading>
                <h2 class="page-title text-lg">{{ __('app.manual_receivables_list') }}</h2>
            </x-slot:heading>
        </x-page-header>

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
                                <x-badge variant="success">{{ __('app.collection_done') }}</x-badge>
                            @else
                                ৳ {{ number_format($receivable->dueAmount(), 2) }}
                            @endif
                        </td>
                        <td>{{ $receivable->note ?? '-' }}</td>
                        <td class="text-right">
                            <div class="table-actions">
                                @unless ($receivable->isPaidOff())
                                    <form method="POST" action="{{ route('receivables.payment', $receivable) }}" class="d-inline-flex gap-4">
                                        @csrf
                                        <input type="number" step="0.01" min="0.01" max="{{ $receivable->dueAmount() }}" name="payment_amount" class="form-control input-amount" placeholder="{{ __('app.amount') }}" required>
                                        <x-button variant="secondary" size="sm">{{ __('app.collect') }}</x-button>
                                    </form>
                                @endunless
                                <form method="POST" action="{{ route('receivables.destroy', $receivable) }}" onsubmit="return confirm('{{ __('app.confirm_delete_receivable') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" size="sm">{{ __('app.delete') }}</x-button>
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
    </x-table-wrapper>

    <x-table-wrapper class="mb-25">
        <x-page-header flat class="mb-0">
            <x-slot:heading>
                <h2 class="page-title text-lg">{{ __('app.add_new_payable') }}</h2>
            </x-slot:heading>
        </x-page-header>

        <form method="POST" action="{{ route('payables.store') }}" class="p-18">
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
            <x-button variant="primary">{{ __('app.add_payable') }}</x-button>
        </form>
    </x-table-wrapper>

    <x-table-wrapper>
        <x-page-header flat class="mb-0">
            <x-slot:heading>
                <h2 class="page-title text-lg">{{ __('app.payables_list') }}</h2>
            </x-slot:heading>
        </x-page-header>

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
                                <x-badge variant="success">{{ __('app.paid_off') }}</x-badge>
                            @else
                                ৳ {{ number_format($payable->dueAmount(), 2) }}
                            @endif
                        </td>
                        <td>{{ $payable->note ?? '-' }}</td>
                        <td class="text-right">
                            <div class="table-actions">
                                @unless ($payable->isPaidOff())
                                    <form method="POST" action="{{ route('payables.payment', $payable) }}" class="d-inline-flex gap-4">
                                        @csrf
                                        <input type="number" step="0.01" min="0.01" max="{{ $payable->dueAmount() }}" name="payment_amount" class="form-control input-amount" placeholder="{{ __('app.amount') }}" required>
                                        <x-button variant="secondary" size="sm">{{ __('app.payment') }}</x-button>
                                    </form>
                                @endunless
                                <form method="POST" action="{{ route('payables.destroy', $payable) }}" onsubmit="return confirm('{{ __('app.confirm_delete_payable') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" size="sm">{{ __('app.delete') }}</x-button>
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
    </x-table-wrapper>

</div>

@endsection
