<div class="cart-price-wrap">
    <div class="cart-price-block">
        <div class="order-details">
            <h4>Order Details <span class="product-quant">({{ Helper::cartTotalQuantity() }} Items)</span></h4>
            <span class="currency-type"><button class="info-btn"><svg xmlns="http://www.w3.org/2000/svg" width="17"
                        height="16" viewBox="0 0 17 16" fill="none">
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
                    </svg></button>Price in {{ Helper::getDefaultCurrency()->name }}</span>
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
                    <td>{{ Helper::rushCharge(true) }}</td>
                </tr>
            @endif

            @if (Helper::taxCharge() > 0)
                <tr>
                    <th>Tax</th>
                    <td>{{ Helper::taxCharge() }}</td>
                </tr>
            @endif

            @if (Helper::appliedCouponDiscount() > 0)
                <tr>
                    <th>Coupon Discount-</th>
                    <td>{{ Helper::appliedCouponDiscount(true) }}</td>
                </tr>
            @endif
        </table>
        <form action="{{ route('applyCoupon') }}" method="post" class="coupon-apply-form">
            @csrf
            <div class="apply-coupen">
                <input type="text" name="coupon_code" id="apply_code_in" placeholder="Enter coupon code">
                <button id="apply_btn" class="apply-btn" type="submit">Apply</button>
            </div>

            @if (Helper::appliedCoupon())
                <span class="mt-2 applied-coupon">
                    Applied Coupon :- <b style="color:#38b434">{{ Helper::appliedCoupon()->code }}</b>
                    <a href="{{ route('appliedCouponRemove') }}" class="coupon-removed">
                        <span class="applied-remove"><i class="fa fa-trash" style="color:red;"></i></span>
                    </a>
                </span>
            @endif

            <!-- <strong style="color:red;font-weight:bold;">
                <p class="error-coupon_code errors error-validate"></p>
            </strong>
            <strong style="color:green;font-weight:bold;">
                <p class="coupon-info errors"></p>
            </strong> -->
        </form>
        @include('frontend._assets._coupon_section')
    </div>

    <div class="checkout-block final">
        <div class="total-price order-total">
            <tr class="order-total">
                <span class="text">Total</span>
                <span class="woocommerce-Price-amount amount main-total">
                    {{ Helper::totalCharge() }}
                </span>
        </div>
        {{-- <div class="pay-pal">
            <a href="#" class="pay-btn">PAY<i class="paypal-icon"></i></a>
            <p>By placing this order you agree to our <a href="{{ url('terms-condition') }}" class="conditions">terms
                    and conditions</a></p>
        </div> --}}
    </div>
</div>
@include('frontend._assets._coupon_section_js')
