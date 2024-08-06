<div class="col-lg-7 col-md-12 col-12">
    <div class="cart-table">
        <div class="gocover"
            style="position: absolute; background: url({{ asset('assets/images/xloading.gif') }})
                            center center no-repeat scroll rgba(255, 255, 255, 0.5); display: none;">
        </div>
        @foreach ($cartList as $key => $carts)
            @if ($carts->product)
                <h3 class="mt-2 mb-3">{{ $carts->design_name }}</h3>
                @foreach ($carts->details as $row)
                    <div class="blank-cart-wrap cart-panel-{{ $row->id }}">
                        @include('frontend._assets._mainCart', [
                            'cart' => $carts,
                            'cartDetails' => $row,
                        ])
                    </div>
                @endforeach
                <br><br>
            @endif
        @endforeach

    </div>

</div>
<div class="col-lg-4 col-md-12 col-12 main-cart-order-details">
    @include('frontend._assets._main_cart_order_details')
</div>
