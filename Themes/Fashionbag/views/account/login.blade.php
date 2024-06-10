@extends('fashionbag::layouts.app')
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
@php
$title = 'Login';
$type = 1;
@endphp
<li class="active"> {{__('Login')}} </li>
@endsection
<div class="section section-margin">
    <div class="container">

        <div class="row mb-n10">
            <div class="col-lg-6 col-md-8 m-auto pb-10">
                <!-- Login Wrapper Start -->
                <div class="login-wrapper">

                    <!-- Login Title & Content Start -->
                    <div class="section-content text-center mb-5">
                        <h2 class="title mb-2">{{ __('Login') }}</h2>
                        <p class="desc-content">
                            <span>{{ __('Don\'t have an account?') }}
                            <a href="{{ url('/user/register') }}">{{ __('Register here') }}</a></span>
                        </p>
                    </div>
                    <!-- Login Title & Content End -->

                    <!-- Form Action Start -->
                    <form action="{{ url('/customer/login') }}" method="POST">
                        @csrf
                         <!-- Input Email Start -->
                         <div class="single-input-item mb-3">
                            <input type="email" placeholder="{{__('Email')}}"  class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required="">
                            @if($errors->has('email'))
                            <div class="invalid-feedback" style="display: block;">
                                {{ $errors->first('email') }}
                            </div>
                            @endif
                            @if(Session::get('message'))
                            <div class="invalid-feedback" style="display: block;">
                            {{ Session::get('message') }}
                            </div>
                            @endif
                        </div>
                        <!-- Input Email End -->

                        <!-- Input Password Start -->
                        <div class="single-input-item mb-3">
                            <input type="password" type="password" id="password-field" placeholder="{{ __('Password') }}" name="password" required="">
                            <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password icon is-right" style="pointer-events: visible;width: 44px;margin-top: -50px;font-size: 17px;"></span>
                        </div>
                        <!-- Input Password End -->

                        <!-- Checkbox/Forget Password Start -->
                        <div class="single-input-item mb-3">
                            <div class="login-reg-form-meta d-flex align-items-center justify-content-between">
                                <div class="remember-meta mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="remember" id="rememberMe">
                                        <label class="custom-control-label" for="rememberMe">{{ __('Remember Me') }}</label>
                                    </div>
                                </div>
                                <a href="{{ url('/user/password/reset') }}" class="forget-pwd mb-3">{{ __('Forgot Password ?') }}</a>
                            </div>
                        </div>
                        <!-- Checkbox/Forget Password End -->

                        <!-- Login Button Start -->
                        <div class="single-input-item mb-3">
                            <button class="btn btn btn-fb btn-hover-primary rounded-0">{{ __('Sign In') }}</button>
                        </div>
                        <!-- Login Button End -->

                        <!-- Lost Password & Creat New Account Start -->
                        {{-- <div class="lost-password">
                            <a href="{{ url('user/register') }}">{{ __('Create Account') }}</a>
                        </div> --}}
                        <!-- Lost Password & Creat New Account End -->

                    </form>
                    <!-- Form Action End -->

                </div>
                <!-- Login Wrapper End -->
            </div>
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




