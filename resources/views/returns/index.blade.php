@extends('layouts.app')

@section('title', __('app.nav_returns'))

@section('content')

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('app.returns_title') }}</h1>
            <p class="page-subtitle">{{ __('app.returns_subtitle') }}</p>
        </div>
    </div>

    @if (session('success'))
        <div style="background:#d1f4df;color:#198754;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="background:#fde2e2;color:#dc3545;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            {{ session('error') }}
        </div>
    @endif

    <div class="card" style="margin-bottom:20px;">
        <form method="GET" action="{{ route('returns.index') }}" style="display:flex;gap:12px;align-items:flex-end;">
            <div class="form-group" style="margin-bottom:0;flex:1;">
                <label class="form-label">{{ __('app.invoice_number') }}</label>
                <input type="text" name="invoice" class="form-control" placeholder="{{ __('app.invoice_eg') }}" value="{{ request('invoice') }}">
            </div>
            <button type="submit" class="btn btn-primary">{{ __('app.search') }}</button>
        </form>
    </div>

    @if (request('invoice') && ! $sale)
        <div style="background:#fff3cd;color:#856404;padding:14px 18px;border-radius:10px;margin-bottom:20px;">
            {{ __('app.no_sale_for_invoice') }}
        </div>
    @endif

    @if ($sale)
        <div class="table-wrapper" style="margin-bottom:25px;">
            <div class="page-header" style="padding:18px 18px 0;">
                <h2 class="page-title" style="font-size:18px;">{{ $sale->invoice_no }} — {{ $sale->customer_name ?? __('app.walk_in_customer') }}</h2>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('app.product') }}</th>
                        <th class="text-right">{{ __('app.unit_price') }}</th>
                        <th class="text-right">{{ __('app.current_quantity') }}</th>
                        <th class="text-right">{{ __('app.return_item') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sale->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td class="text-right">৳ {{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">
                                @if ($item->quantity > 0)
                                    <form method="POST" action="{{ route('returns.store') }}" style="display:flex;gap:6px;justify-content:flex-end;flex-wrap:wrap;">
                                        @csrf
                                        <input type="hidden" name="sale_item_id" value="{{ $item->id }}">
                                        <input type="number" name="quantity" class="form-control" style="width:70px;height:34px;padding:0 6px;" min="1" max="{{ $item->quantity }}" placeholder="{{ __('app.amount') }}" required>
                                        <input type="text" name="reason" class="form-control" style="width:130px;height:34px;padding:0 6px;" placeholder="{{ __('app.reason_optional') }}">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('app.confirm_return') }}');">{{ __('app.return') }}</button>
                                    </form>
                                @else
                                    <span class="badge badge-secondary">{{ __('app.fully_returned') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="table-empty">{{ __('app.no_items') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    <div class="table-wrapper">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;">{{ __('app.recent_returns') }}</h2>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.date') }}</th>
                    <th>{{ __('app.invoice') }}</th>
                    <th>{{ __('app.product') }}</th>
                    <th class="text-right">{{ __('app.amount') }}</th>
                    <th class="text-right">{{ __('app.refund') }}</th>
                    <th>{{ __('app.reason') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentReturns as $return)
                    <tr>
                        <td>{{ $return->created_at->format('d M Y, h:i A') }}</td>
                        <td>{{ $return->sale->invoice_no ?? '-' }}</td>
                        <td>{{ $return->product->name ?? '-' }}</td>
                        <td class="text-right">{{ $return->quantity }}</td>
                        <td class="text-right">৳ {{ number_format($return->refund_amount, 2) }}</td>
                        <td>{{ $return->reason ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="table-empty">{{ __('app.no_returns_yet') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
