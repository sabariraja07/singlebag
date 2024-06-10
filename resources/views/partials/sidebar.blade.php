<!-- BEGIN: Main Menu-->
@php
      $shop_info = \App\Models\Shop::where('id', current_shop_id())->first();
      $image = current_shop_logo_url();
@endphp
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
                @if (current_shop_id() != 0)
                @if(current_shop_type() != 'supplier')
                    <a class="navbar-brand" href="{{ url('/') }}">
                @else
                    <a class="navbar-brand" href="{{ url('#') }}">
                @endif
                    <span class="brand-logo">
                        <img src="{{ current_shop_logo_url() }}" onerror="this.src='./images/shop1.png'" style="max-height: 36px;" alt="Logo">
                    </span>
                    <h2 class="brand-text">{{ $shop_info->name ?? env('APP_NAME') }}</h2>
                </a>
                @else
                <a class="navbar-brand" href="{{ url('/') }}">
                    <span class="brand-logo">
                        <img src="{{ admin_logo_url() }}" onerror="this.src='./images/shop1.png'" alt="Logo" >
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
                <a class="d-flex align-items-center" href="{{ url('/seller/dashboard') }}">
                    <i class="ph-gauge-bold"></i>
                    <span class="menu-title text-truncate">{{ __('Dashboard') }}</span>
                </a>
            </li>
            <li class="nav-item has-sub {{ Request::is('seller/orders*')  || Request::is('seller.transaction.index') ? 'sidebar-group-active open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <i class="ph-notepad-bold"></i>
                    <span class="menu-title text-truncate">{{ __('Orders') }}</span></a>
                <ul class="menu-content">
                    <li class="{{ Request::is('seller/orders/all') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ url('seller/orders/all') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('All Orders') }}</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('seller/orders/canceled') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ url('seller/orders/canceled') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Cancelled') }}</span>
                        </a>
                    </li>
                    @if(current_shop_type() == 'seller')
                    <li class="{{ request()->routeIs('seller.transaction.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.transaction.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Transactions') }}</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            <li class="nav-item has-sub {{ request()->routeIs('seller.products.index') ||
                request()->routeIs('seller.inventory.index') ||
                request()->routeIs('seller.category.index') ||
                request()->routeIs('seller.attribute.index') ||
                request()->routeIs('seller.option.index') ||
                request()->routeIs('seller.brand.index') ||
                request()->routeIs('seller.coupon.index') ? 'sidebar-group-active open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <i class="ph-package-bold"></i>
                    <span class="menu-title text-truncate">{{ __('Products') }}</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->routeIs('seller.products.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.products.index') }}">
                            <i class="ph-circle-bold"></i>
                            {{ __('All Products') }}
                        </a>
                    </li>
                    @if (isset($plan_limit))
                        <li class="{{ request()->routeIs('seller.inventory.index') ? 'active' : '' }}">
                            <a class="d-flex align-items-center @if(filter_var($plan_limit['inventory']) != true) btn disabled @endif"
                                @if (filter_var($plan_limit['inventory']) == true) href="{{ route('seller.inventory.index') }}" @endif>
                                <i class="ph-circle-bold"></i>
                                {{ __('Inventory') }}&nbsp;
                                @if (filter_var($plan_limit['inventory']) != true)
                                    <i data-feather='lock' style=" color: red;"></i>
                                @endif
                            </a>
                        </li>
                    @endif
                    <li class="{{ request()->routeIs('seller.category.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.category.index') }}"><i class="ph-circle-bold"></i>
                            {{ __('Categories') }}
                        </a>
                    </li>
                    @if(current_shop_type() != 'reseller')
                    <li class="{{ request()->routeIs('seller.attribute.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.attribute.index') }}"><i class="ph-circle-bold"></i>
                            {{ __('Attributes') }}
                        </a>
                    </li>
                    @endif
                    @if(current_shop_type() != 'reseller')
                    <li class="{{ request()->routeIs('seller.option.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.option.index') }}"><i class="ph-circle-bold"></i>
                            {{ __('Product Option') }}
                        </a>
                    </li>
                    @endif
                    <li class="{{ request()->routeIs('seller.brand.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.brand.index') }}"><i class="ph-circle-bold"></i>
                            {{ __('Brands') }}
                        </a>
                    </li>
                    @if(current_shop_type() == 'seller')
                    <li class="{{ request()->routeIs('seller.coupon.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.coupon.index') }}">
                            <i class="ph-circle-bold"></i>
                            {{ __('Coupons') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
          
            <li class="nav-item {{ request()->routeIs('seller.report.index') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('seller.report.index') }}">
                    <i data-feather='briefcase'></i>
                    <span class="menu-title text-truncate">{{ __('Analytics') }}</span>
                </a>
            </li>
            @if(current_shop_type() == 'seller')
            <li class="nav-item {{ request()->routeIs('seller.shops.index') || request()->routeIs('admin.shop.create') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ url('/seller/shops') }}">
                    <i class="ph-storefront-bold"></i>
                    <span class="menu-title text-truncate">{{ __('Shops') }}</span>
                </a>
            </li>
            @endif
            @if(current_shop_type() == 'reseller')
            <li
            class="nav-item has-sub {{ request()->routeIs('seller.supplier.list') || request()->routeIs('seller.supplier.products') ? 'sidebar-group-active open' : '' }}">
            <a class="d-flex align-items-center" href="#">
                <i class="ph-users-bold"></i>
                <span class="menu-title text-truncate">{{ __('Suppliers') }}</span>
            </a>
            <ul class="menu-content">
                <li class="{{ request()->routeIs('seller.supplier.list') ? 'active' : '' }}">
                    <a class="d-flex align-items-center"
                        href="{{ route('seller.supplier.list') }}">
                        <i class="ph-circle-bold"></i>
                        <span class="menu-item text-truncate">{{ __('Supplier List') }}</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('seller.supplier.products') ? 'active' : '' }}">
                    <a class="d-flex align-items-center"
                        href="{{ route('seller.supplier.products') }}">
                        <i class="ph-circle-bold"></i>
                        <span class="menu-item text-truncate">{{ __('Products') }}</span>
                    </a>
                </li>
            </ul>
          </li>
            {{-- <li class="nav-item {{ request()->routeIs('seller.supplier.list') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('seller.supplier.list') }}">
                    <i class="ph-users-bold"></i>
                    <span class="menu-title text-truncate">{{ __('Suppliers') }}</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('seller.supplier.products') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('seller.supplier.products') }}">
                    <i class="ph-users-bold"></i>
                    <span class="menu-title text-truncate">{{ __('All Products') }}</span>
                </a>
            </li> --}}
            @endif
            @if(current_shop_type() == 'reseller' || current_shop_type() == 'supplier')
            <li class="nav-item {{ request()->routeIs('seller.settlements*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('seller.settlements.index') }}">
                    <i class="ph-money-bold"></i>
                    <span class="menu-title text-truncate">{{ __('Settlements') }}</span>
                </a>
            </li>
            @endif
            @if(current_shop_type() != 'reseller')
            <li
                class="nav-item has-sub {{ request()->routeIs('seller.customer.index') || request()->routeIs('seller.review.index') ? 'sidebar-group-active open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <i class="ph-users-bold"></i>
                    <span class="menu-title text-truncate">{{ __('Customers') }}</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->routeIs('seller.customer.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.customer.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('All Customers') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('seller.review.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.review.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Review & Ratings') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            @if(current_shop_type() != 'reseller')
            <li
                class="nav-item has-sub {{ request()->routeIs('seller.delivery.index') ? 'sidebar-group-active open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <i class="ph-truck-bold"></i>
                    <span class="menu-title text-truncate">{{ __('Delivery Agents') }}</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->routeIs('seller.delivery.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.delivery.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('All Agents') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            <li class="nav-item has-sub {{ Request::is('seller/settings*') || 
                Request::is('seller/payment*') || 
                request()->routeIs('seller.shipping-methods*') ||
                request()->routeIs('seller.user.term')  ? 'sidebar-group-active open' : '' ||
                request()->routeIs('seller.domain.index*') }}">
                <a class="d-flex align-items-center" href="#">
                    <i class="ph-gear-bold"></i>
                    <span class="menu-title text-truncate" data-i18n="Settings">
                        {{ __('Settings') }}</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ Request::is('seller/settings/shop-settings') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.settings.show', 'shop-settings') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Store Settings') }}</span>
                        </a>
                    </li>
                    @if(current_shop_type() == 'seller')
                    <li class="{{ Request::is('seller/settings/payment') || Request::is('seller/payment*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.settings.show', 'payment') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Payment Options') }}</span>
                        </a>
                    </li>
                    @endif
                    @if(current_shop_type() != 'reseller')
                    <li class="{{ request()->routeIs('seller.shipping-methods*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.shipping-methods.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Shipping Method') }}</span>
                        </a>
                    </li>
                    {{-- <li class="{{ request()->routeIs('seller.shipping.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.shipping.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Shipping Method') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('seller.location.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.location.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Shipping Location') }}</span>
                        </a>
                    </li> --}}
                    @endif
                    @if(current_shop_type() != 'supplier')
                    <li class="{{ Request::is('seller/settings/plan') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.settings.show', 'plan') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Plans') }}</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('seller/settings/subscriptions') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.settings.show', 'subscriptions') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Subscription History') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('seller.user.term') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.user.term') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('User Terms') }}</span>
                        </a>
                    </li>
                    @if (isset($plan_limit))
                        @if (filter_var($plan_limit['custom_domain']) == true)
                            @if (domain_info('custom_domain') == true)
                                <li class="{{ Request::is('seller/setting/domain') ? 'active' : '' }}">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('seller.domain.index') }}">
                                        <i class="ph-circle-bold"></i>
                                        <span class="menu-item text-truncate">{{ __('Domain Settings') }}</span>
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="btn d-flex align-items-center disabled">
                                    <i class="ph-handbag-bold"></i>
                                    <span class="menu-title text-truncate">{{ __('Domain Settings') }}</span>
                                        &nbsp;
                                            <i data-feather='lock' style="color: red;width: 12px;"></i>

                                </a>
                            </li>                            
                        @endif
                    @endif
                    <li class="{{ request()->routeIs('seller.tax_classes.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.tax_classes.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Tax Class') }}</span>
                        </a>
                    </li>
                     {{-- <li class="{{ request()->routeIs('seller.tax_rate.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.tax_rate.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Tax Rate') }}</span>
                        </a>
                    </li> --}}
                    <li class="{{ request()->routeIs('seller.shipping_location.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.shipping_location.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Shipping Location') }}</span>
                        </a>
                    </li>
                    
                @endif
                </ul>
            </li>
            @if(current_shop_type() != 'supplier')
            <li class="{{ request()->routeIs('seller.marketing.*') ? 'active' : '' }}">
                <a class="d-flex align-items-center"
                    href="{{ route('seller.marketing.show', 'google-analytics') }}">
                    <i class="ph-facebook-logo-bold"></i>
                    <span class="menu-item text-truncate">{{ __('Marketing Tools') }}</span>
                </a>
            </li>
            {{-- @if(current_shop_type() != 'supplier')
            <li
                class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather='tool'></i>
                    <span class="menu-title text-truncate">{{ __('Business Tools') }}</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->routeIs('seller.privacy-policy-generator.create') ? 'active' : '' }} {{ request()->routeIs('seller.privacy-policy-generator.show') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('seller.privacy-policy-generator.create') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Privacy Policy') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('seller.terms-generator.create') ? 'active' : '' }} {{ request()->routeIs('seller.terms-generator.show') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('seller.terms-generator.create') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Terms & Condition') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('seller.refund-generator.create') ? 'active' : '' }} {{ request()->routeIs('seller.refund-generator.show') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('seller.refund-generator.create') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate" style="font-size: 14px;">{{ __('Return/Refund Policy') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('seller.qr-generator.create') ? 'active' : '' }} {{ request()->routeIs('seller.qr-generator.show') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('seller.qr-generator.create') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('QR Code') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('seller.invoice-generator.create') ? 'active' : '' }} {{ request()->routeIs('seller.invoice-generator.show') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('seller.invoice-generator.create') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Invoice Generator') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif --}}
            {{-- @else
            <li class="{{ request()->routeIs('seller.marketing.*') ? 'active' : '' }}">
                <a class="d-flex align-items-center"
                    href="{{ route('seller.marketing.show', 'whatsapp') }}">
                    <i class="ph-facebook-logo-bold"></i>
                    <span class="menu-item text-truncate">{{ __('Marketing Tools') }}</span>
                </a>
            </li>             --}}
            @endif
            @if(current_shop_type() != 'supplier')
            <li class="navigation-header">
                <span>{{ __('SALES CHANNELS') }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-more-horizontal">
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="19" cy="12" r="1"></circle>
                    <circle cx="5" cy="12" r="1"></circle>
                </svg>
            </li>
            
            @endif
            @if(current_shop_type() == 'seller')
                @if (isset($plan_limit))
                    @if (filter_var($plan_limit['pos']) == true)
                        <li
                            class="nav-item has-sub {{ request()->routeIs('seller.users.index') ? 'sidebar-group-active open' : '' }} {{ request()->routeIs('seller.order.create') ? 'sidebar-group-active open' : '' }} {{ request()->routeIs('seller.products.pos') ? 'sidebar-group-active open' : '' }} ">
                            <a class="d-flex align-items-center" href="#">
                                <i class="ph-handbag-bold"></i>
                                <span class="menu-title text-truncate">
                                    {{ __('Point Of Sales') }}</span></a>
                            <ul class="menu-content">
                                <li class="{{ request()->routeIs('seller.order.create') ? 'active' : '' }}">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('seller.order.create') }}">
                                        <i class="ph-circle-bold"></i>
                                        <span class="menu-item text-truncate">{{ __('Panel') }}</span>
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('seller.users.index') ? 'active' : '' }}">
                                    <a class="d-flex align-items-center"
                                        href="{{ route('seller.users.index') }}">
                                        <i class="ph-circle-bold"></i>
                                        <span class="menu-item text-truncate">{{ __('Users') }}</span>
                                    </a>
                                </li>
                            
                                    <li class="{{ request()->routeIs('seller.products.pos') ? 'active' : '' }}">
                                        <a class="d-flex align-items-center"
                                            href="{{ route('seller.products.pos') }}">
                                            <i class="ph-circle-bold"></i>
                                            <span class="menu-item text-truncate">{{ __('POS Products') }}</span>
                                        </a>
                                    </li>

                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="btn d-flex align-items-center disabled">
                                <i class="ph-handbag-bold"></i>
                                <span class="menu-title text-truncate">{{ __('Point Of Sales') }}</span>
                                    &nbsp;
                                    @if (filter_var($plan_limit['pos']) != true)
                                        <i data-feather='lock' style="color: red;width: 12px;"></i>
                                    @endif
                            </a>
                        </li>
                    @endif
                @endif
            @endif
            @if(current_shop_type() != 'supplier')
            <li
                class="nav-item has-sub 
                {{ 
                request()->routeIs('seller.bump-ads.index') ||
                request()->routeIs('seller.menu.index') ||
                request()->routeIs('seller.theme.index') ||
                request()->routeIs('seller.slider.index') ||
                request()->routeIs('seller.banner-ads.index') ||
                request()->routeIs('seller.seo.index') ||
                request()->routeIs('seller.page.index') ? 'sidebar-group-active open' : '' }} ">
                <a class="d-flex align-items-center" href="#">
                    <i class="ph-storefront-bold"></i>
                    <span class="menu-title text-truncate">{{ __('Online store') }}</span></a>
                <ul class="menu-content">
                    <li class="{{ request()->routeIs('seller.theme.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.theme.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Themes') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('seller.slider.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.slider.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Sliders') }}</span></a>
                    </li>
                    <li class="{{ request()->routeIs('seller.bump-ads.index') ? 'active' : '' }}"><a class="d-flex align-items-center"
                            href="{{ route('seller.bump-ads.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Bump Ads') }}</span></a>
                    </li>
                    <li class="{{ request()->routeIs('seller.banner-ads.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.banner-ads.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Banner Ads') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('seller.menu.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center"
                            href="{{ route('seller.menu.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Menus') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('seller.seo.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('seller.seo.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('SEO') }}</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('seller.page.index') ? 'active' : '' }}"">
                        <a class="d-flex align-items-center" href="{{ route('seller.page.index') }}">
                            <i class="ph-circle-bold"></i>
                            <span class="menu-item text-truncate">{{ __('Pages') }}</span>
                        </a>
                    </li>
                </ul>
                
            </li>
            @endif
        </ul>
        <ul class="navigation navigation-main">
            @if(current_shop_type() != 'supplier')
                <li>
                    <a class="d-flex align-items-center dt-button btn btn-success waves-effect waves-float waves-light" href="{{ url('/') }}">
                        <i class="ph-globe-bold"></i>
                        <span class="menu-title text-truncate">{{ __('Visit Store') }}</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
