@extends('electrobag::layouts.app')
@push('css')
<style>
    .field-icon {
        float: right;
        margin-left: -25px;
        margin-top: -25px;
        position: relative;
        z-index: 2;
    }
</style>
@endpush
@section('content')
@section('breadcrumb')
    <span></span> {{ __('Login') }}
@endsection
<div class="container">
    <div class="empty-space col-xs-b15 col-sm-b30"></div>
    <div class="breadcrumbs">
        <a href="{{ url('/') }}">{{ __('Home') }}</a>
        <a href="{{ url('/user/login') }}">{{  __('Login') }}</a>
    </div>
    <div class="empty-space col-xs-b15 col-sm-b50 col-md-b100"></div>

    <div class="text-center">
        <div class="h2">{{ __('Login') }}</div>
        <div class="title-underline center"><span></span></div>
    </div>

    <div class="empty-space col-xs-b15 col-sm-b30"></div>
    <div class="row">
        <div class="col-lg-6 col-md-8 col-md-offset-2 col-lg-offset-3">
            <div style="border: 1px #eee solid; padding: 15px;">
                <div class="login_wrap widget-taber-content background-white">
                    <div class="empty-space col-xs-b15 col-sm-b30"></div>
                    <div class="h5">{{ __('Don\'t have an account?') }} <a
                            href="{{ url('/user/register') }}">{{ __('Register here') }}</a></div>
                    <div class="empty-space col-xs-b15 col-sm-b30"></div>
                    <form action="{{ url('/customer/login') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="email" placeholder="Email"
                                class="simple-input @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required="">
                            @if ($errors->has('email'))
                                <div class="invalid-feedback" style="display: block;">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                            @if (Session::get('message'))
                                <div class="invalid-feedback" style="display: block;">
                                    {{ Session::get('message') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="Password" name="password" id="password-field" class="simple-input"
                                required="">
                            <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password icon is-right" style="pointer-events: visible;width: 44px;margin-top: -38px;font-size: 20px;"></span>
                        </div>
                        <div class="login_footer form-group mb-50">
                            <div class="chek-form">
                                <label class="checkbox-entry">
                                    <input type="checkbox" name="remember" id="rememberMe"
                                        value="" /><span>{{ __('Remember Me') }}</span>
                                </label>

                            </div>
                            <div class="empty-space col-xs-b15 col-sm-b30"></div>
                            <a class="text-muted"
                                href="{{ url('/user/password/reset') }}">{{ __('Forgot Password ?') }}</a>
                            <div class="empty-space col-xs-b15 col-sm-b30"></div>
                        </div>

                        <div class="button size-2 style-3">
                            <span class="button-wrapper">
                                <span class="icon"><img
                                        src="{{ asset('frontend/electrobag/img/icon-4.png') }}"
                                        alt=""></span>
                                <span class="text">{{ __('Sign In') }}</span>
                            </span>
                            <input type="submit" name="login" value="" />
                        </div>
                        <div class="empty-space col-xs-b15 col-sm-b30"></div>
                    </form>
                </div>
            </div>
            <div class="empty-space col-xs-b15 col-sm-b30"></div>
            <div class="empty-space col-xs-b15 col-sm-b30"></div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
        input.attr("type", "text");
        } else {
        input.attr("type", "password");
        }
        });
</script>
@endpush 