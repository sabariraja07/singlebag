@php
$menu_items = CollapseAbleMenuItems('header');
$locations = \App\Models\Category::where('shop_id', domain_info('shop_id'))->where('type','city')->with('child_relation')->get();
@endphp
<header class="header-area header-style-1 header-style-5 header-height-2">
    <!-- <div class="mobile-promotion">
        <span>Grand opening, <strong>up to 15%</strong> off all items. Only <strong>3 days</strong> left</span>
    </div> -->
    <div class="header-top header-top-ptb-1 d-none d-lg-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-1 col-lg-1">
                    <div class="header-info">
                        <ul>
                            <!-- <li><a href="page-about.htlm">About Us</a></li> -->
                            {{-- <li><a href="{{ url('/user/dashboard') }}">{{ __('My Account') }}</a></li> --}}
                            {{-- <li><a href="{{ url('/wishlist') }}">{{ __('Wishlist') }}</a></li> --}}
                            <!-- <li><a href="shop-order.html">Order Tracking</a></li> -->
                        </ul>
                    </div>
                </div>
                <div class="col-xl-1 col-lg-1">
                    <div class="text-center">
                        <div id="news-flash" class="d-inline-block">
                            <!-- <ul>
                            <li>100% Secure delivery without contacting the courier</li>
                            <li>Supper Value Deals - Save more with coupons</li>
                            <li>Trendy 25silver jewelry, save up 35% off today</li>
                        </ul> -->
                        </div>
                    </div>
                </div>
                <div class="col-xl-10 col-lg-10">
                    <div class="header-info header-info-right">
                        <ul>
                            @php
                            $languages = get_shop_languages();
                            $locale = collect($languages)->filter(function($item) {
                                            return $item['code'] == app()->getLocale();
                                    })->first();
                            if(!isset($locale))
                                $locale = collect($languages)->first();
                            @endphp
                            @if(!empty($languages))
                            <li>
                                <a href="javascript:void(0);" class="language-dropdown-active">{{ $locale['nativeName'] }} 
                                    <i class="fi-rs-angle-small-down"></i>
                                </a>
                                <ul class="language-dropdown" style="min-width: 140px;text-transform: uppercase;">
                                    @foreach($languages as $language)
                                    <li>
                                      
                                        <a href="{{ url('/make_local?'.'lang='.$language['code'].'&full='.$language['code']) }}">
                                            @if($locale['countryCode'] != $language['code'])
                                            <i class="fa fa-circle-o"></i>
                                            @else
                                            <i class="fa fa-check-circle-o" style="color: green;"></i>
                                            <img src="frontend/singlebag/imgs/theme/flag-dt.png" alt="" />
                                            @endif
                                            {{ $language['nativeName']  }}
                                        </a>
                                    </li>
                                    @endforeach
                                    <!-- <li>
                                    <a href="#"><img src="frontend/singlebag/imgs/theme/flag-dt.png" alt="" />Deutsch</a>
                                </li>
                                <li>
                                    <a href="#"><img src="frontend/singlebag/imgs/theme/flag-ru.png" alt="" />Pусский</a>
                                </li> -->
                                </ul>
                            </li>

                            @endif
                        <form method="post" action="/order_track" id="order_track">
                        @csrf
                            <li style=" margin-right: 10px; display:flex;border: 1px solid;">
                                <input type="text" name="order_no" id="track_order" autocomplete="off" placeholder="Enter Order ID to Track" style=" border: none;font-size: 12px;height: 26px;background: white;">&nbsp;
                                <span class="fa fa-search" onclick='$("#order_track").submit();' style="font-size:17px;background: white;padding: 3px;"></span>
                            </li>
                        </form>
                            <!-- <li>
                            <a class="language-dropdown-active" href="#">USD <i
                                    class="fi-rs-angle-small-down"></i></a>
                            <ul class="language-dropdown">
                                <li>
                                    <a href="#"><img src="frontend/singlebag/imgs/theme/flag-fr.png" alt="" />INR</a>
                                </li>
                                <li>
                                    <a href="#"><img src="frontend/singlebag/imgs/theme/flag-dt.png" alt="" />MBP</a>
                                </li>
                                <li>
                                    <a href="#"><img src="frontend/singlebag/imgs/theme/flag-ru.png" alt="" />EU</a>
                                </li>
                            </ul>
                        </li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-middle header-middle-ptb-1 d-none d-lg-block">
        <div class="container">
            <div class="header-wrap">
                <div class="logo logo-width-1">
                    <a href="{{ url('/') }}">
                        <img src="{{ get_shop_logo_url(domain_info('shop_id')) }}" alt="logo" />
                    </a>
                </div>
                <div class="header-right">
                    <div class="search-style-2 header-search">
                        <header-search
                            :categories="{{ json_encode($categories) }}"
                        >
                        </header-search>
                    </div>
                    <div class="header-action-right">
                        <div class="header-action-2">
                            @if(count($locations) > 0)
                            <div class="search-location">
                                <form action="#">
                                    <select class="select-active" name="location">
                                        <option>{{ __('Your Location') }}</option>
                                        @foreach($locations as $location)
                                        <option value="{{ $location->id }}"
                                            data-method="{{ $location->child_relation }}">{{ $location->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                            @endif

                            <div class="header-action-icon-2">
                                <a href="{{ url('/wishlist') }}">
                                    <img class="svgInject" alt="#"
                                        src="{{ asset('frontend/singlebag/imgs/theme/icons/icon-heart.svg') }}" />
                                    <span class="pro-count blue wishlist_count" v-text="wishlistCount"></span>
                                </a>
                                <a href="{{ url('/wishlist') }}"><span class="lable">{{ __('Wishlist') }}</span></a>
                            </div>
                            <div class="header-action-icon-2">
                                <a class="mini-cart-icon" href="{{ url('/cart') }}">
                                    <img alt="#" src="{{ asset('frontend/singlebag/imgs/theme/icons/icon-cart.svg') }}" />
                                    <span id="cart_count" class="pro-count blue" v-text="cart.count">0</span>
                                </a>
                                <a href="{{ url('/cart') }}"><span class="lable">{{ __('Cart') }}</span></a>
                                <div class="cart-dropdown-wrap cart-dropdown-hm2">
                                    <ul v-if="cart.count > 0">
                                        <li v-for="item in cart.items" :key="item.id">
                                            <div class="shopping-cart-img">
                                                <a href="javascript:void(0);">
                                                    <img :alt="item.name " :src="item.attributes.image" />
                                                </a>
                                            </div>
                                            <div class="shopping-cart-title">
                                                <h4><a href="javascript:void(0);">@{{ item.name }}</a></h4>
                                                <h3><span>@{{ item.quantity }} × </span>@{{ item.price }}</h3>
                                            </div>
                                            <div class="shopping-cart-delete">
                                                <a href="javascript:void(0);" @click="removeCart(item)"><i
                                                        class="fi-rs-cross-small"></i></a>
                                            </div>
                                        </li>
                                    </ul>
                                    <ul v-else>
                                        <li>
                                            <div class="txt-cart-empty">{{ __('Your cart is empty') }}</div>
                                        </li>
                                    </ul>
                                    <div class="shopping-cart-footer">
                                        <div class="shopping-cart-total">
                                            <h4 v-html="'{{ __('Sub Total') }} <span>' + $formatPrice(cart.subtotal ?? 0) + '</span>'"></h4>
                                        </div>
                                        <div class="shopping-cart-total">
                                            <h4>{{ __('Total') }} <span>@{{ $formatPrice(cart.total ?? 0 )}}</span></h4>
                                        </div>
                                        <div class="shopping-cart-button">
                                            <a href="{{ url('/cart') }}">{{ __('View cart') }}</a>
                                            <a href="{{ url('/checkout') }}">{{ __('Checkout') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="header-action-icon-2">
                                @if(!Auth::guard('customer')->check())
                                <a href="{{ url('/user/login') }}">
                                    <img class="svgInject" alt="#"
                                        src="{{ asset('frontend/singlebag/imgs/theme/icons/icon-user.svg') }}" />
                                </a>
                                <a href="{{ url('/user/login') }}"><span class="lable ml-2">{{ __('Login') }}</span></a>
                                @else
                                <a href="{{ url('/user/dashboard') }}">
                                    <img class="svgInject" alt="#"
                                        src="{{ asset('frontend/singlebag/imgs/theme/icons/icon-user.svg') }}" />
                                </a>
                                <a href="{{ url('/user/dashboard') }}"><span class="lable ml-2">{{ __('Account') }}</span></a>
                                <div class="cart-dropdown-wrap cart-dropdown-hm2 account-dropdown">
                                    <ul>
                                        <li>
                                            <a href="{{ url('/user/dashboard') }}">
                                                <i class="fi fi-rs-user mr-10"></i>
                                                {{ __('My Account') }}
                                            </a>
                                        </li>

                                        <li>
                                            <a href="{{ url('/wishlist') }}"><i class="fi fi-rs-heart mr-10"></i>
                                                {{ __('My Wishlist') }}</a>
                                        </li>
                                        <!-- <li>
                                        <a href="page-account.html"><i class="fi fi-rs-label mr-10"></i>My
                                            Voucher</a>
                                    </li>
                                    <li>
                                        <a href="page-account.html"><i
                                                class="fi fi-rs-settings-sliders mr-10"></i>Setting</a>
                                    </li> -->
                                        <li>
                                            <a href="{{ url('/logout') }}" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                                <i class="fi fi-rs-sign-out mr-10"></i>{{ __('Logout') }}</a>
                                        </li>
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom header-bottom-bg-color sticky-bar">
        <div class="container">
            <div class="header-wrap header-space-between position-relative">
                <div class="logo logo-width-1 d-block d-lg-none">
                    <a href="{{ url('/') }}">
                        <img src="{{ get_shop_logo_url(domain_info('shop_id')) }}" alt="logo" />
                    </a>
                </div>
                <div class="header-nav d-none d-lg-flex">
                    <div class="main-categori-wrap d-none d-lg-block">
                        <a class="categories-button-active" href="#">
                            <span class="fi-rs-apps"></span> <span class="et">{{ __('Trending Categories') }}</span> 
                            <i class="fi-rs-angle-down"></i>
                        </a>
                        <div class="categories-dropdown-wrap categories-dropdown-active-large font-heading">
                            <div class="d-flex categori-dropdown-inner">
                                <ul>
                                    @foreach ($categories as $index => $menu)
                                    @if($index % 2 == 0)
                                    <li>
                                        <a href="{{ 'category/' . $menu['slug'] . '/' . $menu['id'] }}">
                                            <img src="{{$menu['url']}}" alt="" />
                                            {{ $menu['name'] }}
                                        </a>
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                                <ul class="end">
                                    @foreach ($categories as $index => $menu)
                                    @if($index % 2 != 0)
                                    <li>
                                        <a href="{{ 'category/' . $menu['slug'] . '/' . $menu['id'] }}">
                                            <img src="{{$menu['url']}}" alt="" />
                                            {{ $menu['name'] }}
                                        </a>
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                            </div>
                            <!-- <div class="more_slide_open" style="display: none">
                            <div class="d-flex categori-dropdown-inner">
                                <ul>
                                    <li>
                                        <a href="#" onclick="return false;"> <img
                                                src="frontend/singlebag/imgs/theme/icons/icon-1.svg" alt="" />Milks and
                                            Dairies</a>
                                    </li>
                                    <li>
                                        <a href="#" onclick="return false;"> <img
                                                src="frontend/singlebag/imgs/theme/icons/icon-2.svg" alt="" />Clothing &
                                            beauty</a>
                                    </li>
                                </ul>
                                <ul class="end">
                                    <li>
                                        <a href="#" onclick="return false;"> <img
                                                src="frontend/singlebag/imgs/theme/icons/icon-3.svg" alt="" />Wines & Drinks</a>
                                    </li>
                                    <li>
                                        <a href="#" onclick="return false;"> <img
                                                src="frontend/singlebag/imgs/theme/icons/icon-4.svg" alt="" />Fresh Seafood</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="more_categories"><span class="icon"></span> <span class="heading-sm-1">Show
                                more...</span></div> -->
                        </div>
                    </div>
                    <div class="main-menu main-menu-padding-1 main-menu-lh-2 d-none d-lg-block font-heading">
                        <nav>
                            <ul>
                                @include('singlebag::components.menu.header', ['menus' => $menu_items])
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="header-action-icon-2 d-block d-lg-none">
                    <div class="burger-icon burger-icon-white">
                        <span class="burger-icon-top"></span>
                        <span class="burger-icon-mid"></span>
                        <span class="burger-icon-bottom"></span>
                    </div>
                </div>
                <div class="header-action-right d-block d-lg-none">
                    <div class="header-action-2">
                        <div class="header-action-icon-2">
                            <a href="{{ url('/wishlist') }}">
                                <img class="svgInject" alt="#"
                                    src="{{ asset('frontend/singlebag/imgs/theme/icons/icon-heart.svg') }}" />
                                <span class="pro-count blue wishlist_count" v-text="wishlistCount"></span>
                            </a>
                            <a href="{{ url('/wishlist') }}"><span class="lable">{{ __('Wishlist') }}</span></a>
                        </div>
                        <div class="header-action-icon-2">
                            <a class="mini-cart-icon" href="{{ url('/cart') }}">
                                <img alt="#" src="{{ asset('frontend/singlebag/imgs/theme/icons/icon-cart.svg') }}" />
                                <span id="cart_count" class="pro-count blue" v-text="cart.count">0</span>
                            </a>
                            <a href="{{ url('/cart') }}"><span class="lable">{{ __('Cart') }}</span></a>
                            <div class="cart-dropdown-wrap cart-dropdown-hm2">
                                <ul v-if="cart.count > 0">
                                    <li v-for="item in cart.items" :key="item.id">
                                        <div class="shopping-cart-img">
                                            <a href="#">
                                                <img :alt="item.name " :src="item.attributes.image" />
                                            </a>
                                        </div>
                                        <div class="shopping-cart-title">
                                            <h4><a href="#">@{{ item.name }}</a></h4>
                                            <h3><span>@{{ item.quantity }} × </span>@{{ item.price }}</h3>
                                        </div>
                                        <div class="shopping-cart-delete">
                                            <a href="#" @click="removeCart(item)"><i
                                                    class="fi-rs-cross-small"></i></a>
                                        </div>
                                    </li>
                                </ul>
                                <ul v-else>
                                    <li>
                                        <div class="txt-cart-empty">{{ __('Your cart is empty') }}</div>
                                    </li>
                                </ul>
                                <div class="shopping-cart-footer">
                                    <div class="shopping-cart-total">
                                        <h4 v-html="'{{ __('Sub Total') }} <span>' + $formatPrice(cart.subtotal ?? 0) + '</span>'"></h4>
                                    </div>
                                    <div class="shopping-cart-total">
                                        <h4>{{ __('Total') }} <span>@{{ $formatPrice(cart.total ?? 0 )}}</span></h4>
                                    </div>
                                    <div class="shopping-cart-button">
                                        <a href="{{ url('/cart') }}">{{ __('View cart') }}</a>
                                        <a href="{{ url('/checkout') }}">{{ __('Checkout') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="header-action-icon-2">
                                    @if(!Auth::guard('customer')->check())
                                    <a href="{{ url('/user/login') }}">
                                        <img class="svgInject" alt="#"
                                            src="frontend/singlebag/imgs/theme/icons/icon-user.svg" />
                                    </a>
                                    <a href="{{ url('/user/login') }}"><span class="lable ml-2">Login</span></a>
                                    @else
                                    <a href="{{ url('/user/dashboard') }}">
                                        <img class="svgInject" alt="#"
                                            src="frontend/singlebag/imgs/theme/icons/icon-user.svg" />
                                    </a>
                                    <a href="{{ url('/user/dashboard') }}"><span class="lable ml-2">Account</span></a>
                                    <div class="cart-dropdown-wrap cart-dropdown-hm2 account-dropdown">
                                        <ul>
                                            <li>
                                                <a href="{{ url('/user/dashboard') }}"><i
                                                        class="fi fi-rs-user mr-10"></i>My
                                                    Account</a>
                                            </li>

                                            <li>
                                                <a href="{{ url('/wishlist') }}"><i class="fi fi-rs-heart mr-10"></i>My
                                                    Wishlist</a>
                                            </li>
                                            <!-- <li>
                                            <a href="page-account.html"><i class="fi fi-rs-label mr-10"></i>My
                                                Voucher</a>
                                        </li>
                                        <li>
                                            <a href="page-account.html"><i
                                                    class="fi fi-rs-settings-sliders mr-10"></i>Setting</a>
                                        </li> -->
                                            <li>
                                                <a href="{{ url('/logout') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                                    <i class="fi fi-rs-sign-out mr-10"></i>{{ __('Logout') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="mobile-header-active mobile-header-wrapper-style">
    <div class="mobile-header-wrapper-inner">
        <div class="mobile-header-top">
            <div class="mobile-header-logo">
                <a href="{{ url('/') }}"><img src="{{ current_shop_logo_url() }}"
                        alt="logo" /></a>
            </div>
            <div class="mobile-menu-close close-style-wrap close-style-position-inherit">
                <button class="close-style search-close">
                    <i class="icon-top"></i>
                    <i class="icon-bottom"></i>
                </button>
            </div>
        </div>
        <div class="mobile-header-content-area">
            {{-- <div class="mobile-search search-style-3 mobile-header-border">
                <form action="#">
                    <input type="text" placeholder="Search for items…" />
                    <button type="submit"><i class="fi-rs-search"></i></button>
                </form>
            </div> --}}
            <div class="mobile-menu-wrap mobile-header-border">
                <!-- mobile menu start -->
                <nav>
                    <ul class="mobile-menu font-heading">
                        @include('singlebag::components.menu.mobile-header', ['menus' => $menu_items])
                    </ul>
                </nav>
            </div>
            <!-- <div class="mobile-header-info-wrap">
            <div class="single-mobile-header-info">
                <a href="page-contact.html"><i class="fi-rs-marker"></i> Our location </a>
            </div>
            <div class="single-mobile-header-info">
                <a href="page-login.html"><i class="fi-rs-user"></i>Log In / Sign Up </a>
            </div>
            <div class="single-mobile-header-info">
                <a href="#"><i class="fi-rs-headphones"></i>(+01) - 2345 - 6789 </a>
            </div>
        </div>
        <div class="mobile-social-icon mb-50">
            <h6 class="mb-15">Follow Us</h6>
            <a href="#"><img src="frontend/singlebag/imgs/theme/icons/icon-facebook-white.svg" alt="" /></a>
            <a href="#"><img src="frontend/singlebag/imgs/theme/icons/icon-twitter-white.svg" alt="" /></a>
            <a href="#"><img src="frontend/singlebag/imgs/theme/icons/icon-instagram-white.svg" alt="" /></a>
            <a href="#"><img src="frontend/singlebag/imgs/theme/icons/icon-pinterest-white.svg" alt="" /></a>
            <a href="#"><img src="frontend/singlebag/imgs/theme/icons/icon-youtube-white.svg" alt="" /></a>
        </div>
        <div class="site-copyright">Copyright 2021 © Singlebag. All rights reserved. Powered by AliThemes.</div> -->
        </div>
    </div>
</div>
<form id="logout-form" action="{{ url('/logout') }}" method="POST" class="d-none">
    @csrf
</form>
