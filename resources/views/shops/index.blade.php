@extends('layouts.app')

@section('title', __('app.nav_shops'))

@section('content')

<div class="page">

    <x-page-header :title="__('app.nav_shops')" :subtitle="__('app.shops_subtitle')" />

    @if (session('success'))
        <x-alert variant="success">{{ session('success') }}</x-alert>
    @endif

    @if (session('error'))
        <x-alert variant="danger">{{ session('error') }}</x-alert>
    @endif

    @if (auth()->user()->isSuperAdmin())
        <x-card class="mb-20">
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
                    <div class="d-flex flex-wrap gap-16">
                        @forelse ($owners as $owner)
                            <label class="form-check">
                                <input type="checkbox" name="owner_ids[]" value="{{ $owner->id }}">
                                {{ $owner->name }} ({{ $owner->email }})
                            </label>
                        @empty
                            <span class="text-muted-note">{{ __('app.no_owners_yet') }} <a href="{{ route('users.create') }}" class="text-primary">{{ __('app.create_here') }}</a>।</span>
                        @endforelse
                    </div>
                </div>

                <x-button variant="primary">+ {{ __('app.add_shop') }}</x-button>
            </form>
        </x-card>
    @endif

    <x-table-wrapper>
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
                                <x-badge variant="success">{{ __('app.active') }}</x-badge>
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
                                <form method="POST" action="{{ route('shops.switch') }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                    <x-button variant="secondary" size="sm">{{ __('app.select') }}</x-button>
                                </form>
                            @endif
                            @if (auth()->user()->isSuperAdmin())
                                <form method="POST" action="{{ route('shops.destroy', $shop) }}" class="d-inline" onsubmit="return confirm('{{ __('app.confirm_delete_shop') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" size="sm">{{ __('app.delete') }}</x-button>
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
    </x-table-wrapper>

</div>

@endsection
