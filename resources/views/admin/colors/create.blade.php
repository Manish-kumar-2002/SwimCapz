@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('TTF') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('TTF Files') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('colors.index') }}">{{ __('Colors') }}</a>
                        </li>
                        <li>
                            <span>{{ __('Add') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">

                        @if ($errors->any())
                            <div class="alert alert-danger validation">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <ul class="text-left">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('colors.store') }}" method="POST"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Name') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="name" placeholder="{{ __("Name") }}" value="{{old('name')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Code') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="color" class="input-field" name="code" placeholder="{{ __("Code") }}" value="{{old('code')}}">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <button class="btn btn-success" type="submit">{{ __("Save") }}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript"></script>
@endsection
