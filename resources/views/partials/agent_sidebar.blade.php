<!-- BEGIN: Main Menu-->
@php
      $shop_info = \App\Models\Shop::where('id', current_shop_id())->first();
      $image = public_path() . '/uploads/' . current_shop_id() . '/logo.png';
      $agent = Auth::guard('customer')->user();
@endphp
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
                @if (current_shop_id() != 0)
                <a class="navbar-brand" href="{{ url('/seller/agent/dashboard') }}">
                    <span class="brand-logo">
                        <img src="{{ asset('uploads/' . current_shop_id() . '/logo.png') }}" onerror="this.src='./images/shop1.png'" style="max-height: 36px;" alt="Logo">
                    </span>
                    <h2 class="brand-text">{{ $agent->first_name ?? env('APP_NAME') }}</h2>
                </a>
                @else
                <a class="navbar-brand" href="{{ url('/') }}">
                    <span class="brand-logo">
                        <img src="{{ asset('uploads/logo.png') }}" onerror="this.src='./images/shop1.png'" alt="Logo" >
                    </span>
                    <h2 class="brand-text">{{ env('APP_NAME') }}</h2>
                </a>
                @endif
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse">
                    <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                    <i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        @php
            $plan_limit = user_limit();
        @endphp
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item">
                <a class="d-flex align-items-center" href="{{ url('/seller/agent/dashboard') }}">
                    <i class="ph-gauge-bold"></i>
                    <span class="menu-title text-truncate">{{ __('Dashboard') }}</span>
                </a>
            </li>
            <li class="nav-item has-sub {{ Request::is('agent_orders') ? 'sidebar-group-active open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <i class="ph-notepad-bold"></i>
                    <span class="menu-title text-truncate">{{ __('Orders') }}</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('seller/agent/orders') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ url('seller/agent/orders') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('All Orders') }}</span>
                        </a>
                    </li>
                   
                   
                </ul>
            </li>
           
        </ul>
        
    </div>
</div>
