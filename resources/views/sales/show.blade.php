@extends('layouts.app')

@section('title', __('app.invoice') . ' ' . $sale->invoice_no)

@section('content')

<div class="page">

    <x-page-header :title="__('app.invoice') . ' ' . $sale->invoice_no" :subtitle="$sale->created_at->format('d M Y, h:i A')">
        <x-slot:actions>
            <div class="d-flex gap-10 no-print">
                <x-button tag="a" href="{{ route('sales.index') }}" variant="secondary">&larr; {{ __('app.back_to_list') }}</x-button>
                <x-button variant="primary" type="button" onclick="window.print()">🖨️ {{ __('app.print') }}</x-button>
            </div>
        </x-slot:actions>
    </x-page-header>

    @if (session('success'))
        <x-alert variant="success">{{ session('success') }}</x-alert>
    @endif

    <x-card class="mb-20">
        <div class="form-row">
            <div>
                <div class="form-label">{{ __('app.customer') }}</div>
                <div>{{ $sale->customer_name ?? __('app.walk_in_customer') }}</div>
            </div>
            <div>
                <div class="form-label">{{ __('app.payment_status') }}</div>
                <div>
                    @if ($sale->payment_status === 'paid')
                        <x-badge variant="success">{{ __('app.paid') }}</x-badge>
                    @elseif ($sale->payment_status === 'partial')
                        <x-badge variant="warning">{{ __('app.partial') }}</x-badge>
                    @else
                        <x-badge variant="danger">{{ __('app.due') }}</x-badge>
                    @endif
                </div>
            </div>
        </div>
    </x-card>

    <x-table-wrapper>
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

        <div class="summary-panel">
            <div class="summary-box">
                <div class="list-row">
                    <span>{{ __('app.subtotal') }}</span>
                    <span>৳ {{ number_format($sale->subtotal, 2) }}</span>
                </div>
                <div class="list-row">
                    <span>{{ __('app.discount') }}</span>
                    <span>- ৳ {{ number_format($sale->discount, 2) }}</span>
                </div>
                <div class="list-row summary-row-total">
                    <span>{{ __('app.total') }}</span>
                    <span>৳ {{ number_format($sale->total, 2) }}</span>
                </div>
                <div class="list-row">
                    <span>{{ __('app.paid') }}</span>
                    <span>৳ {{ number_format($sale->paid_amount, 2) }}</span>
                </div>
                <div class="list-row fw-700">
                    <span>{{ __('app.due') }}</span>
                    <span>৳ {{ number_format($sale->due_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </x-table-wrapper>

</div>

@endsection
