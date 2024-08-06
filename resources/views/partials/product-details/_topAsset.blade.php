<div class="row single-product-wrapper product-info">
    <div class="col-12 col-lg-6 mb-5 mb-lg-0">
        <div class="product-images overflow-hidden">
            <div class="images-inner">
                <div class="woocommerce-product-gallery woocommerce-product-gallery--with-images
                        woocommerce-product-gallery--columns-4 images"
                    data-columns="4" style="opacity: 1; transition: opacity 0.25s ease-in-out 0s;">
                    <figure class="woocommerce-product-gallery__wrapper">
                        <div class="product_view">
                            <img id="single-image-zoom"
                                @if (isset($variant_details->variantImages[0]->images)) src="{{ filter_var($variant_details->variantImages[0]->images, FILTER_VALIDATE_URL)
                                    ? $variant_details->variantImages[0]->images
                                    : asset('assets/product/' . $variant_details->variantImages[0]->images) }}"
                                alt="Thumb Image"
                                data-zoom-image="{{ filter_var($variant_details->variantImages[0]->images, FILTER_VALIDATE_URL)
                                    ? $variant_details->variantImages[0]->images
                                    : asset('assets/product/thumb/large/' . $variant_details->variantImages[0]->images) }}" 
                            @else
                            src="{{ asset('assets/images/noimage.png') }}"
                            alt="Thumb Image"
                            data-zoom-image="{{ asset('assets/images/noimage.png') }}" @endif />

                        </div>
                        <div id="gallery_09" class="product-slide-thumb">
                            <div class="product_gallery">
                                @foreach ($variant_details->variantImages as $gal)
                                    <div class="item">
                                        <a class="active"
                                            href="{{ asset('assets/product/thumb/large/' . $gal->images) }}"
                                            data-image="{{ asset('assets/product/' . $gal->images) }}"
                                            data-zoom-image="{{ asset('assets/product/thumb/large/' . $gal->images) }}">
                                            <img src="{{ asset('assets/product/thumb/small/' . $gal->images) }}"
                                                alt="Thumb Image" />
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </figure>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-5 col-md-8">
        <div class="summary entry-summary right-block">
            <div class="summary-inner">
                <h2 class="product_title entry-title">{{ $productt->name }}</h2>
                <div class="title-wrap">
                    @php
                        $tag_colors = $productt->colors;
                    @endphp
                    @if (!empty($productt->features))
                        @foreach ((array) $productt->features as $key => $tag)
                            <span class="cap-title"
                                style="background-color:{{ $tag_colors[$key] }}">{{ $tag }}</span>
                        @endforeach
                    @endif
                </div>
                <p><strong>{{ $variant_details->discount_price ? Helper::convertPrice($variant_details->discount_price) : Helper::convertPrice($variant_details->price) }}</strong> / each</p>
                <div class="order-quantity">
                    <h3>Minimum Order Size: {{ $variant_details->minimum_order }}</h3>
                    <p>To purchase this item, your order must include at least
                        <strong>{{ $variant_details->minimum_order == 1 ? $variant_details->minimum_order." item " : $variant_details->minimum_order." items "   }} </strong> of the
                        selected color.
                    </p>
                </div>
                <div class="product-color">
                    @if (count($productt->variant) > 1)
                        <div class="title">Other Color :</div>&nbsp;
                        <ul class="color-list">
                            @foreach ($productt->variant as $variant_color)
                                <?php
                                $style = $variant_color->id == $variant_details->id ? 'style="border: 2px solid #e02020;padding: 3px;"' : '';
                                ?>
                                <li class="show-colors" {!! $style !!}>
                                    <a class="color-change"
                                        href="{{ route('front.product', [$productt->slug, $variant_color->id]) }}">
                                        <span href="ab" class="box"
                                            data-color="{{ $variant_color->color_code }}"
                                            style="background-color:{{ $variant_color->color_code }}"></span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <ul class="product-info-listing">
                    <li><span class="product-list-title">Available Sizes: </span> {{ $variant_details->size }}
                    </li>
                    <li><a href="{{ asset('storage/productChart/' . $productt->size_chart) }}"><span
                                class="product-list-title">Size Chart</span></a></li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22"
                            fill="none">
                            <g clip-path="url(#clip0_515_2237)">
                                <path d="M7.3335 2.75H21.0835V14.6667H7.3335V2.75Z" stroke="#5C5C5C" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M7.3335 7.33333H3.66683L0.91683 10.0833V14.6667H7.3335V7.33333Z"
                                    stroke="#5C5C5C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                                <path
                                    d="M16.9583 19.25C15.6927 19.25 14.6667 18.224 14.6667 16.9583C14.6667 15.6927 15.6927 14.6667 16.9583 14.6667C18.224 14.6667 19.25 15.6927 19.25 16.9583C19.25 18.224 18.224 19.25 16.9583 19.25Z"
                                    stroke="#5C5C5C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                                <path
                                    d="M5.04183 19.25C3.77618 19.25 2.75016 18.224 2.75016 16.9583C2.75016 15.6927 3.77618 14.6667 5.04183 14.6667C6.30748 14.6667 7.3335 15.6927 7.3335 16.9583C7.3335 18.224 6.30748 19.25 5.04183 19.25Z"
                                    stroke="#5C5C5C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </g>
                            <defs>
                                <clipPath id="clip0_515_2237">
                                    <rect width="22" height="22" fill="white"
                                        transform="matrix(-1 0 0 1 22 0)"></rect>
                                </clipPath>
                            </defs>
                        </svg>
                        <span class="product-list-title">Delivery: </span>{{ $productt->ship }}
                    </li>
                    @if ($productt->sku != null)
                        <li
                            class="product-offer-item product-id{{ $productt->product_type == 'affiliate' ? 'mt-4' : '' }}">
                            <span class="product-list-title">{{ __('Product SKU:') }} </span> {{ $productt->sku }}
                        </li>
                    @endif
                    @if ($variant_details->emptyStock())
                        <li class="stock-availability out-stock" style="color:red;">{{ 'Out Of Stock' }}
                        </li>
                    @else
                        <li class="stock-availability in-stock text-bold">{{ $variant_details->quantity }}
                            {{ 'In Stock' }}</li>
                    @endif
                </ul>

                <div class="btn-wrap">
                    <a href="{{ route('tool.details') }}?product-id={{ $variant_details->id }}" class="btn red">Design
                        now</a>
                </div>
                <div class="pro-details">
                    <div class="pro-info">
                        {{-- PRODUCT OTHER DETAILS SECTION --}}
                    </div>
                </div>
                @if ($productt->stock_check == 1)
                    @if (!empty($productt->size))
                        <div class="product-size">
                            <p class="title">{{ __('Size :') }}</p>
                            <ul class="siz-list">
                                @foreach (array_unique($productt->size) as $key => $data1)
                                    <li class="{{ $loop->first ? 'active' : '' }}"
                                        data-key="{{ str_replace(' ', '', $data1) }}">
                                        <span class="box">
                                            {{ $data1 }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endif
                <input type="hidden" id="product_price"
                    value="{{ round($productt->vendorPrice() * $curr->value, 2) }}">
                <input type="hidden" id="product_id" value="{{ $productt->id }}">
                <input type="hidden" id="curr_pos" value="{{ $gs->currency_format }}">
                <input type="hidden" id="curr_sign" value="{{ $curr->sign }}">
                <input type="hidden" id="varient_id" value="{{ $variant_details->id }}">

                @if (!empty($productt->size))
                    <input type="hidden" id="stock" value="{{ $productt->size_qty[0] }}">
                @else
                    @if (!$productt->emptyStock())
                        <input type="hidden" id="stock" value="{{ $productt->stock }}">
                    @elseif($productt->type != 'Physical')
                        <input type="hidden" id="stock" value="0">
                    @else
                        <input type="hidden" id="stock" value="">
                    @endif
                @endif
                @if ($productt->is_discount == 1 && $productt->discount_date >= date('Y-m-d') && $productt->user->is_vendor == 2)
                    <div class="time-count time-box text-center my-30 flex-between w-75"
                        data-countdown="{{ $productt['discount_date'] }}"></div>
                @endif

                <script async src="https://static.addtoany.com/menu/page.js"></script>
                @if (!empty($productt->attributes))
                    @php
                        $attrArr = json_decode($productt->attributes, true);
                    @endphp
                @endif
            </div>
        </div>
    </div>
</div>
<div class="product-description">
    <ul class="nav nav-tabs" style="display: flex; justify-content:space-between" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                type="button" role="tab" aria-controls="home" aria-selected="true">
                <h3>DESCRIPTION</h3>
            </button>
        </li>
        @if ($productt->size_chart)
            <li class="nav-item" role="presentation">
                <a target="_blank" href="{{ asset('storage/productChart/' . $productt->size_chart) }}">
                    <button class="nav-link" id="profile-tab" type="button" role="tab">
                        <h3>SIZE CHART</h3>
                    </button>
                </a>
            </li>
        @endif
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active " id="home" role="tabpanel" aria-labelledby="home-tab">
            <p>{!! $productt->details !!}</p>
        </div>
    </div>
</div>
@if ($productt->policy != "<br>")
    <div class="product-description">
        <li class="nav-item" role="presentation" style="list-style-type: none;" >
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#home"
                type="button" role="tab" aria-controls="home" aria-selected="true">
                <h3> RETURN POLICY</h3>
            </button>
        </li>
        <div class="tab-content" id="myTabContent" style="list-style-type: none;">
            <div class="tab-pane fade show active " id="home" role="tabpanel" aria-labelledby="home-tab">
                <p>{!! $productt->policy !!}</p>
            </div>
        </div>
    </div>
@endif
