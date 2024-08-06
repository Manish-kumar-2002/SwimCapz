<div class="col">
    <div class="row custom-printed pt-5 align-items-center">
        <div class="col-sm-12 col-md-5">
            <div class="printed-content-wrap">
                <div class="img-wrap">
                    <img src="{{url('/')}}/assets/front/custom-images/paper.png" alt="file">
                </div>
                <div class="content-text">
                    <h3>
                        <a href="{{ route('front.download-cart-name-list', $cart->id) }}" download
                    >Custom Printed Names</a></h3>
                    <ul class="product-info-list">
                        <li>
                            <span class="list-title">
                                Quantity:
                            </span>{{ array_sum(array_column($cart->names, 'quantity')) }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            <a
                href="{{ route('front.cart-name-remove', $cart->id) }}"
                onclick="return confirm('Are you sure ?.')"
                class="cart-name-remove"
            >
                <div class="remove-content">
                    <i class="clossed-icon"></i>
                    <span>Remove</span>
                </div>
            </a>
        </div>
        {{-- <div class="col-sm-12 col-md-3">
            <div class="right-block">
                <div class="product-subtotal total-price">
                    <span class="d-inline-block">
                        {{ $cart->totalNameCharge() }}
                    </span>
                </div>
                <span class="product-price price-per-cap">
                    <span>({{ $cart->nameChargeEach() }}/name)</span>
                </span>
            </div>
        </div> --}}
    </div>
</div>
