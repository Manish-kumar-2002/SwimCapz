@extends('layouts.front')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/front/css/datatables.css') }}">
@endsection
@section('content')
    @include('partials.global.common-header')
    <!-- breadcrumb -->
    <div class="full-row bg-light overlay-dark py-5"
        style="background-image:
         url({{ $gs->breadcrumb_banner
             ? asset('assets/images/' . $gs->breadcrumb_banner)
             : asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
        <div class="container">
            <div class="row text-left text-white">
                <div class="col-12">
                    <h3 class="mb-2 text-white">{{ __('Dashboard') }}</h3>
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
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: large;">
                            <strong>{{ __('Success:') }}</strong> {{ Session::get('success') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="widget border-0 p-30 widget_categories bg-light account-info">
                                <h4 class="widget-title down-line mb-30">{{ __('Account Information') }}</h4>
                                <div class="user-info">
                                    <p><span class="user-title">{{ __('Name') }}:</span> {{ $user->name }}</p>
                                    <p><span class="user-title">{{ __('Email') }}:</span> {{ $user->email }}</p>
                                    @if ($user->phone != null)
                                        <p><span class="user-title">{{ __('Phone') }}:</span> {{ $user->phone }}</p>
                                    @endif
                                    @if ($user->fax != null)
                                        <p><span class="user-title">{{ __('Fax') }}:</span> {{ $user->fax }}</p>
                                    @endif
                                    @if ($user->city != null)
                                        <p><span class="user-title">{{ __('City') }}:</span> {{ $user->city }}</p>
                                    @endif
                                    @if ($user->zip != null)
                                        <p><span class="user-title">{{ __('Zip') }}:</span> {{ $user->zip }}</p>
                                    @endif
                                    @if ($user->address != null)
                                        <p><span class="user-title">{{ __('Address') }}:</span> {{ $user->address }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Statistic section --}}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="widget border-0 p-30 widget_categories bg-light account-info card c-info-box-area">
                                <div class="c-info-box box2">
                                    <p>{{ Auth::user()->orders()->count() }}</p>
                                </div>
                                <div class="c-info-box-content">
                                    <h6 class="title">{{ __('Total Orders') }}</h6>
                                    <p class="text">{{ __('All Time') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="widget border-0 p-30 widget_categoriesbg-light account-info card c-info-box-area">
                                <div class="c-info-box box1">
                                    <p>
                                        {{ Auth::user()->orders()->where('order_status', Helper::ORDER_PENDING)->count() }}
                                    </p>
                                </div>
                                <div class="c-info-box-content">
                                    <h6 class="title">{{ __('Pending Orders') }}</h6>
                                    <p class="text">{{ __('All Time') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Statistic section End --}}
                    <div class="row table-responsive-lg">
                        <div class="col-lg-12">
                            <div class="widget border-0 p-30 widget_categories bg-light account-info">
                                <h4 class="widget-title down-line mb-30">{{ __('Recent Orders') }}</h4>
                                <div class="table-responsive">
                                    <table class="table order-table table-bordered" cellspacing="0" width="100%">
                                        <thead class="text-center">
                                            <tr>
                                                <th>{{ __('#Order') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Order Total') }}</th>
                                                <th>{{ __('Order Status') }}</th>
                                                <th>{{ __('View') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (Auth::user()->orders()->latest()->take(10)->get() as $order)
                                                	<tr>
											<td data-label="{{ __('#Order') }}">
												<div>
													{{ $order->order_number }}
												</div>
											</td>
											<td data-label="{{ __('Date') }}">
												<div>
													{{ date('d M Y', strtotime($order->created_at)) }}
												</div>
											</td>
											<td data-label="{{ __('Order Total') }}">
												<div>
													{{ \PriceHelper::showAdminCurrencyPrice($order->pay_amount * $order->currency_value, $order->currency_sign) }}
												</div>
											</td>
											<td data-label="{{ __('Order Status') }}">
												<div>
													{{ $order->order_custom_status }}
												</div>
											</td>
											<td data-label="{{ __('View') }}">
												<div>
													<a class="mybtn1 sm1"
													href="{{ route('user-order', $order->id) }}">
													{{ __('View Order') }}
													</a>
												</div>
											</td>
										</tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--==================== Blog Section End ====================-->
    @includeIf('partials.global.common-footer')
@endsection
