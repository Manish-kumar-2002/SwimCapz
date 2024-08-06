<ul class="cart_list product_list_widget ">

    @if (count(Helper::cartList()) > 0)

        @foreach (Helper::cartList() as $cart)
            @foreach ($cart->details as $row)
                @include('frontend._assets._miniCart', [
                    'cart' => $cart,
                    'cartDetails' => $row,
                ])
            @endforeach
        @endforeach
    @else
        <div class="card">
            <div class="card-body">
                <h4 class="text-center">{{ __('Cart is Empty!! Add some products in your Cart') }}</h4>
            </div>
        </div>
    @endif

</ul>
<div class="total-cart">
    <div class="title">&nbsp; </div>
    <div class="price">

    </div>
</div>
<div class="btn-wrap">
    <a href="{{ route('front.cart') }}" class="btn btn-primary  view-cart">{{ __('View cart') }}</a>

    <a href="{{ route('front.checkout') }}" class="btn btn-secondary checkout">{{ __('Check out') }}</a>
</div>
