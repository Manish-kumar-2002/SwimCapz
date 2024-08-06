@php
    $categories = Helper::categories();
    
@endphp
<div class="col-12">
    <div class="row align-items-baseline product-content-wrap"> 
<div class="col-xl-6">
<div class="humberger-wrap">
                            <button class="dashboard-sidebar-btn btn bg-transparent rounded d-xl-none" type="button">Filter Menu
                            <i class="fas fa-chevron-down"></i>
                            </button>

                            <ul class="breadcrumb">
                <li>
                    <a href="{{ route('front.index') }}">{{ __('Home') }}</a>
                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="12" height="12" viewBox="0 0 12 12" fill="none"
                    >
                        <path d="M4.3335 2.5L7.8335 6L4.3335 9.5" stroke="#ACACAC" stroke-linecap="round"
                            stroke-linejoin="round"
                        ></path>
                    </svg>
                </li>
                <li aria-current="page">{{ __('Products') }}</li>
            </ul>
                        </div>
</div>
<div class="col-xl-6">
<div class="products-header-right">
        <div class="search-form">
            <form>
                <input type="search" placeholder="Search..." id="prod_name"
                name="search" value="{{request()->search ?? ''}}">
                <button class="search-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                        fill="none">
                        <path
                            d="M8.25 14.25C11.5637 14.25 14.25 11.5637 14.25 8.25C14.25 4.93629 11.5637 2.25 8.25 2.25C4.93629 2.25 2.25 4.93629 2.25 8.25C2.25 11.5637 4.93629 14.25 8.25 14.25Z"
                            stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M15.7498 15.75L12.4873 12.4875" stroke="black" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </button>
            </form>
        </div>
        <div class="select_box">
            <form class="woocommerce-ordering custom-select" method="get">
                <select name="sort" class="orderby short-item" aria-label="Shop order" id="sortby">
                    <option value="">{{ __('Sort') }}</option>
                    <option value="date_desc">{{ __('Most recent') }}</option>
                    <option value="date_asc">{{ __('Oldest Product') }}</option>
                    <option value="price_asc">{{ __('Low to High') }}</option>
                    <option value="price_desc">{{ __('High to Low') }}</option>
                </select>
                @if ($gs->product_page != null)
                    <select id="pageby" name="pageby" class="short-itemby-no">
                        @foreach (explode(',', $gs->product_page) as $element)
                            <option value="{{ $element }}">{{ $element }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="hidden" id="pageby" name="paged" value="{{ $gs->page_count }}">
                    <input type="hidden" name="shop-page-layout" value="left-sidebar">
                @endif
            </form>
        </div>
        <!-- <div class="products-view">
          <a  class="grid-view check_view" data-shopview="grid-view" href="javascript:;"><i class="flaticon-menu-1 flat-mini"></i></a>
          <a class="list-view check_view" data-shopview="list-view" href="javascript:;"><i class="flaticon-list flat-mini"></i></a>
       </div> -->
    </div>

</div>
    </div>
</div>
<div class="col-xl-3">
    <div id="sidebar" class="widget-title-bordered-full">
        <div class="dashbaord-sidebar-close d-xl-none">
            <i class="fas fa-times"></i>
        </div>
        <!-- <div class="products-header-left d-flex align-items-center justify-content-between mb-5">
            <div class="woocommerce-result-count"></div>
        </div> -->
        <form
            id="catalogForm"
            action="{{ route('front.category', [
                Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}"
            method="GET"
        >

        <div
            id="woocommerce_product_categories-4"
            class="widget woocommerce widget_product_categories widget-toggle"
        >
            <ul class="product-categories category-listing">
                <li class="list cat-item cat-parent {{ Request::url() == url('/category') ? 'active':'' }}">
                    <a href="{{url('/category')}}"><strong>All Categories</strong></a>
                    <i class="cross-icon" aria-hidden="true"></i>
                </li>
                @foreach ($categories as $category)
                
                    <li
                        class="list cat-item cat-parent
                        {{ Request::url() == route('front.category', $category->slug) ? 'active':'' }}">
                        <a
                            href="{{route('front.category', $category->slug)}}
                            {{!empty(request()->input('search')) ? '?search='.request()->input('search') : ''}}"
                            class="category-link"
                            id="cat"
                        >{{ $category->name }} <span class="count"></span></a>

                        @if($category->subs_count > 0)
                            <span class="has-child"></span>
                            <ul class="children">
                                @foreach ($category->subs()->get() as $subcategory)
                                <li class="cat-item cat-parent">
                                    <a
                                        href="{{route('front.category', [
                                            $category->slug, $subcategory->slug
                                        ])}}{{!empty(request()->input('search')) ? '
                                        ?search='.request()->input('search') : ''}}"
                                        class="category-link {{ isset($subcat) ? ($subcat->id == $subcategory->id ? 'active' : '') : '' }}"
                                    >{{$subcategory->name}} <span class="count"></span></a>

                                    @if($subcategory->childs->count()!=0)
                                        <span class="has-child"></span>
                                        <ul class="children">
                                            @foreach ($subcategory->childs()->get() as $key => $childelement)
                                            <li class="cat-item ">
                                                <a href="{{route('front.category', [
                                                    $category->slug, $subcategory->slug, $childelement->slug
                                                ])}}{{!empty(request()->input('search')) ? '?search='.request()->input('search') : ''}}" class="category-link {{ isset($childcat) ? ($childcat->id == $childelement->id ? 'active' : '') : '' }}"> {{$childelement->name}} <span class="count"></span></a>
                                            </li>
                                            @endforeach
                                        </ul>

                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>





