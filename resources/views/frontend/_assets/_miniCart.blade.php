@if ($cart->product)
    <li class="mini-cart-item">
        <div class="cart-remove remove" data-class="cremove{{ $cart->id }}"
            data-href="{{ route('product.cart.remove', $cart->id) }}" title="Remove this item">
            <button class="close-button" type="button"></button>
        </div>
        <a href="{{ route('front.product', ["{$cart->product->slug}", $cartDetails->product_variant_id]) }}"
            class="product-image">
            <img src="{{ $cartDetails->front_design_url }}"
                class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="Cart product">
        </a>
        <a href="{{ route('front.product', ["{$cart->product->slug}", $cartDetails->product_variant_id]) }}"
            class="product-name">
            {{ Helper::getSubString($cart->product->name) }}
        </a>

    </li>
@endif
