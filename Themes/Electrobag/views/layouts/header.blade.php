@php
$location = Cache::get(domain_info('shop_id') . 'location', '');
$location = json_decode($location, true);
$menu_items = CollapseAbleMenuItems('header');
$locations = \App\Models\Category::where('shop_id', domain_info('shop_id'))
    ->where('type', 'city')
    ->with('child_relation')
    ->get();
@endphp

<header>
    <div class="header-top">
        <div class="content-margins">
            <div class="row">
                <div class="col-md-2 hidden-xs hidden-sm">
                    {{-- <div class="entry"><b>{{ __('Call Us') }}:</b> <a
                        href="tel:{{ $location['phone'] ?? ''}}">{{ $location['phone'] ?? "" }}</a></div> --}}
                    {{-- <div class="entry"><b>{{ __('Email') }}:</b> <a
                    href="mailto:{{ Cache::get(domain_info('shop_id').'store_email','') }}">{{ Cache::get(domain_info('shop_id').'store_email','') }}</a>
            </div> --}}
                </div>
                <div class="col-md-10 col-md-text-right">
                    <div class="entry language">
                        @php
                        $languages = get_shop_languages();
                        $locale = collect($languages)->filter(function($item) {
                                        return $item['code'] == app()->getLocale();
                                })->first();
                        if(!isset($locale))
                            $locale = collect($languages)->first();
                        @endphp
                        @if(!empty($languages))
                        <div class="title"><b>{{ $locale['nativeName'] }}</b></div>
                        <div class="language-toggle header-toggle-animation">
                            @foreach ($languages as $language)
                                <a href="{{ url('/make_local?' . 'lang=' . $language['code'] . '&full=' . $language['code']) }}">
                                    {{ $language['nativeName'] }}
                                </a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="entry">
                        @if (!Auth::guard('customer')->check())
                            <a href="{{ url('/user/login') }}"><b>login</b></a>&nbsp; or &nbsp;<a
                                href="{{ url('/user/register') }}"><b>register</b></a>
                        @else
                            <a href="{{ url('/user/dashboard') }}"><b>{{ __('My Account') }}</b></a>&nbsp; | &nbsp;
                            <a href="{{ url('/logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();"><b>{{ __('Logout') }}</b></a>
                        @endif
                    </div>
                    <div class="entry">
                        <form method="post" action="/order_track" id="order_track">
                            @csrf
                            <div style=" margin-right: 10px; display:flex;border: 1px solid;" class="mt-2">
                                {{-- <select class="custom-select" name="type" id="type" style="border-right: 1px solid;">
                                    <option value="order_id">{{ __('Search By Order ID') }}</option>
                                    <option value="email_id">{{ __('Search By Email ID') }}</option>
                                    <option value="mobile_number">{{ __('Search By Mob No.') }}</option>
                                </select> --}}
                                <input type="text" name="order_no" id="track_order" autocomplete="off" placeholder="   Track your order" style=" border: none;height: 25px;">
                                <span class="fa fa-search" onclick='$("#order_track").submit();' style="font-size: 17px;background: white;padding: 3px;border-top:1px solid;"></span>
                            </div>
                        </form>
                    </div>
                    <div class="entry hidden-xs hidden-sm">
                        <a href="{{ url('/wishlist') }}"><i class="fa fa-heart-o" aria-hidden="true"> <span
                                    class="product-label green" v-text="wishlistCount">0</span></i></a>
                    </div>
                    <div class="entry hidden-xs hidden-sm cart">
                        <a href="{{ url('/cart') }}">
                            <b class="hidden-xs">Your Cart</b>
                            <span class="cart-icon">
                                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                <span class="cart-label" v-text="cart.count">0</span>
                            </span>
                            <span class="cart-title hidden-xs">@{{ $formatPrice(cart.total ?? 0) }}</span>
                        </a>

                        <div class="cart-toggle hidden-xs hidden-sm">
                            <div class="cart-overflow" v-if="cart.count > 0">
                                <div class="cart-entry clearfix" v-for="(item, index) in cart.items"
                                    :key="index">
                                    <a class="cart-entry-thumbnail" href="javascript:void(0)"><img width="85"
                                            height="85" :alt="item.name" :src="item.attributes.image" /></a>
                                    <div class="cart-entry-description">
                                        <table>
                                            <tr>
                                                <td>
                                                    <div class="h6"><a
                                                            href="javascript:void(0)">@{{ item.name }}</a></div>
                                                    {{-- <div class="simple-article size-1">QUANTITY: @{{ item.quantity }}</div> --}}
                                                </td>
                                                <td>
                                                    <div class="simple-article size-3 grey">@{{ $formatPrice(item.price ?? 0) }} X
                                                        @{{ item.quantity }}</div>

                                                </td>
                                                <td>
                                                    <div class="simple-article size-1">TOTAL: @{{ $formatPrice(item.price * item.quantity) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="button-close" @click="removeCart(item)">
                                                        <a href="javascript:void(0)">
                                                            <i class="fi-rs-cross-small"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="cart-overflow" v-else>
                                <div class="cart-entry clearfix">
                                    <div class="txt-cart-empty">{{ __('Your cart is empty') }}</div>
                                </div>
                            </div>
                            <div class="empty-space col-xs-b40"></div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="cell-view empty-space col-xs-b50">
                                        <div class="simple-article size-5 grey">{{ __('Total') }} <span
                                                class="color">@{{ $formatPrice(cart.total ?? 0) }}</span></div>
                                    </div>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <a class="button size-2 style-3" href="{{ url('/checkout') }}">
                                        <span class="button-wrapper">
                                            <span class="icon"><img
                                                    src="{{ asset('frontend/electrobag/img/icon-4.png') }}"
                                                    alt=""></span>
                                            <span class="text">{{ __('Checkout') }}</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom">
        <div class="content-margins">
            <div class="row">
                <div class="col-xs-3 col-sm-1">
                    <a id="logo" href="{{ url('/') }}">
                        <img src="{{ current_shop_logo_url() }}" alt="logo" />
                    </a>
                </div>
                <div class="col-xs-9 col-sm-11">
                    <div class="nav-wrapper">
                        <div class="nav-close-layer"></div>
                        @include('electrobag::components.header_menu_parent', ['menus' => $menu_items])
                      
                        {{-- <nav>
                            <ul>
                                
                                @if (!empty($menu_items))
                                    @foreach ($menu_items as $row)
                                        <li>
                                            @if (isset($row->children))
                                                <a href="javascript:void(0)">{{ $row->text }} <i
                                                        class="fi-rs-angle-down"></i></a>
                                                <ul class="sub-menu">
                                                    @foreach ($row->children as $childrens)
                                                        @if ($childrens)
                                                            <li>
                                                                @if (isset($childrens->children))
                                                                    <a href="javascript:void(0)">{{ $row->text }}<i
                                                                            class="fi-rs-angle-right"></a>
                                                                @else
                                                                    <a href="{{ url($childrens->href) }}"
                                                                        @if (!empty($childrens->target)) target={{ $childrens->target }} @endif>{{ $childrens->text }}</a>
                                                                @endif

                                                                @if (isset($childrens->children))
                                                                    <ul class="level-menu">
                                                                        @foreach ($childrens->children as $parent => $row)
                                                                            <a href="{{ url($childrens->href) }}"
                                                                                @if (!empty($childrens->target)) target={{ $childrens->target }} @endif>{{ $childrens->text }}</a>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @else
                                                <a href="{{ url( $row->href ?? '/' ) }}"
                                                    @if (!empty($row->target)) target="{{ $row->target }}" @endif>{{ $row->text }}</a>
                                            @endif

                                        </li>
                                    @endforeach
                                @else
                                    <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                @endif
                            </ul>
                        </nav> --}}
                    </div>
                    <div class="header-bottom-icon toggle-search pull-right"><i class="fa fa-search" aria-hidden="true"></i></div>
                    <a href="{{ url('/wishlist') }}" class="header-bottom-icon visible-rd pull-right">
                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                        <span class="cart-label" v-text="wishlistCount">0</span>
                    </a>
                    <a href="{{ url('/cart') }}" class="header-bottom-icon visible-rd pull-right">
                        <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                        <span class="cart-label" v-text="cart.count">0</span>
                    </a>
                </div>
            </div>
            <div class="header-search-wrapper">
                <div class="header-search-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 header-search">
                                <header-search :categories="{{ json_encode($categories) }}">
                                </header-search>
                            </div>
                        </div>
                    </div>
                    <div class="button-close"></div>
                </div>
            </div>
        </div>
    </div>
    <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="d-none">
        @csrf
    </form>

</header>

<div class="header-empty-space"></div>
