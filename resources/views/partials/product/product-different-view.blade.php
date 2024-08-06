    @if (Session::has('view'))
    @if (Session::get('view') == 'list-view')
        <div
            class="row row-cols-xxl-2 row-cols-md-2 row-cols-1 g-3 product-style-1 shop-list product-list e-bg-light e-title-hover-primary e-hover-image-zoom">
            @foreach ($prods as $product)
                <div class="col">
                    <div class="product type-product">
                        <div class="product-wrapper">
                            <div class="product-image">
                                <a href="{{ route('front.product', $product->slug) }}"
                                    class="woocommerce-LoopProduct-link">
                                    <img class="lazy"
                                        data-src="{{ $product->photo ? asset('assets/images/products/' . $product->photo) : asset('assets/images/noimage.png') }}"
                                        alt="Product Image">
                                </a>

                            </div>
                            <div class="product-info">
                                <div class="content-wrap">
                                    <h4 class="product-title">
                                        <a href="{{ route('front.product', $product->slug) }}">
                                            {{ $product->showName() }}
                                        </a>
                                    </h4>
                                    <span class="cap-title purple">{{ $tags_val }}</span>
                                </div>
                                <div class="cap_clr_slider">
                                    <input style="--dot-color:#008751" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#0A5FAF" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#1FA5D6" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#6B2A5E" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#E0A80F" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#F95951" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#FC2367" type="radio" class="color-cap active"
                                        name="cap_color">
                                    <input style="--dot-color:#008751" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#0A5FAF" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#1FA5D6" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#6B2A5E" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#E0A80F" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#F95951" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#FC2367" type="radio" class="color-cap"
                                        name="cap_color">
                                </div>
                                <div class="product-price">
                                    <div class="price">
                                        <ins>{{ $product->setCurrency() }}</ins>
                                        <del>{{ $product->showPreviousPrice() }}</del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div
            class="row row-cols-xl-3 row-cols-md-3 row-cols-sm-2 row-cols-1 product-style-1 e-title-hover-primary e-image-bg-light e-hover-image-zoom e-info-center">
            @foreach ($prods as $product)
                <div class="col">
                    <div class="product type-product">
                        <div class="product-wrapper">
                            <div class="product-image">
                                <a href="{{ route('front.product', $product->slug) }}"
                                    class="woocommerce-LoopProduct-link">
                                    <img class="lazy"
                                        data-src="{{ $product->photo ? asset('assets/images/products/' . $product->photo) : asset('assets/images/noimage.png') }}"
                                        alt="Product Image"></a>
                            </div>
                            <div class="product-info">
                                <div class="content-wrap">
                                    <h4 class="product-title">
                                        <a href="{{ route('front.product', $product->slug) }}">
                                            {{ $product->showName() }}
                                        </a>
                                    </h4>
                                    <span class="cap-title red">Open Water</span>
                                    <span class="cap-title green">Promotional Caps</span>
                                </div>
                                <div class="cap_clr_slider">
                                    <input style="--dot-color:#008751" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#0A5FAF" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#1FA5D6" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#6B2A5E" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#E0A80F" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#F95951" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#FC2367" type="radio" class="color-cap active"
                                        name="cap_color">
                                    <input style="--dot-color:#008751" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#0A5FAF" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#1FA5D6" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#6B2A5E" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#E0A80F" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#F95951" type="radio" class="color-cap"
                                        name="cap_color">
                                    <input style="--dot-color:#FC2367" type="radio" class="color-cap"
                                        name="cap_color">
                                </div>
                                <div class="product-price">
                                    <div class="price">
                                        <ins>{{ $product->setCurrency() }}</ins>
                                        <del>{{ $product->showPreviousPrice() }}</del>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@else

    <div
        class="row row-cols-xl-3 row-cols-md-3 row-cols-sm-2 row-cols-1
		product-style-1 e-title-hover-primary e-image-bg-light e-hover-image-zoom e-info-center">
        @foreach ($prods as $product)
            <div class="col">
                <div class="product type-product slide-card">
                    <div class="product-wrapper">
                        <div class="product-image cap-wrap">
                            @if (!empty($product->getDefaultVariant))
                                @php
                                    $default_image = $product->getDefaultVariant->variantImages->toArray();
                                @endphp
                                <a href="{{route('front.product', [$product->slug, $product->getDefaultVariant->id])}}"
									class="woocommerce-LoopProduct-link lazy-product-{{$product->id}}">
                                    <img
										class="lazy lazy-product"
                                        src="{{ isset($default_image[0]['images']) ?
										asset('assets/product/thumb/large/' . $default_image[0]['images']) :
										asset('assets/images/noimage.png') }}"
                                        alt="Product Image"
									>
                                </a>
                            @else
                                <a href="" class="woocommerce-LoopProduct-link">
                                    <img class="lazy" src="{{ asset('assets/images/noimage.png') }}"
                                        alt="Product Image"></a>
                            @endif
                        </div>
                        <div class="product-info cap-content">
                            <div class="content-wrap">
                                <h4 class="product-title">
                                    <a
                                        href="{{route('front.product', [
                                            $product->slug, $product->getDefaultVariant->id
                                        ])}}"
                                    >
                                        {{ $product->showName() }}
                                    </a>
                                </h4>
                                @php
                                    $tag_colors = $product->colors;
                                @endphp
                                @if (!empty($product->features))
                                    @foreach ((array) $product->features as $key => $tag)
                                        <span class="cap-title"
                                            style="background-color:{{ $tag_colors[$key] }}">{{ $tag }}</span>
                                    @endforeach
                                @endif
                            </div>
                            <div class="cap_clr_slider">
                               
								@if(!empty($product->getDefaultVariant))
									@php
                                    	$default_image = $product->getDefaultVariant->variantImages->toArray();
										$imagePath= isset($default_image[0]['images']) ?
										asset('assets/product/thumb/large/' . $default_image[0]['images']) :
										asset('assets/images/noimage.png');
                                	@endphp

									<input
										style="--dot-color:{{ $product->getDefaultVariant->color_code }} ;cursor:pointer;"
                                        type="radio"
										class="color-cap active colorCombinations"
										name="cap_color"
										data-href="{{route('front.product', [$product->slug, $product->getDefaultVariant->id]) }}"
										data-img="{{$imagePath}}"
										data-product-class="lazy-product-{{$product->id}}"
										data-price={{Helper::convertPrice($product->getDefaultVariant->price)}}
										data-discount={{Helper::convertPrice($product->getDefaultVariant->discount_price)}}
									>
								@endif

								@if(!empty($product->getVariant))
                                    
									@foreach ($product->getVariant as $variant_color)
										@php
											$default_image = $variant_color->variantImages->toArray();
											$imagePath = isset($default_image[0]['images']) ?
											asset('assets/product/thumb/large/' . $default_image[0]['images']) :
											asset('assets/images/noimage.png');
                                		@endphp

										<input
											style="--dot-color:{{ $variant_color->color_code }};cursor:pointer;"
											type="radio"
                                            class="color-cap colorCombinations"
											name="cap_color"
											data-href="{{ route('front.product', [$product->slug, $variant_color->id]) }}"
											data-img="{{$imagePath}}"
											data-product-class="lazy-product-{{$product->id}}"
											data-price={{Helper::convertPrice($variant_color->price)}}
											data-discount={{Helper::convertPrice($variant_color->discount_price)}}
										>

									@endforeach

								@endif
                            </div>
                            <div class="product-price">
                                @if (!empty($product->getDefaultVariant))
                                    <div class="price">
                                        <ins>
											<span class="lazy-product-{{$product->id}}-discount_price">
                                                @if($product->getDefaultVariant->discount_price)
												{{ Helper::convertPrice($product->getDefaultVariant->discount_price) }}
                                                @else
												{{ Helper::convertPrice($product->getDefaultVariant->price) }}
                                                @endif
											</span>
										</ins>
                                        <del>
                                            <span class="lazy-product-{{$product->id}}-price">
                                                @if($product->getDefaultVariant->discount_price)
												{{ Helper::convertPrice($product->getDefaultVariant->price) }}
                                                @else
                                                @endif
											</span>
										</del>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
