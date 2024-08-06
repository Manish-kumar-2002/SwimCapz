<li>
    <a href="#menu5" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="icofont-cart"></i>{{ __('Category Management') }}
    </a>
    <ul class="collapse list-unstyled" id="menu5" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-cat-index') }}"><span>{{ __('All Category') }}</span></a>
        </li>
        <li>
            <a href="{{ route('price-structure.index') }}"><span>{{ __('Price Structure') }}</span></a>
        </li>
    </ul>
</li>
<li>
    <a href="#menu2" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="icofont-cart"></i>{{ __('Product Management') }}
    </a>
    <ul class="collapse list-unstyled" id="menu2" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-prod-index') }}"><span>{{ __('All Product') }}</span></a>
        </li>
        {{-- <li>
            <a href="{{ route('admin-prod-deactive') }}"><span>{{ __('Deactivated Product') }}</span></a>
        </li> --}}
        <!-- <li>
            <a href="{{ route('admin-prod-catalog-index') }}"><span>{{ __('Product Catalogs') }}</span></a>
        </li> -->

       
    </ul>
</li>

<li>
    <a href="{{ route('admin-coupon-index') }}" class=" wave-effect"><i
            class="fas fa-percentage"></i>{{ __('Coupon Management') }}</a>
</li>

<li>
    <a href="#menu3" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="icofont-user"></i>{{ __('User Management') }}
    </a>
    <ul class="collapse list-unstyled" id="menu3" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-user-index') }}"><span>{{ __('All User') }}</span></a>
        </li>
        <!-- <li>
            <a href="{{ route('admin-withdraw-index') }}"><span>{{ __('Withdraws') }}</span></a>
        </li> -->
        <!-- <li>
            <a href="{{ route('admin-user-image') }}"><span>{{ __('Customer Default Image') }}</span></a>
        </li> -->
    </ul>
</li>

<li>
    <a
        href="#order"
        class="accordion-toggle wave-effect"
        data-toggle="collapse"
        aria-expanded="true"><i
        class="fas fa-hand-holding-usd"
    ></i>{{ __('Order Management') }}</a>

    <ul class="collapse list-unstyled" id="order" data-parent="#accordion">
        <li>
            <a
                href="{{ route('admin-orders-all') }}"
            >{{ __('All Orders') }}</a>
        </li>
        @foreach (Helper::getOrderStatus() as $key => $row)
            <li>
                <a
                    href="{{$row['url']}}"
                >{{ __($row['text']) }}</a>
            </li>
        @endforeach
    </ul>
