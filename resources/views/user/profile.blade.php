@extends('layouts.front')
@section('content')
    @include('partials.global.common-header')
    <style>
        .address {
            box-shadow: 0 10px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19) !important;
            margin: 5px;
        }
    </style>
    <!-- breadcrumb -->
    <div class="full-row bg-light overlay-dark py-5"
        style="background-image: url({{ $gs->breadcrumb_banner ?
            asset('assets/images/' . $gs->breadcrumb_banner) :
            asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
        <div class="container">
            <div class="row text-left text-white">
                <div class="col-12">
                    <h3 class="mb-2 text-white">{{ __('Edit Profile') }}</h3>
                </div>
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('user-dashboard') }}">{{ __('Dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Profile') }}</li>
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
                            <div class="widget border-0 p-10 widget_categories bg-light account-info">
                                <div class="edit-info-area">
                                    <div class="body">
                                        <div class="edit-info-area-form">
                                            <div class="gocover"
                                                style="background: url({{ asset('assets/images/' . $gs->loader) }})
                                                no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                                            </div>
                                            
                                            <!-- Nav tabs -->
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-bs-toggle="tab" href="#home">
														<b>{{ __('Edit Profile') }}</b></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#menu1">
														<b>{{ __('Address') }}</b></a>
                                                </li>
                                            </ul>
                                            <!-- Tab panes -->
                                            <div class="tab-content">
                                                <div id="home" class="container tab-pane active"><br>
                                                    @include('user._assets._profile',['page' => 0])
                                                </div>
                                                <div id="menu1" class="container tab-pane fade"><br>
                                                    @include('user._assets._profile',['page' => 1])
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
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
   @include('user._assets._profileScript')
@endsection
