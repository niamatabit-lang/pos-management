@extends('layouts.app')

@section('title', __('app.categories'))

@section('content')

<div class="page">

    <x-page-header :title="__('app.categories')" :subtitle="__('app.categories_subtitle')" />

    @if (session('success'))
        <x-alert variant="success">
            {{ session('success') }}
        </x-alert>
    @endif

    <x-card class="mb-20">
        <form method="POST" action="{{ route('categories.store') }}" class="form-row">
            @csrf
            <div class="form-group form-group-flush">
                <label class="form-label">{{ __('app.category_name') }} <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="{{ __('app.category_name_eg') }}">
                @error('name')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group form-group-flush align-end d-flex">
                <x-button variant="primary">+ {{ __('app.add_category') }}</x-button>
            </div>
        </form>
    </x-card>

    <x-table-wrapper>
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
                                <x-button variant="danger" size="sm">{{ __('app.delete') }}</x-button>
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
    </x-table-wrapper>

</div>

@endsection
