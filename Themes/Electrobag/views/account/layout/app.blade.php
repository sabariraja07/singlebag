@extends('electrobag::layouts.app')
@section('content')

<div class="container">
    <div class="empty-space col-xs-b15 col-sm-b30"></div>
    <div class="breadcrumbs">
        <a href="{{ url('/') }}" rel="nofollow">{{ __('Home') }}</a>
        {{-- @if (request()->routeIs('account.dashboard'))
            <a href="{{ route('account.dashboard') }}">{{ __('My Account') }}</a>
        @endif --}}
        @yield('account_breadcrumb')
    </div>
    <div class="empty-space col-xs-b15 col-sm-b30"></div>
    <div class="text-center">
        <div class="h2">@yield('account_title')</div>
        <div class="title-underline center"><span></span></div>
    </div>
    <div class="empty-space col-xs-b35 col-md-b70"></div>
    <div class="tabs-block">
        <div class="tabulation-menu-wrapper text-center">
            <div class="tabulation-title simple-input">description</div>
            <ul class="tabulation-toggle">
                <li><a class="tab-menu {{ request()->is('user/dashboard') ? 'active' : '' }}"
                        href="{{ url('/user/dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li><a class="tab-menu {{request()->is('user/orders') || request()->is('user/order*') ? 'active' : '' }}"
                        href="{{ url('/user/orders') }}"><i
                            class="fi-rs-shopping-bag mr-10"></i>{{ __('Orders') }}</a></li>
                <li><a class="tab-menu {{ request()->is('user/settings') ? 'active' : '' }}"
                        href="{{ url('/user/settings') }}">{{ __('Account details') }}</a></li>
                <li><a class="tab-menu" href="{{ url('/logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                </li>
            </ul>
        </div>
        <div class="empty-space col-xs-b30 col-sm-b60"></div>
        <div class="tab-entry visible">

            @yield('user_content')

        </div>
    </div>
    <div class="empty-space col-xs-b35 col-md-b70"></div>
</div>
@endsection
