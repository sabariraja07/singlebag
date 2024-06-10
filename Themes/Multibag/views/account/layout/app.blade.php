@extends('multibag::layouts.app')
@section('content')
@section('breadcrumb')
    @if (request()->routeIs('account.dashboard'))
        @php
        $title = 'My Account';
        @endphp
		<li class="active">{{__('My Account')}}</li>
    @else
    <li><a href="{{ route('account.dashboard') }}">{{ __('My Account') }}</a><li>
    <li><span> > </span></li>
    @endif

    @yield('account_breadcrumb')
@endsection
<div class="my-account-wrapper bg-gray pt-160 pb-160">
  <div class="container">
      <div class="row">
          <div class="col-lg-12">
              <!-- My Account Page Start -->
              <div class="myaccount-page-wrapper">
                  <!-- My Account Tab Menu Start -->
                  <div class="row">
                      <div class="col-lg-3 col-md-4">
                          <div class="myaccount-tab-menu nav" role="tablist">
                              <a href="{{ url('/user/dashboard') }}" class="@if(url()->current() == url('/user/dashboard')) active @endif">{{ __('Dashboard') }}</a>
                              <a href="{{ url('/user/orders') }}" class="@if(request()->is('user/order*')) active @endif"> {{ __('Orders') }}</a>
                              {{-- <a href="#download">Download</a> --}}
                              {{-- <a href="#payment-method"> Payment
                                  Method</a>
                              <a href="#address-edit">address</a> --}}
                              <a href="{{ url('/user/settings') }}" class="@if(url()->current() == url('/user/settings')) active @endif">{{ __('Account details') }}</a>
                              <a href="{{ url('/logout') }}" onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                          </div>
                      </div>
                      <!-- My Account Tab Menu End -->
                      <!-- My Account Tab Content Start -->
                      <div class="col-lg-9 col-md-8">
                          <div class="tab-content" id="myaccountContent">
                              <!-- Single Tab Content Start -->
                              <div class="tab-pane fade show active" id="dashboad" role="tabpanel">
                                @yield('user_content')
                              </div>
                              <!-- Single Tab Content End -->
                          </div>
                      </div> <!-- My Account Tab Content End -->
                  </div>
              </div> <!-- My Account Page End -->
          </div>
      </div>
  </div>
</div>
@endsection