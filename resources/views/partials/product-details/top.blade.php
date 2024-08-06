<div class="full-row pb-0 view-product2">
    <div class="container">
        <div class="products-header-left d-flex align-items-center mb-2">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ route('front.index') }}">{{ __('Home') }}</a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <path d="M4.3335 2.5L7.8335 6L4.3335 9.5" stroke="#ACACAC" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </li>
                <li>{{ __('Products') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12"
                        fill="none">
                        <path d="M4.3335 2.5L7.8335 6L4.3335 9.5" stroke="#ACACAC" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </li>
                <li aria-current="page">{{ __('Details') }}
                    @if (@$productt && request()->routeIs('front.product'))
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12"
                            fill="none">
                            <path d="M4.3335 2.5L7.8335 6L4.3335 9.5" stroke="#ACACAC" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                        </svg>
                    @endif
                </li>

                @if (@$productt && request()->routeIs('front.product'))
                    <li aria-current="page">{{ $productt->slug }}</li>
                @endif

            </ul>
            <div class="woocommerce-result-count"></div><br>
        </div>
        <div class="product-panel">
            @include('partials.product-details._topAsset', ['productt' => $productt ])
        </div>
    </div>
</div>