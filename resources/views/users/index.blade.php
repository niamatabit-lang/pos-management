@extends('layouts.app')

@section('title', __('app.users'))

@section('content')

<div class="page">

    <x-page-header>
        <x-slot:heading>
            <h1 class="page-title">
                @if (auth()->user()->isSuperAdmin())
                    {{ __('app.shop_owner_list') }}
                @else
                    {{ __('app.manager_employee_list') }}
                @endif
            </h1>
            <p class="page-subtitle">
                @if (auth()->user()->isSuperAdmin())
                    {{ __('app.all_shop_owners_note') }}
                @else
                    {{ __('app.your_created_ids_note') }}
                @endif
            </p>
        </x-slot:heading>

        <x-slot:actions>
            <x-button tag="a" href="{{ route('users.create') }}" variant="primary">
                @if (auth()->user()->isSuperAdmin())
                    + {{ __('app.new_shop_owner') }}
                @else
                    + {{ __('app.new_manager_employee') }}
                @endif
            </x-button>
        </x-slot:actions>
    </x-page-header>

    @if (session('success'))
        <x-alert variant="success">{{ session('success') }}</x-alert>
    @endif

    <x-table-wrapper>
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.name') }}</th>
                    <th>{{ __('app.email') }}</th>
                    @if (!auth()->user()->isSuperAdmin())
                        <th>{{ __('app.role') }}</th>
                    @endif
                    <th>{{ __('app.shop') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th class="text-right">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        @if (!auth()->user()->isSuperAdmin())
                            <td>
                                @if ($user->isManager())
                                    <x-badge variant="warning">{{ __('app.role_manager') }}</x-badge>
                                @else
                                    <x-badge variant="success">{{ __('app.role_employee') }}</x-badge>
                                @endif
                            </td>
                        @endif
                        <td>{{ $user->shops->pluck('name')->join(', ') ?: '-' }}</td>
                        <td>
                            @if ($user->is_active)
                                <x-badge variant="success">{{ __('app.active') }}</x-badge>
                            @else
                                <x-badge variant="danger">{{ __('app.inactive') }}</x-badge>
                            @endif
                        </td>
                        <td class="text-right">
                            <x-button tag="a" href="{{ route('users.edit', $user) }}" variant="secondary" size="sm">{{ __('app.edit') }}</x-button>
                            @if ($user->is_active)
                                <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('{{ __('app.confirm_deactivate') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" size="sm">{{ __('app.deactivate') }}</x-button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isSuperAdmin() ? 5 : 6 }}" class="table-empty">{{ __('app.no_ids_created_yet') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-table-wrapper>

</div>

@endsection
