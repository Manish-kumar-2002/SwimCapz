@extends('layouts.front')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/front/css/datatables.css') }}">
@endsection
@section('content')
    @include('partials.global.common-header')
    <!-- breadcrumb -->
    <div class="full-row bg-light overlay-dark py-5"
        style="background-image: url({{ $gs->breadcrumb_banner
            ? asset('assets/images/' . $gs->breadcrumb_banner)
            : asset('assets/images/noimage.png') }});
        background-position: center center; background-size: cover;">
        <div class="container">
            <div class="row text-left text-white">
                <div class="col-12">
                    <h3 class="mb-2 text-white">{{ __('Orders') }}</h3>
                </div>
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('user-dashboard') }}">{{ __('Dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Orders') }}</li>
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
                    <div class="row table-responsive-lg">
                        <div class="col-lg-12">
                            <div class="widget border-0 p-30 widget_categories bg-light account-info table-responsive">
                                <table class="table order-table table-bordered" id="example">
                                    <caption style="display:none;">&nbsp;</caption>
                                    <thead>
                                        <tr>
                                            <th>{{ __('#Sno') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Front Design') }}</th>
                                            <th>{{ __('Back Design') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (Auth::user()->savedDesign as $key => $row)
                                            <tr>
                                                <td style="vertical-align: middle;"> {{$key + 1}} </td>
                                                <td style="vertical-align: middle;"> {{$row->name}} </td>
                                                @if ($row->design && $row['design'])
                                                    @foreach ($row['design'] as $key => $item)
                                                        @php
                                                            if ($key > 1) {
                                                                continue;
                                                            }
                                                        @endphp

                                                        <td style="vertical-align: middle;">
                                                            @if ($item && $item['design'])
                                                                <img
                                                                    src="{{$item['design']}}"
                                                                    style="height:100px;cursor:pointer;"
                                                                    alt="Design"
                                                                    class="img-viewer"
                                                                />
                                                            @else
                                                                &nbsp;
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                    <td style="vertical-align: middle;">
                                                        <a href="{{$row->edit_link}}">
                                                            <i class="fa fa-edit" style="color:green;"></i>
                                                        </a>
                                                    </td>
                                                @else
                                                    <td colspan="3">
                                                        Empty
                                                    </td>
                                                @endif
                                               
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
    <!--==================== Blog Section End ====================-->

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Viewer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" class="img-fluid" alt="Selected Image">
                </div>
            </div>
        </div>
    </div>

    @includeIf('partials.global.common-footer')
@endsection
@section('script')
    <script src="{{ asset('assets/front/js/dataTables.min.js') }}" defer></script>
    <script src="{{ asset('assets/front/js/user.js') }}" defer></script>

    <script>
        $(document).ready(function() {
            $('.img-viewer').on('click', function() {
                var src = $(this).attr('src');
                $('#modalImage').attr('src', src);
                $('#imageModal').modal('show');
            });
        });
    </script>
@endsection
