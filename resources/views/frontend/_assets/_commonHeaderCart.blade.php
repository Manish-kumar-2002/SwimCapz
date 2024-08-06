<a href="{{ route('front.cart') }}" class="cart has-cart-data" title="View Cart">
    <div class="cart-icon">
        <i class="icon-cart"></i>
        <span class="header-cart-count" id="cart-count">
            {{-- {{ Helper::cartList() ? count(Helper::cartList()) : 0 }} --}}
            {{ Helper::cartTotalQuantity() ? Helper::cartTotalQuantity() : 0 }}
        </span>
    </div>
    <div class="cart-wrap">
        <div class="cart-text">Cart</div>
        <span class="header-cart-count">
            {{-- {{ Helper::cartList() ? count(Helper::cartList()) : 0 }} --}}
            {{ Helper::cartTotalQuantity() ? Helper::cartTotalQuantity() : 0 }}
        </span>
    </div>
</a>
<div class="cart-popup">
    @include('load.cart')
</div>
