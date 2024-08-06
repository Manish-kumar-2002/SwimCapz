<!--==================== Cart Section Start ====================-->
<div class="full-row cartpage shopping-cart">
    <div class="container">
        <h2>Shopping Cart</h2>
        <div class="row flex-wrapper justify-content-between">

            @if(count(Helper::cartList()) > 0)

               @include('frontend.ajax.main-cart',['cartList' => Helper::cartList()])

            @else

                <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                    <div class="card border py-4">
                        <div class="card-body">
                            <h4 class="text-center">{{ __('Cart is Empty!! Add some products in your Cart') }}</h4>
                        </div>
                    </div>
                </div>

            @endif

        </div>
    </div>
</div>
<script src="{{ asset('assets/front/js/custom.js') }}"></script>
