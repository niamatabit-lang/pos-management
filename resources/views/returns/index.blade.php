@extends('layouts.app')

@section('title', __('app.nav_returns'))

@section('content')

<div class="page">

    <x-page-header :title="__('app.returns_title')" :subtitle="__('app.returns_subtitle')" />

    @if (session('success'))
        <x-alert variant="success">{{ session('success') }}</x-alert>
    @endif

    @if (session('error'))
        <x-alert variant="danger">{{ session('error') }}</x-alert>
    @endif

    <x-card class="mb-20">
        <form method="GET" action="{{ route('returns.index') }}" class="d-flex gap-12 align-end">
            <div class="form-group form-group-flush flex-1">
                <label class="form-label">{{ __('app.invoice_number') }}</label>
                <input type="text" name="invoice" class="form-control" placeholder="{{ __('app.invoice_eg') }}" value="{{ request('invoice') }}">
            </div>
            <x-button variant="primary">{{ __('app.search') }}</x-button>
        </form>
    </x-card>

    @if (request('invoice') && ! $sale)
        <x-alert variant="warning">
            {{ __('app.no_sale_for_invoice') }}
        </x-alert>
    @endif

    @if ($sale)
        <x-table-wrapper class="mb-25">
            <x-page-header flat class="mb-0">
                <x-slot:heading>
                    <h2 class="page-title text-lg">{{ $sale->invoice_no }} — {{ $sale->customer_name ?? __('app.walk_in_customer') }}</h2>
                </x-slot:heading>
            </x-page-header>

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
                                    <form method="POST" action="{{ route('returns.store') }}" class="table-actions">
                                        @csrf
                                        <input type="hidden" name="sale_item_id" value="{{ $item->id }}">
                                        <input type="number" name="quantity" class="form-control input-qty" min="1" max="{{ $item->quantity }}" placeholder="{{ __('app.amount') }}" required>
                                        <input type="text" name="reason" class="form-control input-reason" placeholder="{{ __('app.reason_optional') }}">
                                        <x-button variant="danger" size="sm" onclick="return confirm('{{ __('app.confirm_return') }}');">{{ __('app.return') }}</x-button>
                                    </form>
                                @else
                                    <x-badge variant="secondary">{{ __('app.fully_returned') }}</x-badge>
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
        </x-table-wrapper>
    @endif

    <x-table-wrapper>
        <x-page-header flat class="mb-0">
            <x-slot:heading>
                <h2 class="page-title text-lg">{{ __('app.recent_returns') }}</h2>
            </x-slot:heading>
        </x-page-header>

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
    </x-table-wrapper>

</div>

@endsection
