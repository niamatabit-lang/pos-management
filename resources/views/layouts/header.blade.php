<header class="header">

    <div class="header-left">

        <button id="menuToggle" class="menu-toggle">

            <svg xmlns="http://www.w3.org/2000/svg"
                 width="22"
                 height="22"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M4 6h16M4 12h16M4 18h16"/>

            </svg>

        </button>

        <div class="header-search">

            <input
                type="text"
                placeholder="{{ __('app.search_placeholder') }}">

        </div>

    </div>

    <div class="header-right">

        <form method="POST" action="{{ route('locale.switch', app()->getLocale() === 'bn' ? 'en' : 'bn') }}" class="lang-switch-form">
            @csrf
            <x-button variant="secondary" size="sm" title="{{ __('app.switch_language') }}">
                @if (app()->getLocale() === 'bn')
                    English
                @else
                    বাংলা
                @endif
            </x-button>
        </form>

        <form method="POST" action="{{ route('shops.switch') }}" class="shop-switcher">
            @csrf
            <span class="shop-switcher-label">{{ __('app.current_shop') }}</span>
            <select name="shop_id" onchange="this.form.submit()" class="form-select">
                @foreach ($allShops as $shop)
                    <option value="{{ $shop->id }}" @selected($currentShop->id === $shop->id)>
                        🏬 {{ $shop->name }}
                    </option>
                @endforeach
            </select>
        </form>

        <div class="user">

            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background={{ ltrim(config('ui.colors.primary'), '#') }}&color=fff"
                 alt="User">

            <div class="user-info">

                <div class="user-name">
                    {{ auth()->user()->name }}
                </div>

                <div class="user-role">
                    @php
                        $roleLabels = [
                            'super_admin' => __('app.role_super_admin'),
                            'shop_owner' => __('app.role_shop_owner'),
                            'manager' => __('app.role_manager'),
                            'employee' => __('app.role_employee'),
                        ];
                    @endphp
                    {{ $roleLabels[auth()->user()->role] ?? auth()->user()->role }}
                </div>

            </div>

        </div>

        <x-button tag="a" href="{{ route('password.change') }}" variant="secondary" size="sm">{{ __('app.change_password') }}</x-button>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-button variant="secondary" size="sm">{{ __('app.logout') }}</x-button>
        </form>

    </div>

</header>
