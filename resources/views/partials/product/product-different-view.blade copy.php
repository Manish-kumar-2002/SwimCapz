@if (Session::has('view'))
@if (Session::get('view')=='list-view')
<div class="row row-cols-xxl-2 row-cols-md-2 row-cols-1 g-3 product-style-1 shop-list product-list e-bg-light e-title-hover-primary e-hover-image-zoom">
   @foreach($prods as $product)
   <div class="col" >
      <div class="product type-product">
         <div class="product-wrapper">
            <div class="product-image">
               <a href="{{ route('front.product', $product->slug) }}" class="woocommerce-LoopProduct-link"><img class="lazy" data-src="{{ $product->photo ? asset('assets/images/products/'.$product->photo):asset('assets/images/noimage.png') }}" alt="Product Image"></a>
               @if (round($product->offPercentage() )>0)
               {{--
               <div class="on-sale">- {{ round($product->offPercentage() )}}%</div>
               --}}
               @endif
               <!-- <div class="hover-area">
                  @if($product->product_type == "affiliate")
                  <div class="cart-button buynow">
                     <a  class="add-to-cart-quick button add_to_cart_button" href="javascript:;" data-href="{{ route('product.cart.quickadd',$product->id) }}" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="{{ __('Buy Now') }}" aria-label="{{ __('Buy Now') }}"></a>
                  </div>
                  @else
                  @if($product->emptyStock())
                  <div class="closed">
                     <a class="cart-out-of-stock button add_to_cart_button" href="#" title="{{ __('Out Of Stock') }}" ><i class="flaticon-cancel flat-mini mx-auto"></i></a>
                  </div>
                  @else
                     @if ($product->type != "Listing") 
                     <div class="cart-button">
                        <a href="javascript:;" data-bs-toggle="modal" data-cross-href="{{route('front.show.cross.product',$product->id)}}"  {{$product->cross_products ? 'data-bs-target=#exampleModal' : ''}} data-href="{{ route('product.cart.add',$product->id) }}" class="add-cart button add_to_cart_button {{$product->cross_products ? 'view_cross_product' : ''}}" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="{{ __('Add To Cart') }}" aria-label="{{ __('Add To Cart') }}"></a>
                     </div>
                  
                     <div class="cart-button buynow">
                        <a  class="add-to-cart-quick button add_to_cart_button" href="javascript:;" data-href="{{ route('product.cart.quickadd',$product->id) }}" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="{{ __('Buy Now') }}" aria-label="{{ __('Buy Now') }}"></a>
                     </div>
                     @endif
                  @endif
                  @endif
                  @if(Auth::check())
                  <div class="wishlist-button">
                     <a class="add_to_wishlist  new button add_to_cart_button" id="add-to-wish" href="javascript:;" data-href="{{ route('user-wishlist-add',$product->id) }}" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist">{{ __('Wishlist') }}</a>
                  </div>
                  @else
                  <div class="wishlist-button">
                     <a class="add_to_wishlist button add_to_cart_button" href="{{ route('user.login') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist">{{ __('Wishlist') }}</a>
                  </div>
                  @endif
                  
                  @if ($product->type != "Listing") 
                     <div class="compare-button">
                        <a class="compare button button add_to_cart_button" data-href="{{ route('product.compare.add',$product->id) }}" href="javascrit:;" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Compare" aria-label="Compare">{{ __('Compare') }}</a>
                     </div>
                  @endif
                  </div> -->
            </div>
            <div class="product-info">
               <div class="content-wrap">
                  <h4 class="product-title"><a href="{{ route('front.product', $product->slug) }}">{{ $product->showName() }}</a></h4>
                  <span class="cap-title purple">{{$tags_val}}</span>
               </div>
               <div class="cap_clr_slider">
                  <input style="--dot-color:#008751" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#0A5FAF" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#1FA5D6" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#6B2A5E" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#E0A80F" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#F95951" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#FC2367" type="radio" class="color-cap active" name="cap_color">
                  <input style="--dot-color:#008751" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#0A5FAF" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#1FA5D6" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#6B2A5E" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#E0A80F" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#F95951" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#FC2367" type="radio" class="color-cap" name="cap_color">
               </div>
               <div class="product-price">
                  <div class="price">
                     <ins>{{ $product->setCurrency() }}</ins>
                     <del>{{ $product->showPreviousPrice() }}</del>
                  </div>
               </div>
               <!-- <div class="shipping-feed-back">
                  <div class="star-rating">
                     <div class="rating-wrap">
                        <p><i class="fas fa-star"></i><span> {{ number_format($product->ratings_avg_rating,1) }} ({{ $product->ratings_count }})</span></p>
                     </div>
                  </div>
                  </div> -->
            </div>
         </div>
      </div>
   </div>
   @endforeach
