@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Order Invoice') }}
                        <a class="add-btn" href="javascript:history.back();"><i class="fas fa-arrow-left"></i>
                            {{ __('Back') }}</a>
                    </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Orders') }}</a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Invoice') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="order-table-wrap">
            <div class="invoice-wrap">
                <div class="invoice__title">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="invoice__logo text-left">
                                <img src="{{ asset('assets/images/' . $gs->invoice_logo) }}" alt="woo commerce logo">
                            </div>
                        </div>
                        <div class="col-lg-6 text-right">
                            <a class="btn  add-newProduct-btn print" href="{{ route('admin-order-print', $order->id) }}"
                                target="_blank"><i class="fa fa-print"></i> {{ __('Print Invoice') }}</a>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row invoice__metaInfo mb-4">
                    <div class="col-lg-6">
                        <div class="invoice__orderDetails">

                            <p><strong>{{ __('Order Details') }} </strong></p>
                            <span><strong>{{ __('Invoice Number') }} :</strong>
                                {{ sprintf("%'.08d", $order->id) }}</span><br>
                            <span><strong>{{ __('Order Date') }} :</strong>
                                {{ date('d-M-Y', strtotime($order->created_at)) }}</span><br>
                            <span><strong>{{ __('Order ID') }} :</strong> {{ $order->order_number }}</span><br>
                            @if ($order->dp == 0)
                                <span> <strong>{{ __('Shipping Method') }} :</strong>
                                    @if ($order->shipping == 'pickup')
                                        {{ __('Pick Up') }}
                                    @else
                                        {{ __('Ship To Address') }}
                                    @endif
                                </span><br>
                            @endif
                            <span>
                                <strong>{{ __('Payment Method') }} :</strong> {{ $order->method }}
                            </span>

                            @if (!empty($order->cancel_note))
                                <br>
                                <span>
                                    <strong>{{ __('Order Note') }} :</strong> 
                                    <b style="color:red;">
                                        {{ $order->cancel_note  }}
                                    </b>
                                </span>
                                
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row invoice__metaInfo">
                    @if ($order->dp == 0)
                        <div class="col-lg-6">
                            <div class="invoice__shipping">
                                <p><strong>{{ __('Shipping Address') }}</strong></p>
                                <span>
                                    <strong>{{ __('Customer Name') }}</strong>:
                                    {{ $order->shipping_name == null ? $order->customer_name : $order->shipping_name }}
                                </span><br>
                                <span><strong>{{ __('Address') }}</strong>:
                                    {{ $order->shipping_address == null ? $order->customer_address : $order->shipping_address }}
                                </span><br>
                                <span><strong>{{ __('City') }}</strong>:
                                    {{ $order->shipping_city == null ? $order->customer_city : $order->shipping_city }}
                                </span><br>
                                <span>
                                    <strong>{{ __('State') }}</strong>:
                                    {{ @$order->shippingState->state ?? '' }}
                                </span><br>
                                <span><strong>{{ __('Country') }}</strong>:
                                    {{ @$order->shippingCountry->country_name ?? '' }}
                                </span>

                            </div>
                        </div>
                    @endif

                    <div class="col-lg-6">
                        <div class="buyer">
                            <p><strong>{{ __('Billing Details') }}</strong></p>
                            <span><strong>{{ __('Customer Name') }}</strong>:
                                {{ $order->billing_name ?? $order->customer_name }}</span><br>
                            <span><strong>{{ __('Address') }}</strong>:
                                {{ $order->billing_address ?? $order->customer_Address }}</span><br>
                            <span>
                                <strong>{{ __('City') }}</strong>:
                                {{ $order->billing_city ?? $order->customer_city }}
                            </span><br>
                            <span>
                                <strong>{{ __('State') }}</strong>:
                                {{ @$order->billingState->state ?? '' }}
                            </span><br>
                            <span>
                                <strong>{{ __('Country') }}</strong>:
                                {{ @$order->billingCountry->country_name }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="invoice_table">
                            @include('admin.order.partials._orderTable', ['order' => $order])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content Area End -->
    </div>
    </div>
    </div>

@endsection
