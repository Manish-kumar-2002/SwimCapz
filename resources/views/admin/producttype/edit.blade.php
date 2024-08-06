@extends('layouts.admin')

@section('styles')
    <link href="{{ asset('assets/admin/css/jquery-ui.css') }}" rel="stylesheet" type="text/css">
@endsection


@section('content')
    <div class="content-area">

        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Edit Product Types') }} <a class="add-btn"
                            href="{{ route('admin-coupon-index') }}"><i class="fas fa-arrow-left"></i>
                            {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-product-type-index') }}">{{ __('Product Types') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-product-type-edit', $data->id) }}">{{ __('Edit Product Types') }}</a>
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
                                style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            @include('includes.admin.form-both')
                            <form id="geniusformdata" action="{{ route('admin-product-type-update', $data->id) }}"
                                method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Name') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" name="name"
                                            placeholder="{{ __('Enter the name') }}" required=""
                                            value="{{ $data->name }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Status') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <select id="type" name="status" required>
                                            <option value="">{{ __('Choose a Status') }}</option>
                                            <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>
                                                {{ __('Active') }}</option>
                                            <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>
                                                {{ __('Deactive') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <br>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-7">
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
