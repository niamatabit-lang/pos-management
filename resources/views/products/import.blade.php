@extends('layouts.app')

@section('title', __('app.bulk_import_title'))

@section('content')

<div class="page">

    <x-page-header :title="__('app.bulk_import_title')" :subtitle="__('app.bulk_import_subtitle')">
        <x-slot:actions>
            <x-button tag="a" href="{{ route('products.index') }}" variant="secondary">&larr; {{ __('app.back_to_products') }}</x-button>
        </x-slot:actions>
    </x-page-header>

    @if (session('success'))
        <x-alert variant="success">
            {{ session('success') }}
        </x-alert>
    @endif

    @if (session('error'))
        <x-alert variant="danger">
            {{ session('error') }}
        </x-alert>
    @endif

    @if (session('importErrors') && count(session('importErrors')))
        <x-alert variant="warning">
            <strong>{{ __('app.import_errors_found') }}</strong>
            <ul class="mt-10 ml-20">
                @foreach (session('importErrors') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <x-card width="600">
        <p class="page-subtitle mb-15">
            {{ __('app.csv_columns_note') }}
            <code>name, sku, category, unit, buy_price, sell_price, commission, quantity, reorder_level</code>
        </p>

        <x-button tag="a" href="{{ route('products.import.sample') }}" variant="secondary" class="mb-20">{{ __('app.download_sample_csv') }}</x-button>

        <form method="POST" action="{{ route('products.import.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">{{ __('app.select_csv_file') }} <span class="required">*</span></label>
                <input type="file" name="file" class="form-control" accept=".csv,.txt" required>
                @error('file') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <x-button variant="primary">{{ __('app.import') }}</x-button>
        </form>
    </x-card>

</div>

@endsection
