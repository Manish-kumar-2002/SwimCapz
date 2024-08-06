<!-- cap customize section start -->
@if ($latest_products)
        <section class="select-your-cap">
            <div class="container">
            <div class="customize-slider-wrap">
                <div class="slide-info">
                    <div class="left-block">
                        <h2>We have you covered</h2>
                        <p>Easy to customize to show your team spirit.</p>
                    </div>
                    <div class="slider-pagination">
                        <span id="nonActiveSlides"></span>
                        <span id="totalSlides"></span>
                    </div>
                    <div class="right-block">
                        <button id="left" onClick="myPrev()" class="slick-prev slick-arrow"></button>
                        <button id="right" onClick="myNext()" class="slick-next slick-arrow"></button>
                    </div>
                </div>
                <div class="customize-cap-slider">
                @foreach($latest_products as $product)

                        <div class="slide-card">
                            <div class="cap-wrap" style="--bg_drop: #FFF5F5">
                                
                                @if (!empty($product->getDefaultVariant))
                                    @php
                                        $default_image = $product->getDefaultVariant->variantImages->toArray();
                                    @endphp
                                    <a href="{{route('front.product', [
                                        $product->slug, $product->getDefaultVariant->id])}}"
                                        class="woocommerce-LoopProduct-link lazy-product-{{$product->id}}">
                                        <img
                                            class="lazy lazy-product"
                                            src="{{ $default_image[0]['images'] ?
                                            asset('assets/product/thumb/large/' . $default_image[0]['images']) :
                                            asset('assets/images/noimage.png') }}"
                                            alt="Product"
                                            width="109" height="90"
                                        >
                                    </a>
                                @else
                                    <a href="" class="woocommerce-LoopProduct-link">
                                        <img
                                            class="lazy"
                                            src="{{ asset('assets/images/noimage.png') }}"
                                            alt="Product"></a>
                                @endif
                            </div>
                            <div class="cap-content">
                                <h4>{{$product->name}}</h4>
                                <p>{!! $product->details !!}</p>
                                <div class="cap_clr_slider">
                                    @if(!empty($product->getDefaultVariant))
                                        @php
                                            $default_image = $product->getDefaultVariant->variantImages->toArray();
                                            $imagePath=$default_image[0]['images'] ?
                                            asset('assets/product/thumb/large/' . $default_image[0]['images']) :
                                            asset('assets/images/noimage.png');
                                        @endphp

                                        <input
                                            style="--dot-color:{{ $product->getDefaultVariant->color_code }} ;cursor:pointer;"
                                            type="radio"
                                            class="color-cap active colorCombinations"
                                            name="cap_color"
                                            data-href="{{route('front.product', [
                                                $product->slug, $product->getDefaultVariant->id]) }}"
                                            data-img="{{$imagePath}}"
                                            data-product-class="lazy-product-{{$product->id}}"
                                            data-price={{$product->getDefaultVariant->price}}
                                            data-discount={{$product->getDefaultVariant->discount_price}}
                                            id="color-{{$product->getDefaultVariant->id}}"
                                        />
                                    @endif

                                    @if(!empty($product->getVariant))
                                        
                                        @foreach ($product->getVariant as $variant_color)
                                            @php
                                                $default_image = $variant_color->variantImages->toArray();
                                                $imagePath=$default_image[0]['images'] ?
                                                asset('assets/product/thumb/large/' . $default_image[0]['images']) :
                                                asset('assets/images/noimage.png');
                                            @endphp

                                            <input
                                                style="--dot-color:{{ $variant_color->color_code }};cursor:pointer;"
                                                type="radio"
                                                class="color-cap colorCombinations"
                                                name="cap_color"
                                                data-href="{{ route('front.product', [
                                                    $product->slug, $variant_color->id]) }}"
                                                data-img="{{$imagePath}}"
                                                data-product-class="lazy-product-{{$product->id}}"
                                                data-price={{$variant_color->price}}
                                                data-discount={{$variant_color->discount_price}}
                                                id="color-{{$variant_color->id}}"
                                            />

                                        @endforeach

                                    @endif
                                </div>
                                <button
                                    class="btn designNow"
                                    data-link-class="lazy-product-{{$product->id}}"
                                >Design Now</button>
                            </div>
                        </div>
                @endforeach
                </div>
            </div>
        </section>
    @endif
@php
    $youtube = DB::table('youtube_links')
        ->where('id', 1)
        ->first();
