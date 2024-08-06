<div class="dashboard-overlay">&nbsp;</div>
<div id="sidebar" class="sidebar-blog bg-light p-30">
    <div class="dashbaord-sidebar-close d-xl-none">
        <i class="fas fa-times"></i>
    </div>
    @if (Auth::check() && Auth::user()->email_verified == 'No')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Alert :</strong> Email not verified.
        </div>
    @endif

    <div class="widget border-0 py-0 widget_categories">
        <h4 class="widget-title down-line">{{ __('Dashboard') }}</h4>
        <ul>
            <li class="">
                <a class="{{ Request::url() == route('user-dashboard') ? 'active' : '' }}"
                    href="{{route('user-dashboard')}}"
				>{{ __('Dashboard') }}</a>
            </li>
            {{-- <li class="">
                <a class="" href="javascript:void(0)">{{ __('Quotes & Invoices') }}</a>
            </li> --}}
			<li class="">
                <a class="{{ Request::url() == route('user-orders') ? 'active' : '' }}"
                    href="{{ route('user-orders') }}"
				>{{ __('Orders') }}</a>
            </li>
            <li class="">
                <a
                    class="{{ Request::url() == route('user-designs.index') ? 'active' : '' }}"
                    href="{{route('user-designs.index')}}"
                >{{ __('My Designs') }}</a>
            </li>
            {{-- <li class="">
				<a class="" href="javascript:void(0)">{{ __('My Uploads') }}</a>
			</li> --}}
            <li class="">
				<a
					class="{{ Request::url() == route('user-profile') ? 'active' : '' }}"
					href="{{ route('user-profile') }}"
				>{{ __('Edit Profile/Addresses') }}</a>
			</li>
            <li class=""><a class="{{ Request::url() == route('user-reset') ? 'active' : '' }}"
                    href="{{ route('user-reset') }}">{{ __('Reset Password') }}</a></li>
            <li class=""><a class="" href="{{ route('user-logout') }}">{{ __('Logout') }}</a></li>



        </ul>
    </div>

</div>
