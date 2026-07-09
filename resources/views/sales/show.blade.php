@extends('layouts.app')

@section('title', __('app.invoice') . ' ' . $sale->invoice_no)

@section('content')

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('app.invoice') }} {{ $sale->invoice_no }}</h1>
            <p class="page-subtitle">{{ $sale->created_at->format('d M Y, h:i A') }}</p>
        </div>

        <div class="d-flex gap-10 no-print">
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">&larr; {{ __('app.back_to_list') }}</a>
            <button onclick="window.print()" class="btn btn-primary">🖨️ {{ __('app.print') }}</button>
        </div>
    </div>

    @if (session('success'))
        <div style="background:#d1f4df;color:#198754;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="margin-bottom:20px;">
        <div class="form-row">
            <div>
                <div class="form-label">{{ __('app.customer') }}</div>
                <div>{{ $sale->customer_name ?? __('app.walk_in_customer') }}</div>
            </div>
            <div>
                <div class="form-label">{{ __('app.payment_status') }}</div>
                <div>
                    @if ($sale->payment_status === 'paid')
                        <span class="badge badge-success">{{ __('app.paid') }}</span>
                    @elseif ($sale->payment_status === 'partial')
                        <span class="badge badge-warning">{{ __('app.partial') }}</span>
                    @else
                        <span class="badge badge-danger">{{ __('app.due') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.product') }}</th>
                    <th class="text-right">{{ __('app.unit_price') }}</th>
                    <th class="text-right">{{ __('app.quantity') }}</th>
                    <th class="text-right">{{ __('app.subtotal') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td class="text-right">৳ {{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">{{ number_format($item->quantity) }}</td>
                        <td class="text-right">৳ {{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="padding:20px;display:flex;justify-content:flex-end;">
            <div style="min-width:280px;">
                <div class="d-flex justify-between" style="padding:6px 0;">
                    <span>{{ __('app.subtotal') }}</span>
                    <span>৳ {{ number_format($sale->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-between" style="padding:6px 0;">
                    <span>{{ __('app.discount') }}</span>
                    <span>- ৳ {{ number_format($sale->discount, 2) }}</span>
                </div>
                <div class="d-flex justify-between" style="padding:6px 0;font-weight:700;font-size:18px;color:#198754;border-top:1px solid #eee;margin-top:6px;">
                    <span>{{ __('app.total') }}</span>
                    <span>৳ {{ number_format($sale->total, 2) }}</span>
                </div>
                <div class="d-flex justify-between" style="padding:6px 0;">
                    <span>{{ __('app.paid') }}</span>
                    <span>৳ {{ number_format($sale->paid_amount, 2) }}</span>
                </div>
                <div class="d-flex justify-between" style="padding:6px 0;font-weight:700;">
                    <span>{{ __('app.due') }}</span>
                    <span>৳ {{ number_format($sale->due_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
