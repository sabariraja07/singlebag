@extends('fashionbag::account.layout.app')
@section('user_content')
    <div class="myaccount-content">
        <h3 class="title">{{ __('Dashboard')}}</h3>
        <div class="welcome">
            <p>{{ __('Hello') }}, <strong>{{ Auth::guard('customer')->user()->first_name }}!</strong>
                (If Not <strong>{{ Auth::guard('customer')->user()->first_name }} !</strong>
                <a href="{{ url('/logout') }}" class="logout"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>)
            </p>
        </div>
        <p class="mb-0">
            {!! __('From your account dashboard you can view your :recent_orders and :edit_password_details .', [
                'recent_orders' => "<a href='" . url('/user/orders') . "'>" . trans('recent orders') . '</a>',
                'edit_password_details' =>
                    "<a href='" . url('/user/settings') . "'>" . trans('edit your password and account details') . '</a>',
            ]) !!}
        </p>
    </div>
@endsection
