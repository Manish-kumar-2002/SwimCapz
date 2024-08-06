
@extends('layouts.front')
@section('css')

@endsection
@section('content')
@include('partials.global.common-header')


@include('partials.global.subscription-popup')


@if($ps->slider == 1)
    <div class="position-relative">
        <span class="nextBtn"></span>
        <span class="prevBtn"></span>
        <!-- <section class="home-slider owl-theme owl-carousel">
            @foreach($sliders as $data)
            <div class="banner-slide-item" style="background: url('{{asset('assets/images/sliders/'.$data->photo)}}') no-repeat center center / cover ;">
                <div class="container">
                    <div class="banner-wrapper-item text-{{ $data->position }}">
                        <div class="banner-content text-dark ">
                            <h5 class="subtitle text-dark slide-h5">{{$data->subtitle_text}}</h5>

                            <h2 class="title text-dark slide-h5">{{$data->title_text}}</h2>

                            <p class="slide-h5">{{$data->details_text}}</p>

                            <a href="{{$data->link}}" class="cmn--btn ">{{ __('SHOP NOW') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </section> -->
        <section class="banner-block">
            <img src="{{ asset('assets/front/custom-images/banner.jpg') }}" alt="banner">
            <div class="container">
                <div class="content-wrap">
                    <div class="left-block">
                        <h1><span class="star">Design</span><br> Your Custom Swim Caps Directly Online</h1>
                        <a href="#" class="btn red">Design Now</a>
                    </div>
                    <div class="right-block">
                        <img src="assets/front/custom-images/banner-img.png" alt="banner-img">
                    </div>
                </div>
                <div class="chatbot-block">
                    <div class="chatbot-wrapper">
                        <div class="chatbot-header">
                        <img src="{{ asset('assets/front/custom-images/chatbot-icon.svg') }}" alt="chatbot-image">
                            <h3>Chat Bot</h3>
                        </div>
                        <div class="chatbot-body">
                            <div class="user-wrap">
                                <div class="profile">
                                    <img src="{{ asset('assets/front/custom-images/user-profile.png') }}" alt="user-profile">
                                </div>
                                <div class="user-message">
                                    <p>Hi, What is the minimum quantity required
                                        to purchase the customized caps?</p>
                                </div>
                            </div>
                            <div class="bot-wrap">
                                <div class="profile">
                                    <img src="{{ asset('assets/front/custom-images/chatbot-icon.svg') }}" alt="user-profile">
                                </div>
                                <div class="user-message">
                                    <p>Minimum quantity is 50</p>
                                </div>
                            </div>
                            <div class="user-wrap">
                                <div class="profile">
                                    <img src="{{ asset('assets/front/custom-images/user-profile.png') }}" alt="user-profile">
                                </div>
                                <div class="user-message">
                                    <p>How can I design it</p>
                                </div>
                            </div>
                            <div class="bot-wrap">
                                <div class="profile">
                                    <img src="{{ asset('assets/front/custom-images/chatbot-icon.svg') }}" alt="user-profile">
                                </div>
                                <div class="user-message">
                                    <p>You may add your team's logo, name, your favorite picture, Add text, Add art.</p>
                                </div>
                            </div>
                        </div>
                        <div class="chatbot-input-wrap">
                            <input type="text" placeholder="Write a message...">
                            <button class="send-btn">
                                <i class="icon-plane"></i>
                            </button>
                        </div>
                    </div>
                    <button class="chatbot">
                        <i class="icon-message"></i>
                    </button>
                </div>
            </div>
        </section>
    </div>
@endif
@php
$featured = App\Models\FeaturedOn::where('status',1)->orderBY('id','DESC')->paginate(3);
@endphp
<section class="news-block">
            <div class="container">
                <div class="heading-wrap">
                    <h2>Featured on</h2>
                </div>
                <div class="news-wrap">
                <a href="https://www.usatoday.com/story/special/contributor-content/2023/06/22/leading-swim-cap-distributor-is-expanding-to-europe/70346584007/" class="news" target="_blank">
                        <figure class="image-wrap">
                            <img src="http://localhost:8035/assets/featuredon/1694516079_SRkNVdwFHAGYuz2ENjtr.jpg" alt="news-image">
                        </figure>
                        <div class="content">
                            <span class="news-date">03, March 2023</span>
                            <h4>Leading Swim Cap Distributor is Expanding Into High Performing Markets in Europe</h4>
                            <p>SwimCapz, a custom swim cap design company based in Canada, is expanding its services into the European market. 

The company provides custom swim caps for swim teams and events throughout the United States and Canada. The swim caps can be customized on the SwimCapz webpage. People can choose from a variety of colors and can have custom art and text put on the swim cap. The caps will then be delivered to customers worldwide with free shipping.</p>
                        </div>
                    </a>

                    <a href="https://www.usatoday.com/story/special/contributor-content/2023/06/22/leading-swim-cap-distributor-is-expanding-to-europe/70346584007/" class="news" target="_blank">
                        <figure class="image-wrap">
                            <img src="http://localhost:8035/assets/featuredon/1694516079_SRkNVdwFHAGYuz2ENjtr.jpg" alt="news-image">
                        </figure>
                        <div class="content">
                            <span class="news-date">03, March 2023</span>
                            <h4>Leading Swim Cap Distributor is Expanding Into High Performing Markets in Europe</h4>
                            <p>SwimCapz, a custom swim cap design company based in Canada, is expanding its services into the European market. 

The company provides custom swim caps for swim teams and events throughout the United States and Canada. The swim caps can be customized on the SwimCapz webpage. People can choose from a variety of colors and can have custom art and text put on the swim cap. The caps will then be delivered to customers worldwide with free shipping.</p>
                        </div>
                    </a>

                    <a href="https://www.usatoday.com/story/special/contributor-content/2023/06/22/leading-swim-cap-distributor-is-expanding-to-europe/70346584007/" class="news" target="_blank">
                        <figure class="image-wrap">
                            <img src="http://localhost:8035/assets/featuredon/1694516079_SRkNVdwFHAGYuz2ENjtr.jpg" alt="news-image">
                        </figure>
                        <div class="content">
                            <span class="news-date">03, March 2023</span>
                            <h4>Leading Swim Cap Distributor is Expanding Into High Performing Markets in Europe</h4>
                            <p>SwimCapz, a custom swim cap design company based in Canada, is expanding its services into the European market. 

The company provides custom swim caps for swim teams and events throughout the United States and Canada. The swim caps can be customized on the SwimCapz webpage. People can choose from a variety of colors and can have custom art and text put on the swim cap. The caps will then be delivered to customers worldwide with free shipping.</p>
                        </div>
                    </a>

                    <a href="https://www.usatoday.com/story/special/contributor-content/2023/06/22/leading-swim-cap-distributor-is-expanding-to-europe/70346584007/" class="news" target="_blank">
                        <figure class="image-wrap">
                            <img src="http://localhost:8035/assets/featuredon/1694516079_SRkNVdwFHAGYuz2ENjtr.jpg" alt="news-image">
                        </figure>
                        <div class="content">
                            <span class="news-date">03, March 2023</span>
                            <h4>Leading Swim Cap Distributor is Expanding Into High Performing Markets in Europe</h4>
                            <p>SwimCapz, a custom swim cap design company based in Canada, is expanding its services into the European market. 

The company provides custom swim caps for swim teams and events throughout the United States and Canada. The swim caps can be customized on the SwimCapz webpage. People can choose from a variety of colors and can have custom art and text put on the swim cap. The caps will then be delivered to customers worldwide with free shipping.</p>
                        </div>
                    </a>

                    <a href="https://www.usatoday.com/story/special/contributor-content/2023/06/22/leading-swim-cap-distributor-is-expanding-to-europe/70346584007/" class="news" target="_blank">
                        <figure class="image-wrap">
                            <img src="http://localhost:8035/assets/featuredon/1694516079_SRkNVdwFHAGYuz2ENjtr.jpg" alt="news-image">
                        </figure>
                        <div class="content">
                            <span class="news-date">03, March 2023</span>
                            <h4>Leading Swim Cap Distributor is Expanding Into High Performing Markets in Europe</h4>
                            <p>SwimCapz, a custom swim cap design company based in Canada, is expanding its services into the European market. 

The company provides custom swim caps for swim teams and events throughout the United States and Canada. The swim caps can be customized on the SwimCapz webpage. People can choose from a variety of colors and can have custom art and text put on the swim cap. The caps will then be delivered to customers worldwide with free shipping.</p>
                        </div>
                    </a>
                    <!-- @foreach($featured  as $featured_val)
                        <a href="{{$featured_val->link}}" class="news" target="_blank">
                            <figure class="image-wrap">
                            <img src="{{ asset('assets/featuredon/'.$featured_val->image) }}" alt="news-image">
                            </figure>
                            <div class="content">
                                <span class="news-date">{{date("d, F Y", strtotime($featured_val->article_date))}}</span>
                                <h4>{{$featured_val->title}}</h4>
                                <p>{{$featured_val->description}}</p>
                            </div>
                        </a>
                    @endforeach -->
                </div>
                
            </div>
        </section>
<!-- cap customize content start -->
@php
$aboutus = App\Models\AboutUs::findOrFail(1);
@endphp
<section class="customize-caps">
            <div class="container">
                <div class="content-wrap">
                    <h2>{{$aboutus->title}}</h2>
                    <span>{!! $aboutus->description !!}</span>
                    <a href="{{ url('/category') }}" class="btn">Design now</a>
                </div>
            </div>
        </section>
<!-- cap customize content end -->


<div id="extraData">
    <div class="text-center">
        <img  src="{{asset('assets/images/'.$gs->loader)}}">
    </div>
</div>

@if(isset($visited))
    @if($gs->is_cookie == 1)
        <div class="cookie-bar-wrap show">
            <div class="container d-flex justify-content-center">
                <div class="col-xl-10 col-lg-12">
                    <div class="row justify-content-center">
                        <div class="cookie-bar">
                            <div class="cookie-bar-text">
                                {{ __('The website uses cookies to ensure you get the best experience on our website.') }}
                            </div>
                            <div class="cookie-bar-action">
                                <button class="btn btn-primary btn-accept">
                                {{ __('GOT IT!') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endif
<!-- Scroll to top -->
<a href="#" class="scroller text-white" id="scroll"><i class="fa fa-angle-up"></i></a>

@endsection
@section('script')
	<script>
		let checkTrur = 0;
		$(window).on('scroll', function(){

		if(checkTrur == 0){
			$('#extraData').load('{{route('front.extraIndex')}}');
			checkTrur = 1;
		}
		});
        var owl = $('.home-slider').owlCarousel({
        loop: true,
        nav: false,
        dots: true,
        items: 1,
        autoplay: true,
        margin: 0,
        animateIn: 'fadeInDown',
        animateOut: 'fadeOutUp',
        mouseDrag: false,
    })
    $('.nextBtn').click(function() {
        owl.trigger('next.owl.carousel', [300]);
    })
    $('.prevBtn').click(function() {
        owl.trigger('prev.owl.carousel', [300]);
    })
	</script>
@endsection
