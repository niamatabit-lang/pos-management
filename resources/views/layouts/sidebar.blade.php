<aside class="sidebar">

    <div class="logo">
        <span>POS</span> Management
    </div>

    <nav class="sidebar-nav">

        <ul>

            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i>🏠</i>
                    <span>{{ __('app.nav_dashboard') }}</span>
                </a>
            </li>

            @if (auth()->user()->hasPermission('sales'))
                <li>
                    <a href="{{ route('sales.index') }}" class="{{ request()->routeIs('sales.*') ? 'active' : '' }}">
                        <i>💰</i>
                        <span>{{ __('app.nav_sales') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('returns.index') }}" class="{{ request()->routeIs('returns.*') ? 'active' : '' }}">
                        <i>↩️</i>
                        <span>{{ __('app.nav_returns') }}</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasPermission('stock'))
                <li>
                    <a href="{{ route('stock.index') }}" class="{{ request()->routeIs('stock.*') && !request()->routeIs('stock.ledger') ? 'active' : '' }}">
                        <i>📈</i>
                        <span>{{ __('app.nav_stock') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('stock.ledger') }}" class="{{ request()->routeIs('stock.ledger') ? 'active' : '' }}">
                        <i>📅</i>
                        <span>{{ __('app.nav_stock_ledger') }}</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasPermission('expenses'))
                <li>
                    <a href="{{ route('expenses.index') }}" class="{{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                        <i>🧾</i>
                        <span>{{ __('app.nav_expenses') }}</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->isSuperAdmin() || auth()->user()->isShopOwner())
                <li>
                    <a href="{{ route('finance.index') }}" class="{{ request()->routeIs('finance.*') ? 'active' : '' }}">
                        <i>💵</i>
                        <span>{{ __('app.nav_finance') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('activity-logs.index') }}" class="{{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                        <i>📜</i>
                        <span>{{ __('app.nav_activity_logs') }}</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasPermission('reports'))
                <li>
                    <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i>📊</i>
                        <span>{{ __('app.nav_reports') }}</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasPermission('service_fee'))
                <li>
                    <a href="{{ route('service-fees.index') }}" class="{{ request()->routeIs('service-fees.*') ? 'active' : '' }}">
                        <i>📱</i>
                        <span>{{ __('app.nav_service_fees') }}</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasPermission('products'))
                <li>
                    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i>📦</i>
                        <span>{{ __('app.nav_products') }}</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasPermission('categories'))
                <li>
                    <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i>🏷️</i>
                        <span>{{ __('app.nav_categories') }}</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->isSuperAdmin() || auth()->user()->isShopOwner())
                <li>
                    <a href="{{ route('shops.index') }}" class="{{ request()->routeIs('shops.*') ? 'active' : '' }}">
                        <i>🏬</i>
                        <span>{{ __('app.nav_shops') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i>👤</i>
                        <span>
                            @if (auth()->user()->isSuperAdmin())
                                {{ __('app.nav_users_owner') }}
                            @else
                                {{ __('app.nav_users_staff') }}
                            @endif
                        </span>
                    </a>
                </li>
            @endif

        </ul>

    </nav>

</aside>
