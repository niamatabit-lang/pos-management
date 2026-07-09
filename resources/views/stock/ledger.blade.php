@extends('layouts.app')

@section('title', __('app.nav_stock_ledger'))

@section('content')

<div class="page">

    <div class="page-header">

        <div>
            <h1 class="page-title">
                {{ __('app.nav_stock_ledger') }}
            </h1>

            <p class="page-subtitle">
                {{ __('app.stock_ledger_subtitle') }}
            </p>
        </div>

        <a href="{{ route('stock.index') }}" class="btn btn-secondary">
            &larr; {{ __('app.back_to_stock_list') }}
        </a>

    </div>

    <div class="card" style="margin-bottom:20px;">
        <form method="GET" action="{{ route('stock.ledger') }}" class="form-row-3">

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">{{ __('app.date') }}</label>
                <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}">
            </div>

            <div class="form-group" style="margin-bottom:0;display:flex;align-items:flex-end;gap:10px;">
                <button type="submit" class="btn btn-primary">{{ __('app.search') }}</button>
                <a href="{{ route('stock.ledger') }}" class="btn btn-secondary">{{ __('app.today') }}</a>
            </div>

        </form>
    </div>

    <div class="table-wrapper">
        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;">
                {{ __('app.stock_account_for_date', ['date' => $date->format('d M Y')]) }}
            </h2>
        </div>

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
                        <td>{{ $row['product']->name }} <small style="color:#999;">({{ $row['product']->sku }})</small></td>
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
    </div>

</div>

@endsection
