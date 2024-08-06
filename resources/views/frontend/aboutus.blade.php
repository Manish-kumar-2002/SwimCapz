@extends('layouts.front')

@section('content')
@include('partials.global.common-header')

  <div class="full-row">
    <div class="container">
        <div class="row terms-condition">
            @foreach($data as $about)
            <section class="customize-caps">
                <div class="container">
                    <div class="content-wrap">
                        <h2>{{ $about->title }}</h2>
                        <span>{!! $about->description !!}</span>  <br>  <br>
                    </div>
                </div>
            </section>
            @endforeach
        </div>
    </div>
</div>
<!--==================== About Owner Section End ====================-->




@includeIf('partials.global.common-footer')

@endsection
