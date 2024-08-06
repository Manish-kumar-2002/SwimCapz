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
                            <a href="{{ route('ttf.index') }}">{{ __('TTF Files') }}</a>
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
                        <form action="{{ route('ttf.store') }}" method="POST"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Title') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="title" placeholder="{{ __("Title") }}" value="{{old('title')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Font Name') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="font_name" placeholder="{{ __("Font Name") }}" value="{{old('font_name')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('TTF file') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7 choose-file-input">
                                    <input type="file" class="input-field" name="ttf" accept=".ttf">
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
