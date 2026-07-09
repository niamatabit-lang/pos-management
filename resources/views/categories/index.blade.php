@extends('layouts.app')

@section('title', __('app.categories'))

@section('content')

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('app.categories') }}</h1>
            <p class="page-subtitle">{{ __('app.categories_subtitle') }}</p>
        </div>
    </div>

    @if (session('success'))
        <div style="background:#d1f4df;color:#198754;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="margin-bottom:20px;">
        <form method="POST" action="{{ route('categories.store') }}" class="form-row">
            @csrf
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">{{ __('app.category_name') }} <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="{{ __('app.category_name_eg') }}">
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" style="margin-bottom:0;display:flex;align-items:flex-end;">
                <button type="submit" class="btn btn-primary">+ {{ __('app.add_category') }}</button>
            </div>
        </form>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.name') }}</th>
                    <th class="text-right">{{ __('app.products') }}</th>
                    <th class="text-right">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td class="text-right">{{ $category->products_count }}</td>
                        <td class="text-right">
                            <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('{{ __('app.are_you_sure') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="table-empty">{{ __('app.no_categories_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
