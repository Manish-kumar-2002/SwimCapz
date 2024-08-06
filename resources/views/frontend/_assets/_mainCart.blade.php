@if($cart->product)
<div class="icon-wrapper" style="text-align: right;font-size: 23px;position: relative;">
    <a href="{{ route('product.cart.remove', $cart->id) }}" title="Remove Product">
        <i class="fa fa-trash" style="color:red;"></i>
    </a>
    <a href="/frontend/tool?product-id={{ $cartDetails->product_variant_id }}&isEdit=true&cart={{ $cart->id }}"
        title="Edit Product"><i class="fas fa-pencil-alt" style="color:green;"></i></a>
</div>

<div class="row shopping-cart-wrap">
    <div class="col-md-3">
        <div class="product-image">
            {{-- <div class="product-thumbnail">
                <a
                    href="{{ route('front.product', ["{$cart->product->slug}", $cartDetails->product_variant_id]) }}">
                    <img
                        src="{{ $cartDetails->front_design_url }}"
                        alt="Product"
                    />
                </a>
            </div> --}}
            <div class="product-thumbnail">
                <a
                    href="{{ $cart->product ? route('front.product', ["{$cart->product->slug}", $cartDetails->product_variant_id]) : '#' }}">
                    <img src="{{ $cartDetails->front_design_url }}" alt="Product" />
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="product-content">
            <h3 class="product-name">
                {{-- <a
                    href="{{ route('front.product', ["{$cart->product->slug}", $cartDetails->product_variant_id]) }}">
                    {{ $cart->product->name }}
                </a> --}}
                <a
                    href="{{ $cart->product ? route('front.product', ["{$cart->product->slug}", $cartDetails->product_variant_id]) : '#' }}">
                    {{ $cart->product ? $cart->product->name : 'Product Name Unavailable' }}
                </a>
            </h3>
            <ul class="product-info-list">
                <li class="mt-2">
                    <span class="list-title">Selected Color:</span>
                    <span class="color-dot" style="--dot-color:{{ $cartDetails->productVarients->color_code }}"></span>
                </li>
                <li class="mb-2">
                    <span class="list-title">
                        Available Sizes:
                    </span> {{ $cartDetails->productVarients->size }}
                </li>
                <li class="mt-4 mb-4">
                    <button onclick="decrementValue('{{ $cartDetails->id }}')" class="cart-manage" type="button">
                        <i class="fa fa-minus"></i>
                    </button>
                    <input type="text" name="quantity" value="{{ $cartDetails->total_qty }}"
                        id="number-{{ $cartDetails->id }}" class="cart-input-manage cart-input only-numeric"
                        onchange="cartUpdate('{{ $cartDetails->id }}')" maxlength="3" />
                    <button class="cart-manage" type="button" onclick="incrementValue('{{ $cartDetails->id }}')">
                        <i class="fa fa-plus"></i>
                    </button>
                </li>
                {{-- <li class="mt-2 mb-5">
                    <a href="#">
                        <strong>Add Design to Another Product</strong>
                    </a>
                </li> --}}

            </ul>
        </div>
    </div>
    <div class="col-md-3">
        <div class="right-block">
            <div class="product-subtotal total-price">
                <span class="d-inline-block" id="prc{{ $cart->id }}">
                    SubTotal :
                    {{ Helper::convertPrice(
                        $cartDetails->total_qty * $cartDetails->total_price + $cart->totalNameCharge(false),
                    ) }}
                </span>
            </div>
            <span class="product-price price-per-cap">
                <span>

                </span>
            </span>
        </div>
    </div>

    <!-- Attached names -->
    @if ($cart->names && count($cart->names) > 0)
        @include('frontend._assets._attached_names', ['cart' => $cart])
    @endif

</div>
@endif
