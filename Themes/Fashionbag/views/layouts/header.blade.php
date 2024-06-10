@php
$menu_items = CollapseAbleMenuItems('header');
$locations = \App\Models\Category::where('shop_id', domain_info('shop_id'))->where('type','city')->with('child_relation')->get();
@endphp
<div class="header section">

    <!-- Header Top Start -->
    <div class="header-top bg-light">
        <div class="container">
            <div class="row row-cols-xl-2 align-items-center">

                <!-- Header Top Language, Currency & Link Start -->
                <div class="col d-none d-lg-block">
                    <div class="header-top-lan-curr-link">
                        <div class="header-top-lan dropdown" style=" display: flex; ">
                            @php
                            $languages = get_shop_languages();
                            $locale = collect($languages)->filter(function($item) {
                                            return $item['code'] == app()->getLocale();
                                    })->first();

                            if(!isset($locale))
                                $locale = collect($languages)->first();
                            @endphp
                            @if(!empty($languages))
                            <button class="dropdown-toggle" data-bs-toggle="dropdown">{{ $locale['nativeName'] }} <i class="fa fa-angle-down"></i></button>
                            <ul class="dropdown-menu dropdown-menu-right animate slideIndropdown">
                                @foreach ($languages as $language)
                                <li><a class="dropdown-item" href="{{ url('/make_local?' . 'lang=' . $language['code'] . '&full=' . $language['code']) }}">{{ $language['nativeName'] }}</a></li>
                                @endforeach
                            </ul>
                            @endif
                                <div class="mt-2" style="font-size: 15px;">Track Your Order&nbsp;&nbsp;&nbsp; </div>
                                <form method="post" action="/order_track" id="order_track">
                                    @csrf
                                    <div style=" margin-right: 10px; display:flex; border: 1px solid;" class="mt-2">
                                        <input type="text" name="order_no" id="track_order" autocomplete="off" placeholder="  Enter Order ID" style=" border: none;height: 26px;">
                                        <span class="fa fa-search" onclick='$("#order_track").submit();' style="font-size: 20px;background: white;padding: 3px;"></span>
                                    </div>
                                </form>
                            </div>
                        {{-- <div class="header-top-links">
                            <span>Call Us</span><a href="javascript::void(0)">phone</a>
                        </div> --}}
                    </div>
                </div>
                <!-- Header Top Language, Currency & Link End -->

                <!-- Header Top Message Start -->
                {{-- <div class="col">
                    <p class="header-top-message"> <a href="javascript::void(0)">{{$trans('Shop Now')}}</a></p>
                </div> --}}
                <!-- Header Top Message End -->

            </div>
        </div>
    </div>
    <!-- Header Top End -->

    <!-- Header Bottom Start -->
    <div class="header-bottom">
        <div class="header-sticky">
            <div class="container">
                <div class="row align-items-center search-menu-container" style="display: none;">
                    <!-- Header Logo Start -->
                    <div class="col-xl-2 col-6 d-none d-xl-block">
                        <div class="header-logo">
                            <a href="{{ url('/') }}">
                                <img src="{{ current_shop_logo_url() }}" 
                                onerror="this.onerror=null;this.src='/assets/img/empty/logo_300x120.jpg'"
                                alt="Logo" /></a>
                        </div>
                    </div>
                    <!-- Header Logo End -->
                    <div class="col-xl-8">
                        <div style="margin-top:20px;margin-bottom:20px;">
                            <header-search
                                :categories="{{ json_encode($categories) }}"
                            >
                            </header-search>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center main-menu-container">

                    <!-- Header Logo Start -->
                    <div class="col-xl-2 col-6">
                        <div class="header-logo">
                            <a href="{{ url('/') }}">
                                <img src="{{ current_shop_logo_url() }}" 
                                onerror="this.onerror=null;this.src='/assets/img/empty/logo_300x120.jpg'"
                                alt="Logo" /></a>
                        </div>
                    </div>
                    <!-- Header Logo End -->

                    <!-- Header Menu Start -->
                    <div class="col-xl-8 d-none d-xl-block">
                        <div class="main-menu position-relative">
                            <ul>
                                @include('fashionbag::components.menu.header', ['menus' => $menu_items])
                            </ul>
                        </div>
                    </div>
                    <!-- Header Menu End -->

                    <!-- Header Action Start -->
                    <div class="col-xl-2 col-6">
                        <div class="header-actions">

                            <!-- Search Header Action Button Start -->
                            <a href="javascript:void(0)" class="header-action-btn header-action-btn-search">
                                <i class="pe-7s-search"></i>
                            </a>
                            <!-- Search Header Action Button End -->

                            <!-- User Account Header Action Button Start -->
                            @if (Auth::guard('customer')->check())
                            <a href="{{ url('/user/dashboard') }}" class="header-action-btn d-none d-md-block"><i class="pe-7s-user"></i></a>
                            @else
                            <a href="{{ url('/user/login') }}" class="header-action-btn d-none d-md-block"><i class="pe-7s-user"></i></a>
                            @endif
                            <!-- User Account Header Action Button End -->

                            <!-- Wishlist Header Action Button Start -->
                            <a href="{{ url('/wishlist') }}" class="header-action-btn header-action-btn-wishlist d-none d-md-block">
                                <i class="pe-7s-like"></i>
                                <span v-if="wishlistCount>0" class="header-action-num" v-text="wishlistCount" style="right:-11px;">0</span>
                            </a>
                            <!-- Wishlist Header Action Button End -->

                            <!-- Shopping Cart Header Action Button Start -->
                            <a href="javascript:void(0)" class="header-action-btn header-action-btn-cart">
                                <i class="pe-7s-shopbag"></i>
                                <span class="header-action-num" v-text="cart.count ? cart.count : 0">0</span>
                            </a>
                            <!-- Shopping Cart Header Action Button End -->

                            <!-- Mobile Menu Hambarger Action Button Start -->
                            <a href="javascript:void(0)" class="header-action-btn header-action-btn-menu d-xl-none d-lg-block">
                                <i class="fa fa-bars"></i>
                            </a>
                            <!-- Mobile Menu Hambarger Action Button End -->

                        </div>
                    </div>
                    <!-- Header Action End -->

                </div>
            </div>
        </div>
    </div>
    <!-- Header Bottom End -->

    <!-- Mobile Menu Start -->
    <div class="mobile-menu-wrapper">
        <div class="offcanvas-overlay"></div>

        <!-- Mobile Menu Inner Start -->
        <div class="mobile-menu-inner">

            <!-- Button Close Start -->
            <div class="offcanvas-btn-close">
                <i class="pe-7s-close"></i>
            </div>
            <!-- Button Close End -->

            <!-- Mobile Menu Start -->
            <div class="mobile-navigation">
                <nav>
                    <ul class="mobile-menu">
                        @include('fashionbag::components.menu.mobile-header', ['menus' => $menu_items])
                        <li><a href="{{ url('/wishlist') }}">{{ __('Wishlist') }}</a></li>
                        @if (!Auth::guard('customer')->check())
                        <li><a href="{{ url('/user/login') }}">{{ __('Login') }}</a></li>
                        <li><a href="{{ url('/user/register') }}">{{ __('Register') }}</a></li>
                        @else
                        <li><a href="{{ url('/user/dashboard') }}">{{ __('My Account') }}</a></li>
                        <li>
                            <a href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
            <!-- Mobile Menu End -->

            <!-- Language, Currency & Link Start -->
            <div class="offcanvas-lag-curr mb-6">
                @if (Cache::has(domain_info('shop_id') . 'languages'))
                @php
                    // $languages= active_lang_list(); //Cache::get(domain_info('shop_id').'languages');
                    $locale = app()->getLocale();
                    $languages = Cache::get(domain_info('shop_id') . 'languages');
                    $languages = json_decode($languages);
                @endphp
                <h2 class="title">{{ __('Language') }}</h2>
                <div class="header-top-lan-curr-link">
                    <div class="header-top-lan dropdown">
                        <button class="dropdown-toggle" data-bs-toggle="dropdown">English <i class="fa fa-angle-down"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right animate slideIndropdown">
                            @foreach ($languages as $lang_key => $language)
                            <li>
                                <a class="dropdown-item" href="{{ url('/make_local?' . 'lang=' . $language . '&full=' . $language) }}">
                                    {{ $language }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    {{-- <div class="header-top-curr dropdown">
                        <button class="dropdown-toggle" data-bs-toggle="dropdown">USD <i class="fa fa-angle-down"></i></button>
                        <ul class="dropdown-menu dropdown-menu-right animate slideIndropdown">
                            <li><a class="dropdown-item" href="javascript::void(0)">USD</a></li>
                            <li><a class="dropdown-item" href="javascript::void(0)">Pound</a></li>
                        </ul>
                    </div> --}}
                </div>
                @endif
            </div>
            <!-- Language, Currency & Link End -->

            <!-- Contact Links/Social Links Start -->
            <div class="mt-auto">

                <!-- Contact Links Start -->
                <ul class="contact-links">
                    @php
                    $location = Cache::get(domain_info('shop_id').'location','');
                    $location = json_decode($location, true);
                    @endphp
                    @if(isset($location['phone']))
                    <li><i class="fa fa-phone"></i><a href="tel:{{$location['phone'] ?? ''}}"> {{$location['phone'] ?? ''}}</a></li>
                    @endif
                    @if(isset($location['email']))
                    <li><i class="fa fa-envelope-o"></i><a href="mailto:{{ $location['email'] ?? "" }}"> {{ $location['email'] ?? "" }}</a></li>
                    @endif
                    {{-- <li><i class="fa fa-clock-o"></i> <span>Monday - Sunday 9.00 - 18.00</span> </li> --}}
                </ul>
                <!-- Contact Links End -->

                <!-- Social Widget Start -->
                @if(Cache::has(domain_info('shop_id').'socials'))
                @php
                $socials=json_decode(Cache::get(domain_info('shop_id').'socials',[]));
                @endphp
                    @if(count($socials) > 0)
                    <div class="widget-social">
                        @foreach($socials as $key => $value)
                        <a href="{{ url($value->url) }}"><i class="{{ $value->icon }}"></i></a>
                        @endforeach
                    </div>
                    @endif
                @endif
                <!-- Social Widget Ende -->
            </div>
            <!-- Contact Links/Social Links End -->
        </div>
        <!-- Mobile Menu Inner End -->
    </div>
    <!-- Mobile Menu End -->

    <!-- Offcanvas Search Start -->
    {{-- <div class="offcanvas-search">
        <div class="offcanvas-search-inner">

            <!-- Button Close Start -->
            <div class="offcanvas-btn-close">
                <i class="pe-7s-close"></i>
            </div>
            <!-- Button Close End -->

            <!-- Offcanvas Search Form Start -->
            <form class="offcanvas-search-form" action="#">
                <input type="text" placeholder="Search Here..." class="offcanvas-search-input">
            </form>
            <!-- Offcanvas Search Form End -->

        </div>
    </div> --}}
    <!-- Offcanvas Search End -->

    <!-- Cart Offcanvas Start -->
    <div class="cart-offcanvas-wrapper">
        <div class="offcanvas-overlay"></div>

        <!-- Cart Offcanvas Inner Start -->
        <div class="cart-offcanvas-inner">

            <!-- Button Close Start -->
            <div class="offcanvas-btn-close">
                <i class="pe-7s-close"></i>
            </div>
            <!-- Button Close End -->

            <!-- Offcanvas Cart Content Start -->
            <div class="offcanvas-cart-content">
                <!-- Offcanvas Cart Title Start -->
                <h2 class="offcanvas-cart-title mb-10">{{ __('Shopping Cart') }}</h2>
                <!-- Offcanvas Cart Title End -->

                <!-- Cart Product/Price Start -->
                <div class="cart-product-wrapper mb-6" v-for="item in cart.items" :key="item.id">

                    <!-- Single Cart Product Start -->
                    <div class="single-cart-product">
                        <div class="cart-product-thumb">
                            <a href="javascript::void(0)">
                                <img :alt="item.name " :src="item.attributes.image">
                            </a>
                        </div>
                        <div class="cart-product-content">
                            <h3 class="title">
                                <a href="javascript::void(0)">@{{ item.name }}</a>
                            </h3>
                            <span class="price">
                            <span class="new">@{{ item.quantity }} × </span>@{{ item.price }}</span>
                            {{-- <span class="old">$40.00</span> --}}
                            </span>
                        </div>
                    </div>
                    <!-- Single Cart Product End -->

                    <!-- Product Remove Start -->
                    <div class="cart-product-remove">
                        <a href="javascript::void(0)" @click="removeCart(item)"><i class="fa fa-trash"></i></a>
                    </div>
                    <!-- Product Remove End -->

                </div>
                <!-- Cart Product/Price End -->

                <!-- Cart Product Total Start -->
                <div class="cart-product-total">
                    <span class="value">{{ __('Sub Total') }}</span>
                    <span class="price">@{{$formatPrice(cart.subtotal ?? 0)}}</span>
                </div>
                <div class="cart-product-total">
                    <span class="value">{{ __('Total') }}</span>
                    <span class="price">@{{$formatPrice(cart.subtotal ?? 0)}}</span>
                </div>
                <!-- Cart Product Total End -->

                <!-- Cart Product Button Start -->
                <div class="cart-product-btn mt-4">
                    <a class="btn btn-fb btn-hover-primary rounded-0 w-100" href="{{ url('/cart') }}">{{ __('View cart') }}</a>
                    <a class="btn btn-fb btn-hover-primary rounded-0 w-100 mt-4" href="{{ url('/checkout') }}">{{ __('Checkout') }}</a>
                </div>
                <!-- Cart Product Button End -->

            </div>
            <!-- Offcanvas Cart Content End -->

        </div>
        <!-- Cart Offcanvas Inner End -->
    </div>
    <!-- Cart Offcanvas End -->

</div>
<form id="logout-form" action="{{ url('/logout') }}" method="POST" class="d-none">
    @csrf
</form>
