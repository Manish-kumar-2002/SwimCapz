<ul class="d-flex align-items-center justify-content-end basket-wrap">
    <li class="country_select">
        <div class="currency-selector nice-select">
            <?php
            $icon = @Helper::getDefaultCurrency(Session::get('currency'))->icon ?? null;
            ?>
            <select name="currency" class="currency selectors nice select2-js-init select2-hidden-accessible"
                data-select2-id="select2-data-4-9nqo" tabindex="-1" aria-hidden="true">
                @foreach (Helper::getCurrencies() as $currency)
                    <option value="{{ route('front.currency', $currency->id) }}"
                        {{ Session::has('currency') ? (Session::get('currency') == $currency->id ? 'selected' : ''): (@Helper::getDefaultCurrency()->id == $currency->id ? 'selected' : '') }}
                        data-img="{{ asset('assets/images/currencies/' . $currency->icon) }}">
                        {{ $currency->name }}
                    </option>
                @endforeach

            </select>
        </div>
    </li>
    @if (Auth::check())
        <li class="sign-in position-relative font-general my-account-dropdown">
            <a href="my-account.html" class="has-dropdown d-flex align-items-center text-dark text-decoration-none"
                title="My Account">
                MY ACCOUNT
            </a>
            <ul class="my-account-popup">
                <li>
                    <a href="{{ route('user-dashboard') }}"><span class="menu-item-text">{{ 'My Profile' }}</span></a>
                </li>
                <li>
                    <a href="{{ route('user-orders') }}">
                        <span class="menu-item-text">{{ __('My Orders') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user-logout') }}"><span class="menu-item-text">{{ __('Logout') }}</span></a>
                </li>
            </ul>
        </li>
    @else
        <li class="sign-in position-relative font-general my-account-dropdown">
            <a href="{{ url('user/login') }}" class="d-flex align-items-center text-dark text-decoration-none"
                title="Sign In">
                SIGN IN
            </a>
        </li>
    @endif
    <li class="header-cart-1 _commonHeaderCart">
        @include('frontend._assets._commonHeaderCart')
    </li>
</ul>
