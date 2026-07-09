@extends('layouts.app')

@section('title', __('app.nav_shops'))

@section('content')

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('app.nav_shops') }}</h1>
            <p class="page-subtitle">{{ __('app.shops_subtitle') }}</p>
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

    @if (auth()->user()->isSuperAdmin())
        <div class="card" style="margin-bottom:20px;">
            <form method="POST" action="{{ route('shops.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('app.shop_name') }} <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="{{ __('app.shop_name_eg') }}">
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('app.address') }}</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="{{ __('app.optional') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('app.phone') }}</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="{{ __('app.optional') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.shop_owner_optional') }}</label>
                    <div style="display:flex;flex-wrap:wrap;gap:16px;">
                        @forelse ($owners as $owner)
                            <label class="form-check">
                                <input type="checkbox" name="owner_ids[]" value="{{ $owner->id }}">
                                {{ $owner->name }} ({{ $owner->email }})
                            </label>
                        @empty
                            <span style="color:#999;">{{ __('app.no_owners_yet') }} <a href="{{ route('users.create') }}">{{ __('app.create_here') }}</a>।</span>
                        @endforelse
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">+ {{ __('app.add_shop') }}</button>
            </form>
        </div>
    @endif

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.shop_name') }}</th>
                    <th>{{ __('app.address') }}</th>
                    <th>{{ __('app.phone') }}</th>
                    @if (auth()->user()->isSuperAdmin())
                        <th>{{ __('app.owner') }}</th>
                    @endif
                    <th class="text-right">{{ __('app.products') }}</th>
                    <th class="text-right">{{ __('app.sales') }}</th>
                    <th class="text-right">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shops as $shop)
                    <tr>
                        <td>
                            {{ $shop->name }}
                            @if ($shop->id === $currentShop->id)
                                <span class="badge badge-success">{{ __('app.active') }}</span>
                            @endif
                        </td>
                        <td>{{ $shop->address ?? '-' }}</td>
                        <td>{{ $shop->phone ?? '-' }}</td>
                        @if (auth()->user()->isSuperAdmin())
                            <td>
                                {{ $shop->owners()->pluck('name')->join(', ') ?: '-' }}
                            </td>
                        @endif
                        <td class="text-right">{{ $shop->products_count }}</td>
                        <td class="text-right">{{ $shop->sales_count }}</td>
                        <td class="text-right">
                            @if ($shop->id !== $currentShop->id)
                                <form method="POST" action="{{ route('shops.switch') }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                    <button type="submit" class="btn btn-secondary btn-sm">{{ __('app.select') }}</button>
                                </form>
                            @endif
                            @if (auth()->user()->isSuperAdmin())
                                <form method="POST" action="{{ route('shops.destroy', $shop) }}" style="display:inline;" onsubmit="return confirm('{{ __('app.confirm_delete_shop') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isSuperAdmin() ? 7 : 6 }}" class="table-empty">{{ __('app.no_shops_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
