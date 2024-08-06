@extends('layouts.front')
@section('content')
    @include('partials.global.common-header')
    <!-- breadcrumb -->
    <div class="load_cart">
        @include('frontend.ajax.cart-page')
    </div>
    @include('partials.global.common-footer')
@endsection

@section('css')
    <style>
        .cart-manage {
            border: 1px solid !important;
            padding: 4px 20px;
        }

        .cart-input-manage {
            padding: 1px 32px;
            border: 1px solid !important;
            margin: 0 4px;
            width: 100px;
        }
    </style>
@endsection

@section('script')
    <script>
        function incrementValue(cart_details_id) {
            let inputId = 'number-' + cart_details_id;
            let value = parseInt(document.getElementById(inputId).value);
            value = isNaN(value) ? 0 : value;
            document.getElementById(inputId).value = value + 1;

            cartUpdate(cart_details_id);
        }

        function decrementValue(cart_details_id) {
            let inputId = 'number-' + cart_details_id;
            let value = parseInt(document.getElementById(inputId).value);
            value = isNaN(value) ? 0 : value;
            if (value > 1) {
                document.getElementById(inputId).value = value - 1;
                cartUpdate(cart_details_id);
            }
        }

        function cartUpdate(cart_details_id, panelClass = "cart-panel-") {
            let inputQtyId = 'number-' + cart_details_id;
            let qty = document.getElementById(inputQtyId).value;

            let inputRemarksId = 'remarks-' + cart_details_id;
            let remarks = ''; //document.getElementById(inputRemarksId).value

            panelClass = panelClass + cart_details_id;
            // $('#loaderStartEnd').show();
            $.get('{{ route('cartDetailsUpdate') }}', {
                cart_details_id: cart_details_id,
                qty: qty,
                remarks: remarks
            }, function(response) {

                if (response.status == "400") {
                    toastr.error(response.message);
                    console.log('========>', response);
                    refreshCartOrderDetails(cart_details_id, panelClass);
                    // $('#loaderStartEnd').hide();
                } else {
                    console.log(response);
                    $('.' + panelClass).html(response);
                    /* refresh main cart details */
                    refreshMainCartOrderDetails();
                    // $('#loaderStartEnd').hide();

                }

            });

        }

        function incrementValue(cart_details_id, that) {
            // $(that).prop('disabled', 'true');
            let inputId = 'number-' + cart_details_id;
            var value = parseInt(document.getElementById(inputId).value);
            value = isNaN(value) ? 0 : value;
            document.getElementById(inputId).value = value + 1;
            cartUpdate(cart_details_id);
            // $(that).prop('disabled', 'false');
        }
        
        function decrementValue(cart_details_id, that) {
            // $(that).css('display', 'none');
			let inputId = 'number-' + cart_details_id;
            var value = parseInt(document.getElementById(inputId).value);
            value = isNaN(value) ? 0 : value;
            if (value > 1) {
                document.getElementById(inputId).value = value - 1;
                cartUpdate(cart_details_id);
            }else{
                // $(that).css('display', 'block');
            }
        }

        function refreshMainCartOrderDetails() {
            $.get('{{ route('cartDetailsUpdate') }}?mainCartOrderDetails=true', function(response) {
                $('.main-cart-order-details').html(response);
            });
        }

        function refreshCartOrderDetails(cart_detail_id, panelClass) {
            let url = '{{ route('cartDetailsUpdate') }}?mainCartOrderDetails=true&cart_details_id=' + cart_detail_id;
            $.get(url, function(response) {
                $('.' + panelClass).html(response);
            });
        }


        $(document).ready(function() {
            $('.cart-input').keypress(function(e) {
                var charCode = (e.which) ? e.which : event.keyCode
                if (String.fromCharCode(charCode).match(/[^0-9]/g))
                    return false;

            });
        });
    </script>
    @include('frontend._assets._couponApply')
@endsection
