@extends('layouts.front')

@section('content')
@include('partials.global.common-header')
{{--
<!-- breadcrumb -->
<!-- <div class="full-row bg-light overlay-dark py-5" style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white">{{ $page->title }}</h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li>

                        <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div> -->
<!-- breadcrumb -->
--}}
  <!--==================== About Owner Section Start ====================-->
  <div class="full-row">
    <div class="container">
        <div class="row terms-condition">
            <div class="col">
                {!! clean(@$page->details , array('Attr.EnableID' => true)) !!}
            </div>
            
        </div>
    </div>
</div>
<!--==================== About Owner Section End ====================-->




@includeIf('partials.global.common-footer')

@endsection
