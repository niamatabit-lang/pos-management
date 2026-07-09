@extends('layouts.app')

@section('title', __('app.nav_service_fees'))

@section('content')

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('app.nav_service_fees') }}</h1>
            <p class="page-subtitle">{{ __('app.service_fees_subtitle') }}</p>
        </div>
    </div>

    @if (session('success'))
        <div style="background:#d1f4df;color:#198754;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    <div class="kpi-grid" style="grid-template-columns:repeat(2,1fr);margin-bottom:20px;">
        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.today_service_fee_commission') }}</div>
            <div class="kpi-value">৳ {{ number_format($todayTotal, 2) }}</div>
        </div>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <h2 style="font-size:18px;margin-bottom:15px;">{{ __('app.add_new_service_fee') }}</h2>

        <form method="POST" action="{{ route('service-fees.store') }}">
            @csrf

            <div class="form-row-4">
                <div class="form-group">
                    <label class="form-label">{{ __('app.service_name') }} <span class="required">*</span></label>
                    <input type="text" name="service_name" class="form-control" placeholder="{{ __('app.service_name_eg') }}" value="{{ old('service_name') }}">
                    @error('service_name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.mobile_number') }}</label>
                    <input type="text" name="mobile_number" class="form-control" placeholder="{{ __('app.optional') }}" value="{{ old('mobile_number') }}">
                    @error('mobile_number') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.sale_price') }} (৳) <span class="required">*</span></label>
                    <input type="number" step="0.01" min="0" name="sale_price" class="form-control" value="{{ old('sale_price') }}">
                    @error('sale_price') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.commission') }} (৳) <span class="required">*</span></label>
                    <input type="number" step="0.01" min="0" name="commission" class="form-control" value="{{ old('commission') }}">
                    @error('commission') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('app.add') }}</button>
        </form>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.date') }}</th>
                    <th>{{ __('app.service_name') }}</th>
                    <th>{{ __('app.mobile_number') }}</th>
                    <th class="text-right">{{ __('app.sale_price') }}</th>
                    <th class="text-right">{{ __('app.commission') }}</th>
                    <th class="text-right">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($serviceFees as $fee)
                    <tr>
                        <td>{{ $fee->created_at->format('d M Y, h:i A') }}</td>
                        <td>{{ $fee->service_name }}</td>
                        <td>{{ $fee->mobile_number ?? '-' }}</td>
                        <td class="text-right">৳ {{ number_format($fee->sale_price, 2) }}</td>
                        <td class="text-right">৳ {{ number_format($fee->commission, 2) }}</td>
                        <td class="text-right">
                            <form method="POST" action="{{ route('service-fees.destroy', $fee) }}" onsubmit="return confirm('{{ __('app.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="table-empty">{{ __('app.no_service_fees') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="table-footer">
            <div>
                {{ __('app.showing_results', ['from' => $serviceFees->firstItem() ?? 0, 'to' => $serviceFees->lastItem() ?? 0, 'total' => $serviceFees->total()]) }}
            </div>

            {{ $serviceFees->links('vendor.pagination.custom') }}
        </div>
    </div>

</div>

@endsection
