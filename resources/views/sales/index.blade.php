@extends('layouts.app')

@section('title', __('app.nav_sales'))

@section('content')

<div class="page">

    <x-page-header :title="__('app.sales')" :subtitle="__('app.sales_subtitle')">
        <x-slot:actions>
            <x-button tag="a" href="{{ route('sales.create') }}" variant="primary">+ {{ __('app.new_sale') }}</x-button>
        </x-slot:actions>
    </x-page-header>

    @if (session('success'))
        <x-alert variant="success">{{ session('success') }}</x-alert>
    @endif

    <x-card class="mb-20">
        <form method="GET" action="{{ route('sales.index') }}" class="form-row-3">

            <div class="form-group form-group-flush">
                <label class="form-label">{{ __('app.search') }}</label>
                <input type="text" name="search" class="form-control" placeholder="{{ __('app.search_invoice_customer') }}" value="{{ request('search') }}">
            </div>

            <div class="form-group form-group-flush">
                <label class="form-label">{{ __('app.date') }}</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>

            <div class="form-group form-group-flush form-group-inline">
                <x-button variant="secondary" type="submit">{{ __('app.filter') }}</x-button>
                <x-button tag="a" href="{{ route('sales.index') }}" variant="secondary">{{ __('app.reset') }}</x-button>
            </div>

        </form>
    </x-card>

    <x-table-wrapper>
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
                                <x-badge variant="success">{{ __('app.paid') }}</x-badge>
                            @elseif ($sale->payment_status === 'partial')
                                <x-badge variant="warning">{{ __('app.partial') }}</x-badge>
                            @else
                                <x-badge variant="danger">{{ __('app.due') }}</x-badge>
                            @endif
                        </td>
                        <td class="text-right">
                            <x-button tag="a" href="{{ route('sales.show', $sale) }}" variant="secondary" size="sm">{{ __('app.view') }}</x-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="table-empty">{{ __('app.no_sales_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <x-slot:footer>
            <div>
                {{ __('app.showing_results', ['from' => $sales->firstItem() ?? 0, 'to' => $sales->lastItem() ?? 0, 'total' => $sales->total()]) }}
            </div>

            {{ $sales->links('vendor.pagination.custom') }}
        </x-slot:footer>
    </x-table-wrapper>

</div>

@endsection
