@extends('multibag::layouts.app')
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
<li class="active"> Login </li>
@endsection
<div class="login-register-area bg-gray pt-30 pb-160">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-12 ms-auto me-auto">
                <div class="login-register-wrapper">
                    {{-- <div class="login-register-tab-list nav">
                        <a class="active" data-bs-toggle="tab" href="#lg1">
                            <h4> login </h4>
                        </a>
                    </div> --}}
                    <div class="tab-content">
                        <div id="lg1" class="tab-pane active">
                            <div class="login-form-container">
                                <div class="login-register-form">
                                    <form action="{{ url('/customer/login') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <input type="email" placeholder="Email"  class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required="">
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
                                        <div class="form-group">
                                        <input type="password" placeholder="Password" id="password-field" name="password" class="form-control" required="">
                                        <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password icon is-right" style="pointer-events: visible;width: 44px;margin-top: -64px;font-size: 17px;"></span>
                                        </div>

                                        {{-- <input type="text" name="user-name" placeholder="Username">
                                        <input type="password" name="user-password" placeholder="Password"> --}}
                                        <div class="button-box">
                                            <div class="login-toggle-btn">
                                                <input type="checkbox" name="remember" id="rememberMe" value="" />
                                                <label for="rememberMe"><span>{{ __('Remember Me') }}</span></label>
                                                <a href="{{ url('/user/password/reset') }}">{{ __('Forgot Password ?') }}</a>
                                            </div>
                                            <button type="submit">{{ __('Sign In') }}</button>
                                        </div>

                                        <div class="login-toggle-btn mt-30">
                                           <span>{{ __('Don\'t have an account?') }}
                                            <a href="{{ url('/user/register') }}">{{ __('Register here') }}</a></span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div id="lg2" class="tab-pane">
                            <div class="login-form-container">
                                <div class="login-register-form">
                                    <form action="#" method="post">
                                        <input type="text" name="user-name" placeholder="Username">
                                        <input type="password" name="user-password" placeholder="Password">
                                        <input name="user-email" placeholder="Email" type="email">
                                        <div class="button-box">
                                            <button type="submit">Register</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection