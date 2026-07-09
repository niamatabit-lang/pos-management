@extends('layouts.app')

@section('title', __('app.nav_stock'))

@section('content')

<div class="page">

    <div class="page-header">

        <div>
            <h1 class="page-title">
                {{ __('app.nav_stock') }}
            </h1>

            <p class="page-subtitle">
                {{ __('app.stock_page_subtitle') }}
            </p>
        </div>

        <a href="{{ route('stock.create') }}" class="btn btn-primary">
            + {{ __('app.stock_adjustment') }}
        </a>

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
        <form method="GET" action="{{ route('stock.index') }}" class="form-row-3">

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">{{ __('app.product') }}</label>
                <select name="product_id" class="form-select">
                    <option value="">{{ __('app.all_products') }}</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">{{ __('app.type') }}</label>
                <select name="type" class="form-select">
                    <option value="">{{ __('app.all_types') }}</option>
                    <option value="out" @selected(request('type') == 'out')>{{ __('app.stock_decreased') }}</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom:0;display:flex;align-items:flex-end;gap:10px;">
                <button type="submit" class="btn btn-secondary">{{ __('app.filter') }}</button>
                <a href="{{ route('stock.index') }}" class="btn btn-secondary">{{ __('app.reset') }}</a>
            </div>

        </form>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.date') }}</th>
                    <th>{{ __('app.product') }}</th>
                    <th>{{ __('app.type') }}</th>
                    <th class="text-right">{{ __('app.quantity') }}</th>
                    <th>{{ __('app.note') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($movements as $movement)
                    <tr>
                        <td>{{ $movement->created_at->format('d M Y, h:i A') }}</td>
                        <td>{{ $movement->product->name ?? 'N/A' }}</td>
                        <td>
                            @if ($movement->type === 'in')
                                <span class="badge badge-success">{{ __('app.stock_in') }}</span>
                            @else
                                <span class="badge badge-danger">{{ __('app.stock_out') }}</span>
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($movement->quantity) }}</td>
                        <td>{{ $movement->note ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="table-empty">
                            {{ __('app.no_stock_movements') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="table-footer">
            <div>
                {{ __('app.showing_results', ['from' => $movements->firstItem() ?? 0, 'to' => $movements->lastItem() ?? 0, 'total' => $movements->total()]) }}
            </div>

            {{ $movements->links('vendor.pagination.custom') }}
        </div>
    </div>

</div>

@endsection
