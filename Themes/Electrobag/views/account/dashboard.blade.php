@extends('electrobag::account.layout.app')

@section('account_title',  __('Dashboard') )
@section('account_breadcrumb')
    <a href="{{ url('/user/dashboard') }}">{{ __('Dashboard') }}</a>
@endsection
@section('user_content')
    <div class="text-center">
        <div class="h5">Hello {{ Auth::guard('customer')->user()->first_name }}!</div>
        <div class="simple-article size-3">
            <p>{{ __('Hello') }} <strong>{{ Auth::guard('customer')->user()->fullname }} ( {!! __('not :full_name ?', ['full_name' => '<strong>' . Auth::guard('customer')->user()->fullname . '</strong>']) !!}
                    <a href="{{ url('/logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>)
            </p>
            <p>{!! __('From your account dashboard you can view your :recent_orders and :edit_password_details .', ['recent_orders' => "<a href='" . url('/user/orders') . "'>" . trans('recent orders') . '</a>', 'edit_password_details' => "<a href='" . url('/user/settings') . "'>" . trans('edit your password and account details') . '</a>']) !!}</p>
        </div>
    </div>
@endsection