</li>
<li>
    <a href="#general" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-cogs"></i>{{ __('General Settings') }}
    </a>
    <ul class="collapse list-unstyled" id="general" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-gs-logo') }}"><span>{{ __('Update Logo') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-gs-fav') }}"><span>{{ __('Update Favicon') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-gs-load') }}"><span>{{ __('Update Loader') }}</span></a>
        </li>
        <!-- <li>
            <a href="{{ route('admin-shipping-index') }}"><span>{{ __('Shipping Methods') }}</span></a>
        </li> -->
        <!-- <li>
            <a href="{{ route('admin-package-index') }}"><span>{{ __('Packagings') }}</span></a>
        </li> -->
        <!-- <li>
            <a href="{{ route('admin-pick-index') }}"><span>{{ __('Pickup Locations') }}</span></a>
        </li> -->
        <li>
            <a href="{{ route('admin-gs-contents') }}"><span>{{ __('Captcha Settings') }}</span></a>
        </li>
        <!-- <li>
            <a href="{{ route('admin-gs-affilate') }}"><span>{{ __('Affiliate Program') }}</span></a>
        </li> -->
        <!-- <li>
            <a href="{{ route('admin-gs-popup') }}"><span>{{ __('Popup Banner') }}</span></a>
        </li> -->
        <!-- <li>
            <a href="{{ route('admin-gs-bread') }}"><span>{{ __('Breadcrumb Banner') }}</span></a>
        </li> -->

        <!-- <li>
            <a href="{{ route('admin-gs-error-banner') }}"><span>{{ __('Error Banner') }}</span></a>
        </li> -->
        <li>
            <a href="{{ route('admin-gs-maintenance') }}"><span>{{ __('Maintenance Page') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-user-image') }}"><span>{{ __('Customer Default Image') }}</span></a>
        </li>
        <li>
            <a href="{{ route('ttf.index') }}">
            {{ __('Update Font Files') }}</a>
        </li>
        <li>
            <a href="{{ route('colors.index') }}">
            {{ __('Update Font Color') }}</a>
        </li>
    </ul>
</li>

<li>
    <a href="#menu" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-file-code"></i>{{ __('Content Management') }}
    </a>
    <ul class="collapse list-unstyled" id="menu" data-parent="#accordion">
        <!-- <li>
            <a href="{{ route('admin-faq-index') }}"><span>{{ __('FAQ Page') }}</span></a>
        </li> -->
        <li>
            <a href="{{ route('admin-ps-contact') }}"><span>{{ __('Contact Us Page') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-gs-prod-settings') }}"><span>{{ __('Product Settings') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-page-index') }}"><span>{{ __('Other Pages') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-featured-index') }}"><span>{{ __('Featured') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-testimonial-index') }}"><span>{{ __('Testimonials') }}</span></a>
        </li>
       
        <li>
            <a href="{{ route('admin-gallery-index') }}"><span>{{ __('Gallery') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-aboutus-edit', 1) }}"><span>{{ __('About Us') }}</span></a>
        </li>
        <!-- <li>
            <a href="{{ route('admin-ps-page-banner') }}"><span>{{ __('Other Page Banner') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-ps-menu-links') }}"><span>{{ __('Customize Menu Links') }}</span></a>
        </li> -->
        <li>
            <a href="{{ route('admin-cblog-index') }}"><span>{{ __('Blog Categories') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-blog-index') }}"><span>{{ __('Blog Posts') }}</span></a>
        </li>
        <li>
            <a href="{{ route('requested-quotes.index') }}"><span>{{ __('Requested Quotes') }}</span></a>
        </li>
        <li>
            <a href="{{ route('reported-problems.index') }}">
                <span>{{ __('Reported Problems') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin-subs-index') }}">
            {{ __('Subscribers') }}</a>
        </li>
    </ul>
</li>

<!-- <li>
    <a href="#blog" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-fw fa-newspaper"></i>{{ __('Blog') }}
    </a>
    <ul class="collapse list-unstyled" id="blog" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-cblog-index') }}"><span>{{ __('Categories') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-blog-index') }}"><span>{{ __('Posts') }}</span></a>
        </li>
        {{-- <li>
            <a href="{{ route('admin-gs-blog-settings') }}"><span>{{ __('Blog Settings') }}</span></a>
        </li> --}}
    </ul>
</li> -->

<!-- <li>
    <a href="#other" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-fw fa-newspaper"></i>{{ __('Others') }}
    </a>
    <ul class="collapse list-unstyled" id="other" data-parent="#accordion">
        <li>
            <a href="{{ route('requested-quotes.index') }}"><span>{{ __('Requested Quotes') }}</span></a>
        </li>
        <li>
            <a href="{{ route('reported-problems.index') }}">
                <span>{{ __('Reported Problems') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin-subs-index') }}">
            {{ __('Subscribers') }}</a>
        </li>
        
    </ul>
</li> -->

<!-- <li>
    <a href="#homepage" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-edit"></i>{{ __('Home Page Settings') }}
    </a>
    <ul class="collapse list-unstyled" id="homepage" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-sl-index') }}"><span>{{ __('Sliders') }}</span></a>
        </li>

        <li>
            <a href="{{ route('admin-arrival-index') }}"><span>{{ __('Arrival Section') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-ps-deal') }}"><span>{{ __('Deal of the day') }}</span></a>
        </li>

        <li>
            <a href="{{ route('admin-service-index') }}"><span>{{ __('Services') }}</span></a>
        </li>


        <li>
            <a href="{{ route('admin-partner-index') }}"><span>{{ __('Partners') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-ps-customize') }}"><span>{{ __('Home Page Customization') }}</span></a>
        </li>
    </ul>
</li> 
<li>
    <a href="#emails" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-at"></i>{{ __('Email Settings') }}
    </a>
    <ul class="collapse list-unstyled" id="emails" data-parent="#accordion">
        <li><a href="{{ route('admin-mail-index') }}"><span>{{ __('Email Template') }}</span></a></li>
        <li><a href="{{ route('admin-mail-config') }}"><span>{{ __('Email Configurations') }}</span></a></li>
        <li><a href="{{ route('admin-group-show') }}"><span>{{ __('Group Email') }}</span></a></li>
    </ul>
</li> -->
<li>
    <a href="#payments" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-file-code"></i>{{ __('Payment History') }}
    </a>
    <ul class="collapse list-unstyled" id="payments" data-parent="#accordion">
        <!-- <li><a href="{{ route('admin-gs-payments') }}"><span>{{ __('Payment Information') }}</span></a></li>
        <li><a href="{{ route('admin-payment-index') }}"><span>{{ __('Payment Gateways') }}</span></a></li> -->
        <li><a href="{{ route('admin-currency-index') }}"><span>{{ __('Currencies') }}</span></a></li>
        <!-- <li><a href="{{ route('admin-reward-index') }}"><span>{{ __('Reward Information') }}</span></a></li> -->
    </ul>
</li>

<li>
    <a href="#socials" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-paper-plane"></i>{{ __('Social Settings') }}
    </a>
    <ul class="collapse list-unstyled" id="socials" data-parent="#accordion">
            <li><a href="{{ route('admin-sociallink-index') }}"><span>{{ __('Social Links') }}</span></a></li>
            <li>
            <a href="{{ route('admin-youtube-edit', 1) }}"><span>{{ __('Youtube Link') }}</span></a>
        </li>
            {{-- <li><a href="{{ route('admin-social-facebook') }}"><span>{{ __('Facebook Login') }}</span></a></li>
            <li><a href="{{ route('admin-social-google') }}"><span>{{ __('Google Login') }}</span></a></li> --}}
    </ul>
</li>

<!-- <li>
    <a href="#seoTools" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-wrench"></i>{{ __('SEO Tools') }}
    </a>
    <ul class="collapse list-unstyled" id="seoTools" data-parent="#accordion">
        <li>
            <a href="{{ route('admin-prod-popular', 30) }}"><span>{{ __('Popular Products') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-seotool-analytics') }}"><span>{{ __('Google Analytics') }}</span></a>
        </li>
        <li>
            <a href="{{ route('admin-seotool-keywords') }}"><span>{{ __('Website Meta Keywords') }}</span></a>
        </li>
    </ul>
</li> -->

<!-- <li>
    <a href="{{ route('admin-staff-index') }}" class=" wave-effect"><i class="fas fa-user-secret"></i>{{ __('Manage Staffs') }}</a>
</li> -->

<!-- <li>
    <a href="{{ route('admin-role-index') }}" class=" wave-effect"><i class="fas fa-user-tag"></i>{{ __('Manage Roles') }}</a>
</li> -->

<!-- <li>
    <a href="{{ route('admin-cache-clear') }}" class=" wave-effect"><i class="fas fa-sync"></i>{{ __('Clear Cache') }}</a>
</li> -->

<!-- <li>
    <a href="{{ route('admin-addon-index') }}" class=" wave-effect"><i class="fas fa-list-alt"></i>{{ __('Addon Manager') }}</a>
</li> -->

<!-- <li>
    <a href="#sactive" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-cog"></i>{{ __('System Activation') }}
    </a>
    <ul class="collapse list-unstyled" id="sactive" data-parent="#accordion">

        <li><a href="{{ route('admin-activation-form') }}"> {{ __('Activation') }}</a></li>
        <li><a href="{{ route('admin-generate-backup') }}"> {{ __('Generate Backup') }}</a></li>
    </ul>
</li> -->