</div>
@else
<div class="row row-cols-xl-3 row-cols-md-3 row-cols-sm-2 row-cols-1 product-style-1 e-title-hover-primary e-image-bg-light e-hover-image-zoom e-info-center">
   @foreach($prods as $product)
   <div class="col" >
      <div class="product type-product">
         <div class="product-wrapper">
            <div class="product-image">
               <a href="{{ route('front.product', $product->slug) }}" class="woocommerce-LoopProduct-link"><img class="lazy" data-src="{{ $product->photo ? asset('assets/images/products/'.$product->photo):asset('assets/images/noimage.png') }}" alt="Product Image"></a>
               @if (round($product->offPercentage() )>0)
               {{--
               <div class="on-sale">- {{ round($product->offPercentage() )}}%</div>
               --}}
               @endif
               <!-- <div class="hover-area">
                  @if($product->product_type == "affiliate")
                    <div class="cart-button buynow">
                      <a  class="add-to-cart-quick button add_to_cart_button" href="javascript:;" data-href="{{ route('product.cart.quickadd',$product->id) }}" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="{{ __('Buy Now') }}" aria-label="{{ __('Buy Now') }}"></a>
                    </div>
                  @else
                  @if($product->emptyStock())
                    <div class="closed">
                       <a class="cart-out-of-stock button add_to_cart_button" href="#" title="{{ __('Out Of Stock') }}" ><i class="flaticon-cancel flat-mini mx-auto"></i></a>
                    </div>
                  @else
                    @if ($product->type != "Listing")
                    <div class="cart-button">
                       <a href="javascript:;" data-bs-toggle="modal" data-cross-href="{{route('front.show.cross.product',$product->id)}}"  {{$product->cross_products ? 'data-bs-target=#exampleModal' : ''}} data-href="{{ route('product.cart.add',$product->id) }}" class="add-cart button add_to_cart_button {{$product->cross_products ? 'view_cross_product' : ''}}" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="{{ __('Add To Cart') }}" aria-label="{{ __('Add To Cart') }}"></a>
                    </div>
                  
                       <div class="cart-button buynow">
                          <a  class="add-to-cart-quick button add_to_cart_button" href="javascript:;" data-href="{{ route('product.cart.quickadd',$product->id) }}" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="{{ __('Buy Now') }}" aria-label="{{ __('Buy Now') }}"></a>
                       </div>
                    @endif
                  @endif
                  @endif
                  @if(Auth::check())
                  <div class="wishlist-button">
                     <a class="add_to_wishlist  new button add_to_cart_button" id="add-to-wish" href="javascript:;" data-href="{{ route('user-wishlist-add',$product->id) }}" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist">{{ __('Wishlist') }}</a>
                  </div>
                  @else
                  <div class="wishlist-button">
                     <a class="add_to_wishlist button add_to_cart_button" href="{{ route('user.login') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist">{{ __('Wishlist') }}</a>
                  </div>
                  @endif
                  @if ($product->type != "Listing") 
                    <div class="compare-button">
                       <a class="compare button button add_to_cart_button" data-href="{{ route('product.compare.add',$product->id) }}" href="javascrit:;" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Compare" aria-label="Compare">{{ __('Compare') }}</a>
                    </div>
                  @endif
                  </div> -->
            </div>
            <div class="product-info">
               <div class="content-wrap">
                  <h4 class="product-title"><a href="{{ route('front.product', $product->slug) }}">{{ $product->showName() }}</a></h4>
                  <span class="cap-title red">Open Water</span>
                  <span class="cap-title green">Promotional Caps</span>
               </div>
               <div class="cap_clr_slider">
                  <input style="--dot-color:#008751" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#0A5FAF" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#1FA5D6" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#6B2A5E" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#E0A80F" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#F95951" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#FC2367" type="radio" class="color-cap active" name="cap_color">
                  <input style="--dot-color:#008751" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#0A5FAF" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#1FA5D6" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#6B2A5E" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#E0A80F" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#F95951" type="radio" class="color-cap" name="cap_color">
                  <input style="--dot-color:#FC2367" type="radio" class="color-cap" name="cap_color">
               </div>
               <div class="product-price">
                  <div class="price">
                     <ins>{{ $product->setCurrency() }}</ins>
                     <del>{{ $product->showPreviousPrice() }}</del>
                  </div>
               </div>
               <!-- <div class="shipping-feed-back">
                  <div class="star-rating">
                     <div class="rating-wrap">
                        <p><i class="fas fa-star"></i><span> {{ number_format($product->ratings_avg_rating,1) }} ({{ $product->ratings_count }})</span></p>
                     </div>
                  </div>
                  </div> -->
            </div>
         </div>
      </div>
   </div>
   @endforeach
