<div class="col-xl-8 col-lg-12 col-md-12 col-12">
    <div class="cart-table">
        <div class="gocover"
            style="position: absolute; background: url({{ asset('assets/images/xloading.gif') }})
                            center center no-repeat scroll rgba(255, 255, 255, 0.5); display: none;">
        </div>
        @foreach ($cartList as $key => $carts)
            <h3>{{$carts->design_name}}</h3>
            @foreach ($carts->details as $row)
                
                @include('frontend._assets._mainCart',[
                    'cart'          =>$carts,
                    'cartDetails'   =>$row
                ])
                
            @endforeach
        @endforeach

    </div>

</div>
<div class="col-xl-4 col-lg-12 col-md-12 col-12">
    <div class="cart-price-wrap">
        <div class="cart-price-block">
            <div class="order-details">
                <h4>Order Details <span class="product-quant">({{ count($cartList) }} Items)</span></h4>
                <span class="currency-type"><button class="info-btn"><svg xmlns="http://www.w3.org/2000/svg"
                            width="17" height="16" viewBox="0 0 17 16" fill="none">
                            <g clip-path="url(#clip0_928_1443)">
                                <path
                                    d="M8.9987 14.6667C12.6806 14.6667 15.6654 11.6819 15.6654 8.00001C15.6654 4.31811 12.6806 1.33334 8.9987 1.33334C5.3168 1.33334 2.33203 4.31811 2.33203 8.00001C2.33203 11.6819 5.3168 14.6667 8.9987 14.6667Z"
                                    stroke="#707070" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                                <path d="M8.99805 10.6667V8" stroke="#707070" stroke-width="1.2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M8.99805 5.33334H9.00805" stroke="#707070" stroke-width="1.2"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                            <defs>
                                <clipPath id="clip0_928_1443">
                                    <rect width="16" height="16" fill="white" transform="translate(0.998047)">
                                    </rect>
                                </clipPath>
                            </defs>
                        </svg></button>Price in us {{ Helper::getDefaultCurrency()->name }}</span>
            </div>
            <table class="checkout-price">
                <tr>
                    <th>Cart Total</th>
                    <td>
                        <span class="cart-total">
                            {{ Helper::cartTotal() }}
                        </span>
                    </td>
                </tr>
                @if (Helper::deliveryCharge() > 0)
                    <tr>
                        <th>Delivery Fee</th>
                        <td>{{ Helper::deliveryCharge() }}</td>
                    </tr>
                @endif

                @if (Helper::rushCharge() > 0)
                    <tr>
                        <th>Rush Markup</th>
                        <td>{{ Helper::rushCharge() }}</td>
                    </tr>
                @endif

                @if (Helper::taxCharge() > 0)
                    <tr>
                        <th>Tax</th>
                        <td>{{ Helper::taxCharge() }}</td>
                    </tr>
                @endif

            </table>
        </div>
        <div class="checkout-block">
            <div class="total-price order-total">
                <tr class="order-total">
                    <span class="text">Total</span>
                    <span class="woocommerce-Price-amount amount main-total">
                        {{ Helper::totalCharge() }}
                    </span>
            </div>
            <div class="wc-proceed-to-checkout">
                <a href="{{ route('front.checkout') }}"
                    class="checkout-button btn red">{{ __('Proceed to purchase') }}<svg
                        xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21"
                        fill="none">
                        <path d="M4.375 10.5H16.625" stroke="white" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path d="M10.5 4.375L16.625 10.5L10.5 16.625" stroke="white" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
