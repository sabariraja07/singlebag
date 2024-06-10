@extends('singlebag::layouts.app')
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
<div class="page-content pt-150 pb-150">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-lg-10 col-md-12 m-auto">
                <div class="row">
                    <div class="col-lg-6 pr-30 d-none d-lg-block">
                        <img class="border-radius-15" src="assets/imgs/page/login-1.png" alt="" />
                    </div>
                    <div class="col-lg-6 col-md-8">
                        <div class="login_wrap widget-taber-content background-white">
                            <div class="padding_eight_all bg-white">
                                <div class="heading_s1">
                                    <h1 class="mb-5">{{ __('Login') }}</h1>
                                    <p class="mb-30">{{ __('Don\'t have an account?') }} <a href="{{ url('/user/register') }}">{{ __('Register here') }}</a></p>
                                </div>
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
                                    <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password icon is-right" style="pointer-events: visible;width: 44px;margin-top: -42px;font-size: 20px;"></span>
                                    </div>
                                    <div class="login_footer form-group mb-50">
                                        <div class="chek-form">
                                            <div class="custome-checkbox">
                                                <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" value="" />
                                                <label class="form-check-label" for="rememberMe"><span>{{ __('Remember Me') }}</span></label>
                                            </div>
                                        </div>
                                        <a class="text-muted" href="{{ url('/user/password/reset') }}">{{ __('Forgot Password ?') }}</a>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-heading btn-block hover-up" name="login">{{ __('Sign In') }}</button>
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



