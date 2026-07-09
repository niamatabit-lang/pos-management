@extends('layouts.app')

@section('title', __('app.nav_sales'))

@section('content')

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('app.sales') }}</h1>
            <p class="page-subtitle">{{ __('app.sales_subtitle') }}</p>
        </div>

        <a href="{{ route('sales.create') }}" class="btn btn-primary">+ {{ __('app.new_sale') }}</a>
    </div>

    @if (session('success'))
        <div style="background:#d1f4df;color:#198754;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="margin-bottom:20px;">
        <form method="GET" action="{{ route('sales.index') }}" class="form-row-3">

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">{{ __('app.search') }}</label>
                <input type="text" name="search" class="form-control" placeholder="{{ __('app.search_invoice_customer') }}" value="{{ request('search') }}">
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">{{ __('app.date') }}</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>

            <div class="form-group" style="margin-bottom:0;display:flex;align-items:flex-end;gap:10px;">
                <button type="submit" class="btn btn-secondary">{{ __('app.filter') }}</button>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">{{ __('app.reset') }}</a>
            </div>

        </form>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.invoice') }}</th>
                    <th>{{ __('app.date') }}</th>
                    <th>{{ __('app.customer') }}</th>
                    <th class="text-right">{{ __('app.total') }}</th>
                    <th class="text-right">{{ __('app.paid') }}</th>
                    <th class="text-right">{{ __('app.due') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th class="text-right">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td>{{ $sale->invoice_no }}</td>
                        <td>{{ $sale->created_at->format('d M Y, h:i A') }}</td>
                        <td>{{ $sale->customer_name ?? __('app.walk_in_customer') }}</td>
                        <td class="text-right">৳ {{ number_format($sale->total, 2) }}</td>
                        <td class="text-right">৳ {{ number_format($sale->paid_amount, 2) }}</td>
                        <td class="text-right">৳ {{ number_format($sale->due_amount, 2) }}</td>
                        <td>
                            @if ($sale->payment_status === 'paid')
                                <span class="badge badge-success">{{ __('app.paid') }}</span>
                            @elseif ($sale->payment_status === 'partial')
                                <span class="badge badge-warning">{{ __('app.partial') }}</span>
                            @else
                                <span class="badge badge-danger">{{ __('app.due') }}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary btn-sm">{{ __('app.view') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="table-empty">{{ __('app.no_sales_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="table-footer">
            <div>
                {{ __('app.showing_results', ['from' => $sales->firstItem() ?? 0, 'to' => $sales->lastItem() ?? 0, 'total' => $sales->total()]) }}
            </div>

            {{ $sales->links('vendor.pagination.custom') }}
        </div>
    </div>

</div>

@endsection