@endphp
<section class="why-choose-us">
    <div class="container">
        <span class="vid-text">SwimCapz Inc.</span>
        <div class="vid-block">
            <iframe src="{{ $youtube->url }}" title="youtube content"></iframe>
            <h2>Why Choose<br>
                SwimCapz.com?</h2>
        </div>
    </div>
</section>
@php
    $galleryhome = App\Models\GalleryHome::where('status', 1)->get();
@endphp
<section class="swimcapz-foundation">
    <div class="swimcapz-banner">
        <img src="{{ asset('assets/front/custom-images/swim-banner.jpg') }}" alt="swim-banner">
    </div>
    <div class="swimcapz-foundation-content">
        <div class="container">
            <div class="left-block">
                <h2>Look Who's Wearing SwimCapz</h2>
            </div>
            <div class="right-block">
                @foreach ($galleryhome as $galleryhome_val)
                    <div class="foundation-card">
                        <div class="image-wrapper">
                            <img
                                src="{{ asset('assets/galleryhome/' . $galleryhome_val->image) }}"
                                alt="foundation">
                        </div>
                        <h4>{{ $galleryhome_val->title }}</h4>
                        <img src="{{ asset('assets/galleryhome/' . $galleryhome_val->logo) }}" alt="institute-logo">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="customize-process">
    <div class="container">
        <h2>Receiving your Custom Swim Caps is as easy as 1 2 3 ..</h2>
        <div class="process-wrap">
            <div class="blank-wrap">
                <div class="process-card">
                    <div class="image-wrapper">
                        <img
                            src="{{ asset('assets/front/custom-images/step-1.png') }}"
                            alt="process">

                    </div>
                    <div class="process-content">
                        <span class="number">1.</span>
                        <p>Design your custom swim gear on our easy to use online designer</p>
                    </div>
                </div>
                <div class="process-card">
                    <div class="image-wrapper">
                        <img
                            src="{{ asset('assets/front/custom-images/step-2.png') }}"
                            alt="process">

                    </div>
                    <div class="process-content">
                        <span class="number">2.</span>
                        <p>Add them to your cart and checkout.</p>
                    </div>
                </div>
                <div class="process-card">
                    <div class="image-wrapper">
                        <img
                            src="{{ asset('assets/front/custom-images/step-3.png') }}"
                            alt="process">
                        <span class="del-date">Get items In 10-12 days</span>
                    </div>
                    <div class="process-content">
                        <span class="number">3.</span>
                        <p>Receive your high quality custom swim gear in 10 -12 days.</p>
                    </div>
                </div>
            </div>
            <a href="{{ url('/category') }}" class="btn">customize your design</a>
        </div>
    </div>
</section>
@php
    $testimonial = App\Models\Testimonial::where('status', 1)->get();
@endphp

<section class="testimonial-block">
    <div class="container">
        <div class="left-block">
            <h2>What People say about SwimCapz.com</h2>
            <div class="btn-wrap">
                <button onClick="myPrev2()" class="slick-prev slick-arrow"></button>
                <button onClick="myNext2()" class="slick-next slick-arrow"></button>
            </div>
        </div>
        <div class="testimonial-slider">
            @foreach ($testimonial as $testimonial_val)
                <div class="slide">
                    <div class="review-block">
                        <p>{{ $testimonial_val->description }}</p>
                    </div>
                    <div class="reviewer-block">
                        <div class="profile">
                            <img src="{{ asset('assets/testimonial/' . $testimonial_val->image) }}" alt="profile">
                        </div>
                        <span class="name">{{ $testimonial_val->name }}</span>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>

