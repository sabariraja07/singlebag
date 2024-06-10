@php
$location = Cache::get(domain_info('shop_id') . 'location', '');
$location = json_decode($location, true);
$menu_items = CollapseAbleMenuItems('header');
$locations = \App\Models\Category::where('shop_id', domain_info('shop_id'))
    ->where('type', 'city')
    ->with('child_relation')
    ->get();
@endphp
<header class="header-area section-padding-1 transparent-bar">
    <div class="header-large-device">
        <div class="header-top header-top-ptb-1">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        {{-- <div class="header-contact-number">
                            <span>phone</span>
                        </div> --}}
                    </div>
                    <div class="col-lg-6">
                        <div class="header-top-right header-top-flex" style=" font-size: 13px; ">
                            Track Your Order &nbsp;
                            <form method="post" action="/order_track" id="order_track">
                                @csrf
                                <li style=" margin-right: 10px; display:flex;border: 1px solid;">
                                    <input type="text" name="order_no" id="track_order" autocomplete="off" placeholder="Enter Order ID" style=" border: none;height: 26px;background: white;">&nbsp;
                                    <span class="fa fa-search" onclick='$("#order_track").submit();' style="font-size:20px;background: white;padding: 3px;"></span>
                                </li>
                            </form> | &nbsp;&nbsp;
                            <div class="language-wrap">
                                @php
                                $languages = get_shop_languages();
                                $locale = collect($languages)->filter(function($item) {
                                                return $item['code'] == app()->getLocale();
                                        })->first();
                                if(!isset($locale))
                                    $locale = collect($languages)->first();
                                @endphp
                                @if(!empty($languages))
                                    <a class="language-dropdown-active" href="javascript:void(0);">
                                        {{ $locale['nativeName'] }}
                                        {{-- <img src="assets/images/icon-img/flag.png" alt=""> --}}
                                    </a>
                                    <div class="language-dropdown">
                                        <ul>
                                            @foreach ($languages as $language)
                                                <li>
                                                    <a
                                                        href="{{ url('/make_local?' . 'lang=' . $language['code'] . '&full=' . $language['code']) }}">
                                                        {{ $language['nativeName'] }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>&nbsp;&nbsp; | 
                            <div class="login-reg ml-15">
                                <ul>
                                    @if (!Auth::guard('customer')->check())
                                        <li><a href="{{ url('/user/login') }}">Login</a></li>
                                        <li><a href="{{ url('/user/register') }}">Register</a></li>
                                    @else
                                        <li><a href="{{ url('/user/dashboard') }}">{{ __('My Account') }}</a></li>
                                        <li><a href="{{ url('/logout') }}"
                                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bottom sticky-bar">
            <div class="container-fluid">
                <div class="header-bottom-flex header-search-menu" style="display: none;">
                    <div class="row" style="margin-top: 20px;margin-bottom:20px;">
                        <div class="col-md-4 d-none d-xl-block">
                            <div class="logo">
                                <a href="{{ url('/') }}">
                                    <img src="{{ current_shop_logo_url() }}"
                                        onerror="this.onerror=null;this.src='/assets/img/empty/logo_300x120.jpg'"
                                        alt="logo">
                                </a>
                            </div>
                        </div>
                        <header-search :categories="{{ json_encode($categories) }}">
                        </header-search>
                    </div>
                </div>
                <div class="header-bottom-flex header-main-menu">
                    <div class=""
                        style="display: flex !important; 
                    align-items: center !important;
                    width: 100% !important;">
                        <div class="logo">
                            <a href="{{ url('/') }}">
                                <img src="{{ current_shop_logo_url() }}"
                                    onerror="this.onerror=null;this.src='/assets/img/empty/logo_300x120.jpg'"
                                    alt="logo">
                            </a>
                        </div>
                        <div class="main-menu menu-lh-1 main-menu-padding-1 menu-mrg-1" style="margin-left: 20px;">
                            <nav>
                                <ul>
                                    @include('multibag::components.menu.header', ['menus' => $menu_items])
                                </ul>
                            </nav>
                        </div>
                    </div>

                    <div class="header-action-wrap header-action-flex header-action-width"
                        style="max-width: 0% !important">
                        <div class="same-style ml-30">
                            <a class="search-btn" href="javascript:void(0);"><i class="icofont-search"></i></a>
                        </div>
                        @if (Auth::guard('customer')->check())
                            <div class="same-style ml-30">
                                <a href="{{ url('/user/dashboard') }}"><i class="icofont-user-alt-3"></i></a>
                            </div>
                        @endif
                        <div class="same-style ml-30">
                            <a class="notification" href="{{ url('/wishlist') }}">
                                <i class="icon-heart"></i>
                                <span v-if="wishlistCount>0" class="badge" v-text="wishlistCount"
                                    style="right:-11px;">0</span>
                            </a>
                        </div>

                        <div class="same-style header-cart ml-30">
                            <a class="cart-active notification" href="{{ url('/cart') }}">
                                <i class="icofont-shopping-cart"></i>
                                <span class="badge" v-text="cart.count ? cart.count : 0">0</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-small-device header-small-ptb sticky-bar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="header-search-menu" style="display: none">
                    <header-search :categories="{{ json_encode($categories) }}">
                    </header-search>
                </div>
                <div class="col-6 header-main-menu">
                    <div class="mobile-logo mobile-logo-width">
                        <a href="{{ url('/') }}">
                            <img alt="" src="{{ current_shop_logo_url() }}"
                                onerror="this.onerror=null;this.src='/assets/img/empty/logo_300x120.jpg'"
                                alt="logo">
                        </a>
                    </div>
                </div>
                <div class="col-6 header-main-menu">
                    <div class="header-action-wrap header-action-flex header-action-mrg-1">
                        <div class="same-style header-cart">
                            <a class="search-btn" href="javascript:void(0);"><i class="icofont-search"></i></a>
                        </div>
                        <div class="same-style header-cart">
                            <a class="cart-active" href="javascript:void(0);"><i class="icofont-shopping-cart"></i></a>
                        </div>
                        <div class="same-style header-info">
                            <button class="mobile-menu-button-active">
                                <span class="info-width-1"></span>
                                <span class="info-width-2"></span>
                                <span class="info-width-3"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- mini cart start -->
<div class="sidebar-cart-active">
    <div class="sidebar-cart-all">
        <a class="cart-close" href="javascript:void(0);"><i class="icofont-close-line"></i></a>
        <div class="cart-content">
            <h3>{{ __('Shopping Cart') }}</h3>
            <ul>
                <li class="single-product-cart" v-for="item in cart.items" :key="item.id">
                    <div class="cart-img">
                        <a href="javascript:void(0);"><img :alt="item.name" :src="item.attributes.image"></a>
                    </div>
                    <div class="cart-title">
                        <h4><a href="javascript:void(0);">@{{ item.name }}</a></h4>
                        <span>@{{ item.quantity }} × @{{ item.price }}</span>
                    </div>
                    <div class="cart-delete">
                        <a href="javascript:void(0);" @click="removeCart(item)">×</a>
                    </div>
                </li>
            </ul>
            <div class="cart-total">
                <h4>{{ __('Sub Total') }}: <span>@{{ $formatPrice(cart.subtotal ?? 0) }}</span></h4>
            </div>
            <div class="cart-total">
                <h4>{{ __('Total') }}: <span>@{{ $formatPrice(cart.subtotal ?? 0) }}</span></h4>
            </div>
            <div class="cart-checkout-btn">
                <a class="btn-hover cart-btn-style" href="{{ url('/cart') }}">{{ __('View cart') }}</a>
                <a class="no-mrg btn-hover cart-btn-style" href="{{ url('/checkout') }}">{{ __('Checkout') }}</a>
            </div>
        </div>
    </div>
</div>
<!-- Mobile menu start -->
<div class="mobile-menu-active clickalbe-sidebar-wrapper-style-1">
    <div class="clickalbe-sidebar-wrap">
        <a class="sidebar-close"><i class="icofont-close-line"></i></a>
        <div class="mobile-menu-content-area sidebar-content-100-percent">
            <div class="mobile-search">
                {{-- <form class="search-form" action="#">
                    <input type="text" placeholder="Search here…">
                    <button class="button-search"><i class="icofont-search-1"></i></button>
                </form> --}}
            </div>
            <div class="clickable-mainmenu-wrap clickable-mainmenu-style1">
                <nav>
                    <ul>
                        @include('multibag::components.menu.mobile-header', ['menus' => $menu_items])
                        <li><a href="{{ url('/wishlist') }}">{{ __('Wishlist') }}</a></li>
                        @if (!Auth::guard('customer')->check())
                            <li><a href="{{ url('/user/login') }}">{{ __('Login') }}</a></li>
                            <li><a href="{{ url('/user/register') }}">{{ __('Register') }}</a></li>
                        @else
                            <li><a href="{{ url('/user/dashboard') }}">{{ __('My Account') }}</a></li>
                            <li>
                                <a href="{{ url('/logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            <div class="mobile-curr-lang-wrap">
                <div class="single-mobile-curr-lang">
                    @if (Cache::has(domain_info('shop_id') . 'languages'))
                        @php
                            // $languages= active_lang_list(); //Cache::get(domain_info('shop_id').'languages');
                            $locale = app()->getLocale();
                            $languages = Cache::get(domain_info('shop_id') . 'languages');
                            $languages = json_decode($languages);
                        @endphp
                        <a class="mobile-language-active" href="javascript:void(0);">
                            {{-- {{ strtoupper($locale) }} --}}
                            {{ __('Language') }}
                            {{-- <img src="assets/images/icon-img/flag.png" alt=""> --}}
                            <i class="icofont-simple-down"></i>
                        </a>
                        <div class="lang-curr-dropdown lang-dropdown-active" style="display:none;">
                            <ul>
                                @foreach ($languages as $lang_key => $language)
                                    <li>
                                        <a
                                            href="{{ url('/make_local?' . 'lang=' . $language . '&full=' . $language) }}">
                                            {{ $language }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            {{-- <div class="aside-contact-info">
                <ul>
                    <li><i class="icofont-clock-time"></i</li>
                    <li><i class="icofont-envelope"></i></li>
                    <li><i class="icofont-stock-mobile"></i></li>
                    <li><i class="icofont-home"></i></li>
                </ul>
            </div> --}}
        </div>
    </div>
</div>
<form id="logout-form" action="{{ url('/logout') }}" method="POST" class="d-none">
    @csrf
</form>
