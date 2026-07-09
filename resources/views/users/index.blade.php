@extends('layouts.app')

@section('title', __('app.users'))

@section('content')

<div class="page">

    <div class="page-header">
        <div>
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
        </div>

        <a href="{{ route('users.create') }}" class="btn btn-primary">
            @if (auth()->user()->isSuperAdmin())
                + {{ __('app.new_shop_owner') }}
            @else
                + {{ __('app.new_manager_employee') }}
            @endif
        </a>
    </div>

    @if (session('success'))
        <div style="background:#d1f4df;color:#198754;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-wrapper">
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
                                    <span class="badge badge-warning">{{ __('app.role_manager') }}</span>
                                @else
                                    <span class="badge badge-success">{{ __('app.role_employee') }}</span>
                                @endif
                            </td>
                        @endif
                        <td>{{ $user->shops->pluck('name')->join(', ') ?: '-' }}</td>
                        <td>
                            @if ($user->is_active)
                                <span class="badge badge-success">{{ __('app.active') }}</span>
                            @else
                                <span class="badge badge-danger">{{ __('app.inactive') }}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-secondary btn-sm">{{ __('app.edit') }}</a>
                            @if ($user->is_active)
                                <form method="POST" action="{{ route('users.destroy', $user) }}" style="display:inline;" onsubmit="return confirm('{{ __('app.confirm_deactivate') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">{{ __('app.deactivate') }}</button>
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
    </div>

</div>

@endsection