<!--==================== Our Blog Section Start ====================-->
@if ($ps->blog == 1)
    <section class="blog-block">
        <div class="container">
            <div class="heading-wrap">
                <h2>In the Lanes</h2>
                <a href="{{ url('blog') }}" class="btn">View All</a>
            </div>
            <div class="blog-wrap">
                @foreach ($blogs as $blog)
                    <div class="blog">
                        <div class="thumb-latest-blog text-center transation hover-img-zoom mb-3">
                            <div class="post-image overflow-hidden">
                                <a class="image-wrap" href="{{ route('front.blogshow', $blog->slug) }}">
                                    <img class="lazy" data-src="{{ asset('assets/images/blogs/' . $blog->photo) }}"
                                        alt="Image not found!">
                                </a>

                            </div>
                            <div class="content">
                                <div class="post-meta">
                                    <p class="post-date blog-date">{{ date('d M, Y', strtotime($blog->created_at)) }}
                                    </p>
                                </div>
                                <h4><a
                                        href="{{ route('front.blogshow', $blog->slug) }}">{{ mb_strlen($blog->title, 'UTF-8') > 200 ? mb_substr($blog->title, 0, 200, 'UTF-8') . '...' : $blog->title }}</a>
                                </h4>
                                <!-- <p>{!! mb_strlen($blog->details, 'UTF-8') > 300
                                    ? mb_substr($blog->details, 0, 300, 'UTF-8') . '...'
                                    : $blog->details !!}</p> -->
                                <!-- <p>{!! $blog->details !!}</p> -->
                                <a href="{{ route('front.blogshow', $blog->slug) }}"
                                    class="btn-link-left-line">{{ __('Read More') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="price-wrapper">
                <div class="left-block">
                    <h3>Design online today and join the thousands of teams who have discover how easy it is.</h3>
                    <p>Our easy to use online designer and 10 - 12 day delivery makes it simple to get your team
                        their high quality swim caps quickly at a great price.</p>
                    <a href="{{ url('/category') }}" class="btn">Design now</a>
                </div>
                <div class="right-block">
                    <h2>Need to Know Pricing?</h2>
                    <p>Let us know more about what you need and we'll reach out with a quote.</p>
                    <a href="{{route('request.quotes')}}"  class="btn red">Request a Quote</a>
                </div>
            </div>
        </div>
    </section>
    <!--==================== Our Blog Section End ====================-->
@endif

@includeIf('partials.global.common-footer')

<script src="{{ asset('assets/front/js/extraindex.js') }}"></script>

<script>
    $(".lazy").Lazy();
</script>





<script>
    if ($('.customize-cap-slider').length) {
        $(".customize-cap-slider").slick({
            infinite: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            mobileFirst: true,
            dots: true,
            dotsClass: 'custom_paging',
            customPaging: function(slider, i) {
                return (i + 1) + '/' + slider.slideCount;
            },
            responsive: [{
                    breakpoint: 1200,
                    settings: {
                        arrows: false,
                        slidesToShow: 5,
                        customPaging: function(slider, i) {
                            return (i + 5) + '/' + slider.slideCount;
                        },
                    }
                },
                {
                    breakpoint: 1023,
                    settings: {
                        arrows: false,
                        slidesToShow: 4,
                        customPaging: function(slider, i) {
                            return (i + 4) + '/' + slider.slideCount;
                        },
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        arrows: false,
                        slidesToShow: 3,
                        customPaging: function(slider, i) {
                            return (i + 3) + '/' + slider.slideCount;
                        },
                    }
                },
                {
                    breakpoint: 549,
                    settings: {
                        slidesToShow: 2,
                        customPaging: function(slider, i) {
                            return (i + 2) + '/' + slider.slideCount;
                        },
                    }
                }
            ]
        });
    }


    if ($('.cap_clr_slider').length) {
        $(".cap_clr_slider").slick({
            infinite: false,
            slidesToShow: 7,
            slidesToScroll: 1,
            arrows: true,
        });
    }

    if ($('.testimonial-slider').length) {
        $(".testimonial-slider").slick({
            infinite: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            mobileFirst: true,
            responsive: [{
                breakpoint: 549,
                settings: {
                    slidesToShow: 2
                }
            }]
        });

    }


    function myPrev() {
        $(".customize-cap-slider").slick("slickPrev");
    }

    function myNext() {
        $(".customize-cap-slider").slick("slickNext");
    }

    function myPrev2() {
        $(".testimonial-slider").slick("slickPrev");
    }

    function myNext2() {
        $(".testimonial-slider").slick("slickNext");
    }

    let selectColor = document.getElementsByClassName("customize-cap-slider");
    let childrenElement = selectColor[0].children;
    let inputDiv = document.getElementsByClassName("cap_clr_slider");
    for (const element of inputDiv) {
        for (let l = 0; l < element.children.length; l++) {
            element.children[l].addEventListener('click', (e) => {
                let computedStyle = window.getComputedStyle(e.target);
                let activeColor = computedStyle.getPropertyValue("--dot-color");
                let bgColor = computedStyle.getPropertyValue("--bg_color");
                let parents = [];
                let currentElement = e.target.parentNode;
                while (currentElement !== null) {
                    parents.push(currentElement);
                    currentElement = currentElement.parentNode;
                }
                for (let j = 0; j < parents.length; j++) {
                    if (parents[j]?.classList?.value == 'slide-card') {
                        parents[j].children[0].children[0].children[0].setAttribute('fill', `${activeColor}`);
                        parents[j].children[0].style.backgroundColor = `${bgColor}`;
                    }
                }
            });
        }
    }
</script>
