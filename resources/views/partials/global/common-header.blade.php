 <!--==================== Header Section Start ====================-->
 <header class="ecommerce-header">
     <div class="topbar">
         <div class="container">
             <i class="icon-bus"></i>
             <span class="shipping-text">FREE EXPRESS SHIPPING to the US, Canada and Worldwide</span>
         </div>
     </div>
     <div class="navigation">
         <div class="container">
             <div class="row align-items-center justify-content-between w-100">
                 <div class="col-3 col-md-6 col-xl-7 col-lg-9">
                     <button class="hamburger" type="button">
                         <span class="bar"></span>
                         <span class="bar"></span>
                         <span class="bar"></span>
                     </button>
                     <nav class="navbar">

                         <!-- <div class="navbar"> -->
                         <ul class="navbar-nav">
                             <li class="nav-item {{ request()->path() == '/' ? 'active' : '' }}">
                                 <a class="nav-link" href="{{ route('front.index') }}">{{ __('HOME') }}</a>
                             </li>

                             <li class="nav-item  my-account-dropdown">
                                 <a class="nav-link has-dropdown d-flex align-items-center text-dark text-decoration-none"
                                     href="{{ url('category') }}">{{ __('PRODUCTS') }}</a>
                                 <i class="arrow-up" aria-hidden="true"></i>
                                 <div class="left-product">
                                     <ul>
                                         <li
                                             class="list cat-item cat-parent {{ Request::url() == url('/category') ? 'active' : '' }}">
                                             <a href="{{ url('/category') }}"><strong>All Categories</strong></a>
                                         </li>

                                         @foreach (Helper::categories() as $category)
                                             <li
                                                 class="list cat-item cat-parent
                                                {{ Request::url() == route('front.category', $category->slug) ? 'active' : '' }}">
                                                 <a href="{{ route('front.category', $category->slug) }}
                                                    {{ !empty(request()->input('search')) ? '?search=' . request()->input('search') : '' }}"
                                                     class="category-link" id="cat">{{ $category->name }} <span
                                                         class="count"></span></a>
                                             </li>
                                         @endforeach

                                     </ul>
                                 </div>
                             </li>

                             <li class="nav-item {{ request()->path() == 'blog' ? 'active' : '' }}">
                                 <a class="nav-link" href="{{ url('category') }}">{{ __('DESIGN NOW') }}</a>
                             </li>
                         </ul>
                     </nav>
                 </div>
                 <div class="col-9 col-md-6 col-xl-5 col-lg-3 cart-common-header">
                    @include('frontend._assets._commonHeader')
                 </div>
                 <a class="navbar-brand" href="{{ route('front.index') }}">
                     <img class="nav-logo lazy" src="{{ asset('assets/images/' . $gs->logo) }}"
                         alt="Img not found !"></a>
             </div>
         </div>
     </div>

 </header>
 <!--==================== Header Section End ====================-->
