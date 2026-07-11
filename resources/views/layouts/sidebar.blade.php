<aside class="sidebar">

    <div class="logo">
        <span>POS</span> Management
    </div>

    <nav class="sidebar-nav">

        <ul>

            <x-nav-item :route="route('dashboard')" :active="request()->routeIs('dashboard')" icon="🏠">
                {{ __('app.nav_dashboard') }}
            </x-nav-item>

            @if (auth()->user()->hasPermission('sales'))
                <x-nav-item :route="route('sales.index')" :active="request()->routeIs('sales.*')" icon="💰">
                    {{ __('app.nav_sales') }}
                </x-nav-item>
                <x-nav-item :route="route('returns.index')" :active="request()->routeIs('returns.*')" icon="↩️">
                    {{ __('app.nav_returns') }}
                </x-nav-item>
            @endif

            @if (auth()->user()->hasPermission('stock'))
                <x-nav-item :route="route('stock.index')" :active="request()->routeIs('stock.*') && !request()->routeIs('stock.ledger')" icon="📈">
                    {{ __('app.nav_stock') }}
                </x-nav-item>
                <x-nav-item :route="route('stock.ledger')" :active="request()->routeIs('stock.ledger')" icon="📅">
                    {{ __('app.nav_stock_ledger') }}
                </x-nav-item>
            @endif

            @if (auth()->user()->hasPermission('expenses'))
                <x-nav-item :route="route('expenses.index')" :active="request()->routeIs('expenses.*')" icon="🧾">
                    {{ __('app.nav_expenses') }}
                </x-nav-item>
            @endif

            @if (auth()->user()->isSuperAdmin() || auth()->user()->isShopOwner())
                <x-nav-item :route="route('finance.index')" :active="request()->routeIs('finance.*')" icon="💵">
                    {{ __('app.nav_finance') }}
                </x-nav-item>
                <x-nav-item :route="route('activity-logs.index')" :active="request()->routeIs('activity-logs.*')" icon="📜">
                    {{ __('app.nav_activity_logs') }}
                </x-nav-item>
            @endif

            @if (auth()->user()->hasPermission('reports'))
                <x-nav-item :route="route('reports.index')" :active="request()->routeIs('reports.*')" icon="📊">
                    {{ __('app.nav_reports') }}
                </x-nav-item>
            @endif

            @if (auth()->user()->hasPermission('service_fee'))
                <x-nav-item :route="route('service-fees.index')" :active="request()->routeIs('service-fees.*')" icon="📱">
                    {{ __('app.nav_service_fees') }}
                </x-nav-item>
            @endif

            @if (auth()->user()->hasPermission('products'))
                <x-nav-item :route="route('products.index')" :active="request()->routeIs('products.*')" icon="📦">
                    {{ __('app.nav_products') }}
                </x-nav-item>
            @endif

            @if (auth()->user()->hasPermission('categories'))
                <x-nav-item :route="route('categories.index')" :active="request()->routeIs('categories.*')" icon="🏷️">
                    {{ __('app.nav_categories') }}
                </x-nav-item>
            @endif

            @if (auth()->user()->isSuperAdmin() || auth()->user()->isShopOwner())
                <x-nav-item :route="route('shops.index')" :active="request()->routeIs('shops.*')" icon="🏬">
                    {{ __('app.nav_shops') }}
                </x-nav-item>

                <x-nav-item :route="route('users.index')" :active="request()->routeIs('users.*')" icon="👤">
                    @if (auth()->user()->isSuperAdmin())
                        {{ __('app.nav_users_owner') }}
                    @else
                        {{ __('app.nav_users_staff') }}
                    @endif
                </x-nav-item>
            @endif

        </ul>

    </nav>

</aside>
