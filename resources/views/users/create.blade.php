@extends('layouts.app')

@section('title', __('app.add_user'))

@section('content')

<div class="page">

    <x-page-header>
        <x-slot:heading>
            <h1 class="page-title">
                @if ($mode === 'shop_owner')
                    {{ __('app.create_shop_owner') }}
                @else
                    {{ __('app.create_manager_employee') }}
                @endif
            </h1>
            <p class="page-subtitle">
                @if ($mode === 'shop_owner')
                    {{ __('app.shop_owner_access_note') }}
                @else
                    {{ __('app.permissions_note') }}
                @endif
            </p>
        </x-slot:heading>

        <x-slot:actions>
            <x-button tag="a" href="{{ route('users.index') }}" variant="secondary">&larr; {{ __('app.back') }}</x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('app.name') }} <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.email') }} <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('app.password') }} <span class="required">*</span></label>
                    <input type="password" name="password" class="form-control">
                    @error('password') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.confirm_password') }} <span class="required">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>

            @if ($mode === 'shop_owner')

                <div class="form-group">
                    <label class="form-label">{{ __('app.shops_to_grant_access') }} <span class="required">*</span></label>
                    <div class="d-flex flex-wrap gap-16">
                        @forelse ($shops as $shop)
                            <label class="form-check">
                                <input type="checkbox" name="shop_ids[]" value="{{ $shop->id }}" @checked(collect(old('shop_ids'))->contains($shop->id))>
                                {{ $shop->name }}
                            </label>
                        @empty
                            <span class="text-muted-note">{{ __('app.no_shops_found_create_one') }}</span>
                        @endforelse
                    </div>
                    @error('shop_ids') <div class="form-error">{{ $message }}</div> @enderror
                </div>

            @else

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ __('app.role') }} <span class="required">*</span></label>
                        <select name="role" class="form-select">
                            <option value="manager" @selected(old('role') === 'manager')>{{ __('app.role_manager') }}</option>
                            <option value="employee" @selected(old('role') === 'employee')>{{ __('app.role_employee') }}</option>
                        </select>
                        @error('role') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ __('app.shop') }} <span class="required">*</span></label>
                        <select name="shop_id" class="form-select">
                            <option value="">-- {{ __('app.select_shop') }} --</option>
                            @forelse ($shops as $shop)
                                <option value="{{ $shop->id }}" @selected((string) old('shop_id') === (string) $shop->id)>{{ $shop->name }}</option>
                            @empty
                                <option value="" disabled>{{ __('app.no_shop_access') }}</option>
                            @endforelse
                        </select>
                        @error('shop_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.permissions_checkbox_label') }}</label>
                    <div class="d-flex flex-wrap gap-16">
                        @foreach ($permissions as $key => $label)
                            <label class="form-check">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}" @checked(collect(old('permissions'))->contains($key))>
                                {{ __('app.permission_' . $key) }}
                            </label>
                        @endforeach
                    </div>
                </div>

            @endif

            <x-button variant="primary">{{ __('app.save') }}</x-button>
        </form>
    </x-card>

</div>

@endsection
