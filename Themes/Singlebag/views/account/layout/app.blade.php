@extends('singlebag::layouts.app')
@section('content')
@section('breadcrumb')
	<span></span> 
    @if (request()->routeIs('account.dashboard'))
		{{__('My Account')}}
    @else
		<a href="{{ route('account.dashboard') }}">{{ __('My Account') }}</a>
    @endif

    @yield('account_breadcrumb')
@endsection
  <div class="page-content pt-150 pb-150">
    <div class="container">
      <div class="row">
        <div class="col-lg-10 m-auto">
          <div class="row">
            <div class="col-md-3">
              <div class="dashboard-menu">
                <ul class="nav flex-column" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link @if(url()->current() == url('/user/dashboard')) active @endif" href="{{ url('/user/dashboard') }}"><i class="fi-rs-settings-sliders mr-10"></i>{{ __('Dashboard') }}</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link @if(request()->is('user/order*')) active @endif" href="{{ url('/user/orders') }}"><i class="fi-rs-shopping-bag mr-10"></i>{{ __('Orders') }}</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link @if(url()->current() == url('/user/settings')) active @endif" href="{{ url('/user/settings') }}">
                      <i class="fi-rs-user mr-10"></i>{{ __('Account details') }}</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/logout') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                      <i class="fi-rs-sign-out mr-10"></i>
                      {{ __('Logout') }}
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-md-9">
              <div class="tab-content account dashboard-content pl-50">
                @yield('user_content')
               
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection