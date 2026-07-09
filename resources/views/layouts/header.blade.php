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

        <form method="POST" action="{{ route('locale.switch', app()->getLocale() === 'bn' ? 'en' : 'bn') }}" style="display:flex;">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm" title="{{ __('app.switch_language') }}">
                @if (app()->getLocale() === 'bn')
                    English
                @else
                    বাংলা
                @endif
            </button>
        </form>

        <form method="POST" action="{{ route('shops.switch') }}" class="shop-switcher" style="display:flex;align-items:center;gap:8px;background:#eafaf1;border:1px solid #b7e4c7;padding:6px 12px;border-radius:8px;">
            @csrf
            <span style="font-size:12px;font-weight:700;color:#198754;white-space:nowrap;">{{ __('app.current_shop') }}</span>
            <select name="shop_id" onchange="this.form.submit()" class="form-select" style="min-width:160px;">
                @foreach ($allShops as $shop)
                    <option value="{{ $shop->id }}" @selected($currentShop->id === $shop->id)>
                        🏬 {{ $shop->name }}
                    </option>
                @endforeach
            </select>
        </form>

        <div class="user">

            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=198754&color=fff"
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

        <a href="{{ route('password.change') }}" class="btn btn-secondary btn-sm">{{ __('app.change_password') }}</a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm">{{ __('app.logout') }}</button>
        </form>

    </div>

</header>
