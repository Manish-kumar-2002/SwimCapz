@extends('layouts.front')
@section('css')
@endsection
@section('content')
    @include('partials.global.common-header')


    @include('partials.global.subscription-popup')


    @if ($ps->slider == 1)
        <div class="position-relative">
            <section class="banner-block">
                <img src="{{ asset('assets/front/custom-images/banner.jpg') }}" alt="banner">
                <div class="container">
                    <div class="content-wrap">
                        <div class="left-block">
                            <h1><span class="star">Design</span><br> Your Custom Swim Caps Directly Online</h1>
                            <a href="{{ route('front.category') }}" class="btn red">Design Now</a>
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
                                        <img src="{{ asset('assets/front/custom-images/user-profile.png') }}"
                                            alt="user-profile">
                                    </div>
                                    <div class="user-message">
                                        <p>Hi, What is the minimum quantity required
                                            to purchase the customized caps?</p>
                                    </div>
                                </div>
                                <div class="bot-wrap">
                                    <div class="profile">
                                        <img src="{{ asset('assets/front/custom-images/chatbot-icon.svg') }}"
                                            alt="user-profile">
                                    </div>
                                    <div class="user-message">
                                        <p>Minimum quantity is 50</p>
                                    </div>
                                </div>
                                <div class="user-wrap">
                                    <div class="profile">
                                        <img src="{{ asset('assets/front/custom-images/user-profile.png') }}"
                                            alt="user-profile">
                                    </div>
                                    <div class="user-message">
                                        <p>How can I design it</p>
                                    </div>
                                </div>
                                <div class="bot-wrap">
                                    <div class="profile">
                                        <img src="{{ asset('assets/front/custom-images/chatbot-icon.svg') }}"
                                            alt="user-profile">
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
                        {{-- <button class="chatbot">
                            <i class="icon-message"></i>
                        </button> --}}
                    </div>
                </div>
            </section>
        </div>
    @endif
    @if ($featured)
        <section class="news-block">
            <div class="container">
                <div class="heading-wrap">
                    <h2>Featured on</h2>
                    <div class="right-block">
                        <button id="left1" onClick="$('.news-slider').slick('slickPrev')"
                            class="slick-prev slick-arrow"></button>
                        <button id="right2" onClick="$('.news-slider').slick('slickNext')"
                            class="slick-next slick-arrow"></button>
                    </div>
                </div>
                <div class="news-wrap news-slider">
                    @foreach ($featured as $featured_val)
                        <a href="{{ $featured_val->link }}" class="news" target="_blank">
                            <figure class="image-wrap">
                                <img src="{{ asset('assets/featuredon/' . $featured_val->image) }}" alt="news-pic" />
                            </figure>
                            <div class="content">
                                <span class="news-date">{{ date('d, F Y', strtotime($featured_val->article_date)) }}</span>
                                <h4>{{ $featured_val->title }}</h4>
                                <p>{{ $featured_val->description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

            </div>
        </section>
    @endif
    <!-- cap customize content start -->
    <section class="customize-caps">
        <div class="container">
            <div class="content-wrap">
                <h2>{{ $aboutus->title }}</h2>
                <span>{!! $aboutus->description !!}</span>  <br>  <br>
                <div>
                    <br>
                    <a href="{{ url('/category') }}" class="btn">Design now</a>
                </div>
            </div>
        </div>
    </section>
    <!-- cap customize content end -->


    <div id="extraData">
        <div class="text-center">
            <img src="{{ asset('assets/images/' . $gs->loader) }}">
        </div>
    </div>

    @if (isset($visited))
        @if ($gs->is_cookie == 1)
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
        $(window).on('scroll', function() {

            if (checkTrur == 0) {
                $('#extraData').load('{{ route('front.extraIndex') }}');
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