</div>
@endif
@else
<div class="row row-cols-xl-3 row-cols-md-3 row-cols-sm-2 row-cols-1 product-style-1 e-title-hover-primary e-image-bg-light e-hover-image-zoom e-info-center">
   @foreach($prods as $product)
   <div class="col" >
      <div class="product type-product slide-card">
         <div class="product-wrapper">
            <div class="product-image cap-wrap">
               @if(!empty($product->getDefaultVariant))
               @php
               $default_image = $product->getDefaultVariant->variantImages->toArray();
               @endphp
               <a href="" class="woocommerce-LoopProduct-link"><img class="lazy" src="{{ $default_image[0]['images'] ? asset('assets/product/thumb/large/'.$default_image[0]['images']):asset('assets/images/noimage.png') }}" alt="Product Image"></a>
               @if (round($product->offPercentage() )>0)
               {{--
               <div class="on-sale">- {{ round($product->offPercentage() )}}%</div>
               --}}
               @endif
               @else
               <a href="" class="woocommerce-LoopProduct-link"><img class="lazy" src="{{asset('assets/images/noimage.png') }}" alt="Product Image"></a>
               @endif
               <!-- <div class="hover-area">
                  @if($product->product_type == "affiliate")
                  <div class="cart-button">
                     <a href="javascript:;" data-href="{{ $product->affiliate_link }}" class="button add_to_cart_button affilate-btn" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="{{ __('Add To Cart') }}" aria-label="{{ __('Add To Cart') }}"></a>
                  </div>
                  @else
                  @if($product->emptyStock())
                  <div class="cart-button">
                     <a class="cart-out-of-stock button add_to_cart_button" href="#" title="{{ __('Out Of Stock') }}" ><i class="flaticon-cancel flat-mini mx-auto"></i></a>
                  </div>
                  @else
                  @if ($product->type != 'Listing')
                  <div class="cart-button">
                     <a href="javascript:;" data-bs-toggle="modal" data-cross-href="{{route('front.show.cross.product',$product->id)}}"  {{$product->cross_products ? 'data-bs-target=#exampleModal' : ''}} data-href="{{ route('product.cart.add',$product->id) }}" class="add-cart button add_to_cart_button {{$product->cross_products ? 'view_cross_product' : ''}}" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="{{ __('Add To Cart') }}" aria-label="{{ __('Add To Cart') }}"></a>
                  </div>
                     <div class="cart-button buynow">
                        <a  class="button add_to_cart_button add-to-cart-quick" href="javascript:;" data-bs-toggle="tooltip" data-href="{{ route('product.cart.quickadd',$product->id) }}" data-bs-placement="right" title="{{ __('Buy Now') }}" data-bs-original-title="{{ __('Buy Now') }}"></a>
                     </div>
                  @endif
                  @endif
                  @endif
                  @if(Auth::check())
                  <div class="wishlist-button">
                     <a class="add_to_wishlist  new button add_to_cart_button" id="add-to-wish" href="javascript:;" data-href="{{ route('user-wishlist-add',$product->id) }}" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist">{{ __('Wishlist') }}</a>
                  </div>
                  @else
                  <div class="wishlist-button">
                     <a class="add_to_wishlist button add_to_cart_button" href="{{ route('user.login') }}" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist">{{ __('Wishlist') }}</a>
                  </div>
                  @endif
                  
                  @if ($product->type != 'Listing')
                  <div class="compare-button">
                     <a class="compare button button add_to_cart_button" data-href="{{ route('product.compare.add',$product->id) }}" href="javascrit:;" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Compare" aria-label="Compare">{{ __('Compare') }}</a>
                  </div>
                  @endif
                  </div> -->
            </div>
            <div class="product-info cap-content">
               <div class="content-wrap">
                  <h4 class="product-title"><a href="">{{ $product->showName() }}</a></h4>
                  @php
                  $tag_colors = $product->colors;
                  @endphp
                  @if(!empty($product->features))
                  @foreach((array) $product->features as $key => $tag)
                  <span class="cap-title" style="background-color:{{$tag_colors[$key]}}">{{ $tag }}</span>
                  @endforeach
                  @endif
               </div>
                <div class="cap_clr_slider">
                  	@if(!empty($product->getDefaultVariant))
                  		<input style="--dot-color:{{$product->getDefaultVariant->color_code}}" type="radio" class="color-cap active" name="cap_color" onclick="getVariantDetails('{{ route('front.product', [$product->slug,$product->getDefaultVariant->id]) }}')">
                  	@endif
                  	@if(!empty($product->getVariant))
                  		@foreach($product->getVariant as $variant_color)
                  			<input style="--dot-color:{{$variant_color->color_code}}" type="radio" class="color-cap active" name="cap_color" onclick="getVariantDetails('{{ route('front.product', [$product->slug,$variant_color->id]) }}')">
                  		@endforeach
                  	@endif
               </div>
               <div class="product-price">
                  @if(!empty($product->getDefaultVariant))
                  <div class="price">
                     <ins>{{$curr->sign}}{{ $product->getDefaultVariant->price }}</ins>
                     <del>{{$curr->sign}}{{ $product->getDefaultVariant->discount_price }}</del>
                  </div>
                  @endif
               </div>
               <!-- <div class="shipping-feed-back">
                  <div class="star-rating">
                     <div class="rating-wrap">
                        <p><i class="fas fa-star"></i><span> {{ number_format($product->ratings_avg_rating,1) }} ({{ $product->ratings_count }})</span></p>
                     </div>
                  </div>
                  </div> -->
            </div>
         </div>
      </div>
   </div>
   @endforeach
</div>
@endif
@section('script')
<script type="text/javascript">
   function getVariantDetails(url)
   {
      window.location.href=url;
   }
</script>
@endsection