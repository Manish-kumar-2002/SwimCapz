<div class="cart-price-wrap">
    <div class="cart-price-block">
        <div class="order-details">
            <h4>Order Details <span class="product-quant">({{ Helper::cartTotalQuantity() }} Items)</span></h4>
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

            <strong style="color:red;font-weight:bold;">
                <p class="error-coupon_code errors error-validate"></p>
            </strong>
            <strong style="color:green;font-weight:bold;">
                <p class="coupon-info errors"></p>
            </strong>
        </form>
        @include('frontend._assets._coupon_section')
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
                class="checkout-button btn red">{{ __('Proceed to purchase') }}<svg xmlns="http://www.w3.org/2000/svg"
                    width="21" height="21" viewBox="0 0 21 21" fill="none">
                    <path d="M4.375 10.5H16.625" stroke="white" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                    <path d="M10.5 4.375L16.625 10.5L10.5 16.625" stroke="white" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@include('frontend._assets._coupon_section_js')
