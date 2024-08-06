@extends('layouts.front')

@section('content')
    @include('partials.global.common-header')

    <!-- breadcrumb -->
    <div class="full-row bg-light overlay-dark py-5"
        style="background-image: url({{ $gs->breadcrumb_banner ?
            asset('assets/images/' . $gs->breadcrumb_banner) :
            asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
        <div class="container">
            <div class="row text-left text-white">
                <div class="col-12">
                    <h3 class="mb-2 text-white">{{ __('Purchased Items') }}</h3>
                </div>
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                            <li class="breadcrumb-item"><a href="{{ 'user-dashboard' }}">{{ __('Dashboard') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Purchased Items') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->

    <!--==================== Blog Section Start ====================-->
    <div class="full-row admin-row">
        <div class="container">
            <div class="mb-4 d-xl-none">
                <button class="dashboard-sidebar-btn btn bg-primary rounded">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-xl-4">
                    @include('partials.user.dashboard-sidebar')
                </div>
                <div class="col-xl-8">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="widget border-0 p-40 widget_categories bg-light account-info">
                                <!-- <div class="process-steps-area">
                                    @include('partials.user.order-process')
                                </div> -->
                                <h4 class="widget-title down-line mb-30">{{ __('Purchased Items') }}</h4>
                                <div class="view-order-page">
                                    <h3 class="order-code">{{ __('Order#') }} {{ $order->order_number }}
                                        <span style="color: {{Helper::getColorbyStatus($order->order_custom_status)}}">[{{ $order->order_custom_status }}]</span>
                                    </h3>
                                    <div class="print-order text-right">
                                        <a href="{{ route('user-order-print', $order->id) }}" target="_blank"
                                            class="print-order-btn">
                                            <i class="fa fa-print"></i> {{ __('Print Order') }}
                                        </a>
                                    </div>
                                    <p class="order-date">{{ __('Order Date') }}
                                        {{ date('d-M-Y', strtotime($order->created_at)) }}
                                    </p>

                                    <div class="shipping-add-area mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5>{{ __('Shipping Address') }}</h5>
                                                <address>
                                                   <p> {{ __('Name:') }}
                                                    <span>   {{ $order->shipping_name == null ?
                                                        $order->customer_name : $order->shipping_name }}</span>
                                                   </p>
                                                 <p> {{ __('Email:') }}
                                                    <span>   {{ $order->shipping_email == null ?
                                                        $order->customer_email : $order->shipping_email }}</span>
                                                 </p>
                                                 <p> {{ __('Phone:') }}
                                                    <span>
                                                    {{ $order->shipping_phone == null ?
                                                        $order->customer_phone : $order->shipping_phone }}
                                                    </span>
                                                 </p>
                                                 <p>     {{ __('Address:') }}
                                                    <span>
                                                    {{ $order->shipping_address == null ?
                                                         $order->customer_address : $order->shipping_address }}
                                                    {{ $order->shipping_city == null ?
                                                        $order->customer_city : $order->shipping_city }}-
                                                    {{ $order->shipping_zip == null ?
                                                        $order->customer_zip : $order->shipping_zip }}
                                                    </span>
                                                 </p>
                                                </address>
                                            </div>
                                            <div class="col-md-6">
                                                <p>{{ __('Payment Status') }} :
                                                    <span>{{ __($order->payment_custom_status) }}</span>
                                                </p>
                                                <p>{{ __('Order Status') }} :
                                                    <span>{{ __($order->order_custom_status) }}</span>
                                                </p>
                                                <p>
                                                    {{ __('Paid Amount:') }}
                                                   <span> {{ Helper::convertPrice($order->pay_amount)}}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="billing-add-area">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5>{{ __('Billing Address') }}</h5>
                                                <address>
                                                    <p> {{ __('Name:') }}
                                                        <span> {{ $order->billing_name }}</span>
                                                    </p>
                                                   <p>{{ __('Email:') }}
                                                    <span>  {{ $order->billing_email }}</span>
                                                   </p>
                                                   <p> {{ __('Phone:') }}
                                                    <span> {{ $order->billing_phone }}</span>
                                                   </p>
                                                   <p> {{ __('Address:') }}
                                                    <span>  {{ $order->billing_address }}</span>
                                                   </p>
                                                   <p>   {{ $order->billing_city }}
                                                    <span> {{ $order->billing_zip }}</span>
                                                   </p>
                                                </address>
                                            </div>
                                            <div class="col-md-6 ">
                                                <p>{{ __('Payment Method:') }} <span style=" text-transform: uppercase; ">{{ $order->method }}</span></p>
                                                
                                                @if ($order->method == Helper::PAYMENT_METHOD_PO)
                                                    <p>{{ __('PO Number:') }} <span style=" text-transform: uppercase; ">{{ $order->method }}</span></p>
                                                    @if ($order->attachement)
                                                        <p>
                                                            {{ __('Attachement') }} :
                                                            <span><a
                                                                href="{{ $order->attachement  }}"
                                                                download
                                                            >Download Attachement</a></span></p>
                                                    @endif
                                                @endif
                                                

                                                @if ($order->method == "stripe" && $order->stripe_id)
                                                    <p>Transaction Id : <span>{{$order->stripe_id}}</span></p>
                                                @endif

                                                @if (!empty($order->cancel_note))
                                                    <br>
                                                    <span>
                                                        <strong>{{ __('Cancel Note') }} :</strong> 
                                                        <b style="color:red;">
                                                            {{ $order->cancel_note  }}
                                                        </b>
                                                    </span>
                                                    
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="">
                                        @include('admin.order.partials._orderTable', ['order' => $order])
                                    </div>
                                </div>
                                <a class="back-btn theme-bg" href="{{ route('user-orders') }}"> {{ __('Back') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--==================== Blog Section End ====================-->
    {{-- Modal --}}
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __('License Key') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center">{{ __('The Licenes Key is :') }} <span id="key"></span></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    @includeIf('partials.global.common-footer')

@endsection
@section('script')
    <script type="text/javascript">
        (function($) {
            "use strict";

            $('#example').dataTable({
                "ordering": false,
                'paging': false,
                'lengthChange': false,
                'searching': false,
                'ordering': false,
                'info': false,
                'autoWidth': false,
                'responsive': true
            });

        })(jQuery);
    </script>
@endsection
