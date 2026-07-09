@extends('layouts.app')

@section('title', __('app.edit_user'))

@section('content')

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ $user->name }} — {{ __('app.edit') }}</h1>
            <p class="page-subtitle">{{ __('app.leave_password_blank_note') }}</p>
        </div>

        <a href="{{ route('users.index') }}" class="btn btn-secondary">&larr; {{ __('app.back') }}</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('app.name') }} <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.email') }} <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('app.new_password_optional') }}</label>
                    <input type="password" name="password" class="form-control">
                    @error('password') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="form-check">
                    <input type="checkbox" name="is_active" value="1" @checked($user->is_active)>
                    {{ __('app.id_will_stay_active') }}
                </label>
            </div>

            @if ($mode === 'shop_owner')

                <div class="form-group">
                    <label class="form-label">{{ __('app.shops_to_grant_access') }}</label>
                    <div style="display:flex;flex-wrap:wrap;gap:16px;">
                        @forelse ($shops as $shop)
                            <label class="form-check">
                                <input type="checkbox" name="shop_ids[]" value="{{ $shop->id }}" @checked(in_array($shop->id, $assignedShopIds))>
                                {{ $shop->name }}
                            </label>
                        @empty
                            <span style="color:#999;">{{ __('app.no_shops_found') }}</span>
                        @endforelse
                    </div>
                </div>

            @else

                <div class="form-group">
                    <label class="form-label">{{ __('app.shop') }}</label>
                    <select name="shop_id" class="form-select">
                        @forelse ($shops as $shop)
                            <option value="{{ $shop->id }}" @selected($currentShopId === $shop->id)>{{ $shop->name }}</option>
                        @empty
                            <option value="" disabled>{{ __('app.no_shop_access') }}</option>
                        @endforelse
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.permissions_checkbox_label') }}</label>
                    <div style="display:flex;flex-wrap:wrap;gap:16px;">
                        @foreach ($permissions as $key => $label)
                            <label class="form-check">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}" @checked(in_array($key, $currentPermissions))>
                                {{ __('app.permission_' . $key) }}
                            </label>
                        @endforeach
                    </div>
                </div>

            @endif

            <button type="submit" class="btn btn-primary">{{ __('app.update') }}</button>
        </form>
    </div>

</div>

@endsection
