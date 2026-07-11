@extends('layouts.app')

@section('title', __('app.nav_stock_ledger'))

@section('content')

<div class="page">

    <x-page-header :title="__('app.nav_stock_ledger')" :subtitle="__('app.stock_ledger_subtitle')">
        <x-slot:actions>
            <x-button tag="a" href="{{ route('stock.index') }}" variant="secondary">&larr; {{ __('app.back_to_stock_list') }}</x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card class="mb-20">
        <form method="GET" action="{{ route('stock.ledger') }}" class="form-row-3">

            <div class="form-group form-group-flush">
                <label class="form-label">{{ __('app.date') }}</label>
                <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}">
            </div>

            <div class="form-group form-group-flush form-group-inline">
                <x-button variant="primary" type="submit">{{ __('app.search') }}</x-button>
                <x-button tag="a" href="{{ route('stock.ledger') }}" variant="secondary">{{ __('app.today') }}</x-button>
            </div>

        </form>
    </x-card>

    <x-table-wrapper>
        <x-page-header flat class="mb-0">
            <x-slot:heading>
                <h2 class="page-title text-lg">
                    {{ __('app.stock_account_for_date', ['date' => $date->format('d M Y')]) }}
                </h2>
            </x-slot:heading>
        </x-page-header>

        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.product') }}</th>
                    <th class="text-right">{{ __('app.opening_stock') }}</th>
                    <th class="text-right">{{ __('app.sold_decreased_today') }}</th>
                    <th class="text-right">{{ __('app.closing_stock') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr>
                        <td>{{ $row['product']->name }} <small class="text-muted-note">({{ $row['product']->sku }})</small></td>
                        <td class="text-right">{{ number_format($row['opening']) }} {{ $row['product']->unit }}</td>
                        <td class="text-right">{{ number_format($row['out_during']) }} {{ $row['product']->unit }}</td>
                        <td class="text-right"><strong>{{ number_format($row['closing']) }} {{ $row['product']->unit }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="table-empty">
                            {{ __('app.no_products_for_date') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-table-wrapper>

</div>

@endsection
