@extends('layouts.front')
@section('css')
    <style>
        .select2-container{ width: 100% !important; }
       .input-radio{
            -moz-appearance: none;
            appearance: auto;
            border-radius: 0;
            height: 15px;
            width: 15px;
       }

        .shipping.table>:not(:first-child) {
            border-top: none;
        }

        div.accordion-content a {
            color: #0d6efd !important;
            text-decoration: underline !important;
        }
    </style>
@endsection

@section('content')
    @include('partials.global.common-header')
    <!-- breadcrumb -->
    <section class="shipping-wrapper">
        <div class="container">
            <div class="left-block">
                <div class="accordian-listing">
            
                    <div class="accordian-list active allow-open"><h3 class="list-title">Contact Information</h3>
                        <div class="accordion-content">

                            <form
                                id="form1"
                                action="{{route('createTempOrder')}}"
                                method="post"
                                class="form"
                                data-next="second">
                                @csrf
                                <input type="hidden" name="formNo" value="1">
                                <div class="double-wrap">
                                    <div class="input-wrap">
                                        <label for="fname">First Name*</label>
                                        <input
                                            name="name"
                                            type="text"
                                            id="fname"
                                            placeholder="Enter your name"
                                            value="{{Auth::check() ? Auth::user()->name : ''}}"
                                        >
                                        <strong style="color:red;font-weight:bold;">
                                            <p class="error-name errors"></p>
                                        </strong>
                                    </div>
                                    <div class="input-wrap">
                                        <label for="fname">Last Name*</label>
                                        <input
                                            name="lastname"
                                            type="text"
                                            id="lname"
                                            placeholder="Enter your last name"
                                            value="{{Auth::check() ? Auth::user()->name : ''}}"
                                        >
                                        <strong style="color:red;font-weight:bold;">
                                            <p class="error-lastname errors"></p>
                                        </strong>
                                    </div>
                                </div>
                                <div class="input-wrap">
                                        <label for="lname">Email Address*</label>
                                        <input
                                            name="email"
                                            type="email"
                                            id="email"
                                            placeholder="Enter your email"
                                            value="{{Auth::check() ? Auth::user()->email : ''}}"
                                        >

                                        <strong style="color:red;font-weight:bold;">
                                            <p class="error-email errors"></p>
                                        </strong>
                                    </div>
                                <!-- <span class="confirmation-msg">(Your order confirmation will be emailed to this address)</span> -->
                                <div class="btn-wrap">
                                    <!-- <p>Already have an account? <a href="#" class="login">Log In</a></p> -->
                                    <button type="submit" class="btn red">Next</button>
                                </div>

                            </form>
                    
                        </div>
                    </div>

                    <div class="accordian-list second"><h3 class="list-title">Address</h3>
                        <div class="accordion-content">
                            <form action="{{route('createTempOrder')}}"
                                method="post" class="form" data-type="shipping" data-next="third" id="form2">
                                @csrf
                                <input
                                    aria-hidden="true"
                                    type="hidden" name="formNo" value="2">
                                
                                <div class="shipping-panel">
                                    @include('frontend._assets._shipping_address',[
                                        'selected_id' => null
                                    ])
                                </div>
                                <div
                                    class="billing-address"
                                    style="display:{{ count(Helper::getAddresses()) > 0 ? 'block;' : 'none;' }}">
                                    <span class="billing">Billing Address</span>
                                    <div class="checkbox-wrap">
                                        <input type="checkbox" id="billing_check" name="same_as_shipping" checked>
                                        <button>Same as shipping address</button>
                                    </div>
                                    <div class="billingAddress billing-panel" style="display:none;">
                                        @include('frontend._assets._billing_address', [
                                            'selected_id' => null
                                        ])
                                    </div>
                                </div>

                                <div class="btn-wrap btn-wrapper">
                                    <button class="btn red" type="submit">Next</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="accordian-list third">
                        <h3 class="list-title">
                            Shipping <span class="order-detailed">(Select how you would like your order delivered.)</span></h3>
                        <div class="accordion-content">
                            <form
                                action="{{route('createTempOrder')}}"
                                method="post" class="form" data-next="four" id="form3">
                                @csrf

                                <input type="hidden" name="formNo" value="3">
                                <div class="shipping-charge-wrap">
                                    <div class="left-wrap">
                                        <input type="radio" name="shipping_charge" id="shipping" value="RUSH">
                                      <label for="shipping" class="shipping-charge-info">
                                            <span class="shipping-charge">RUSH SHIPPING - RUSH - 10%</span>
                                            <span class="shipping-date">Estimated Delivery -
                                                <span
                                                    class="delivery-date"
                                                >{{Helper::normalDeliveryEstimation()}}</span>
                                            </span>
                                        </label>
                                    </div>
                                    <span class="shipping-type">Free</span>
                                </div>
                                <div class="shipping-charge-wrap">
                                    <div class="left-wrap">
                                        <input type="radio" name="shipping_charge" id="free" value="FREE">
                                        <label for="free" class="shipping-charge-info">
                                            <span class="shipping-charge">FREE SHIPPING - FREE</span>
                                        </label>
                                    </div>
                                    <span class="shipping-type">Free</span>
                                </div>
                                <strong style="color:red;font-weight:bold;">
                                    <p class="error-shipping_charge errors"></p>
                                </strong>
                                <div class="btn-wrap">
                                    <button class="btn red">Next</button>
                                </div>
                            </form> 
                        </div>
                    </div>

                    <div class="accordian-list four payment-block">
                        <h3 class="list-title payment">Payment</h3>
                        <div class="accordion-content">
                        <p>Select how you would like to pay for your order.</p>
                            @include('frontend._assets._paymentPage')
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-block">
                <div class="right-panel"><!-- right block --></div>
            </div>
        </div>
    </section>
    @includeIf('partials.global.common-footer')
@endsection
@section('script')
    {{-- js file include --}}
        @include('frontend._assets._checkout')
@endsection
