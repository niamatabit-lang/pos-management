@extends('layouts.app')

@section('title', __('app.nav_activity_logs'))

@section('content')

<div class="page">

    <x-page-header :title="__('app.nav_activity_logs')" :subtitle="__('app.activity_logs_subtitle')" />

    <x-table-wrapper>
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.time') }}</th>
                    <th>{{ __('app.user') }}</th>
                    <th>{{ __('app.action') }}</th>
                    <th>{{ __('app.description') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                        <td>{{ $log->user->name ?? __('app.unknown') }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->description }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="table-empty">{{ __('app.no_activity_logs') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <x-slot:footer>
            <div>
                {{ __('app.showing_results', ['from' => $logs->firstItem() ?? 0, 'to' => $logs->lastItem() ?? 0, 'total' => $logs->total()]) }}
            </div>

            {{ $logs->links('vendor.pagination.custom') }}
        </x-slot:footer>
    </x-table-wrapper>

</div>

@endsection
