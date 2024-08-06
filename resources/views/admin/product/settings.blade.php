@extends('layouts.admin')

@section('content')
    <div class="content-area">

        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Product Settings') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Products') }}</a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Product Settings') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="add-product-content1 add-product-content2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ asset('assets/images/' . $gs->admin_loader) }})
                                no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form action="{{ route('admin-gs-prod-settings-update') }}" id="geniusformdata" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                @include('alerts.admin.form-both')

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Latest Product') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="action-list">
                                            <select
                                                id="latest_products"
                                                class="form-control"
                                                name="latest_products[]" multiple>
                                                @foreach ($latestProducts as $item)
                                                    <option value="{{$item->id}}" selected>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Featured On') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="action-list">
                                            <select
                                                id="featured_on"
                                                class="form-control" name="featured_on[]" multiple>
                                                @foreach ($featuredOn as $item)
                                                    <option value="{{$item->id}}" selected>{{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    {{-- TAGIT --}}

    <script type="text/javascript">
        (function($) {
            "use strict";

            $("#product_page").tagit({
                fieldName: "product_page[]",
                allowSpaces: true
            });
            $("#wishlist_page").tagit({
                fieldName: "wishlist_page[]",
                allowSpaces: true
            });

        })(jQuery);
    </script>

    {{-- TAGIT ENDS --}}

    

    <script>
        $('#latest_products').select2({
            placeholder: '-Latest product-',
            ajax: {
                url: "{{ route('ajax.search.products') }}",
                data: function(params) {
                    var query = {
                        search: params.term,
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
        });

        $('#featured_on').select2({
            placeholder: '-Featured on-',
            ajax: {
                url: "{{ route('ajax.search.featuredon') }}",
                data: function(params) {
                    var query = {
                        search: params.term,
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
        });
    </script>
@endsection
