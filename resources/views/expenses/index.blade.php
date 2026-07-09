@extends('layouts.app')

@section('title', __('app.nav_expenses'))

@section('content')

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('app.nav_expenses') }}</h1>
            <p class="page-subtitle">{{ __('app.expenses_subtitle') }}</p>
        </div>
    </div>

    @if (session('success'))
        <div style="background:#d1f4df;color:#198754;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    <div class="kpi-grid" style="grid-template-columns:repeat(2,1fr);margin-bottom:20px;">
        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.month_total_expense') }}</div>
            <div class="kpi-value">৳ {{ number_format($totalThisMonth, 2) }}</div>
        </div>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <h2 style="font-size:18px;margin-bottom:15px;">{{ __('app.add_new_expense') }}</h2>

        <form method="POST" action="{{ route('expenses.store') }}">
            @csrf

            <div class="form-row-4">
                <div class="form-group">
                    <label class="form-label">{{ __('app.expense_title') }} <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="{{ __('app.expense_title_eg') }}" value="{{ old('title') }}">
                    @error('title') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.category') }}</label>
                    <select name="category" class="form-select">
                        <option value="">-- {{ __('app.select') }} --</option>
                        @foreach ($categories as $key => $label)
                            <option value="{{ $key }}" @selected(old('category') == $key)>{{ __('app.expense_cat_' . $key) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.amount_taka') }} <span class="required">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="{{ old('amount') }}">
                    @error('amount') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.date') }} <span class="required">*</span></label>
                    <input type="date" name="date" class="form-control" value="{{ old('date', now()->toDateString()) }}">
                    @error('date') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('app.note') }}</label>
                <input type="text" name="note" class="form-control" placeholder="{{ __('app.optional') }}" value="{{ old('note') }}">
            </div>

            <button type="submit" class="btn btn-primary">{{ __('app.add_expense') }}</button>
        </form>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.date') }}</th>
                    <th>{{ __('app.expense_title') }}</th>
                    <th>{{ __('app.category') }}</th>
                    <th class="text-right">{{ __('app.amount') }}</th>
                    <th>{{ __('app.note') }}</th>
                    <th class="text-right">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($expenses as $expense)
                    <tr>
                        <td>{{ $expense->date->format('d M Y') }}</td>
                        <td>{{ $expense->title }}</td>
                        <td>{{ $expense->category ? __('app.expense_cat_' . $expense->category) : '-' }}</td>
                        <td class="text-right">৳ {{ number_format($expense->amount, 2) }}</td>
                        <td>{{ $expense->note ?? '-' }}</td>
                        <td class="text-right">
                            <form method="POST" action="{{ route('expenses.destroy', $expense) }}" onsubmit="return confirm('{{ __('app.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="table-empty">{{ __('app.no_expenses') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="table-footer">
            <div>
                {{ __('app.showing_results', ['from' => $expenses->firstItem() ?? 0, 'to' => $expenses->lastItem() ?? 0, 'total' => $expenses->total()]) }}
            </div>

            {{ $expenses->links('vendor.pagination.custom') }}
        </div>
    </div>

</div>

@endsection
