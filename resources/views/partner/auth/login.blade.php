@extends('layouts.auth')

@section('title') {{ __('Partner Login') }} @endsection
@section('vendor-style')
    <style>
        .field-icon {
            float: right;
            margin-left: -25px;
            margin-top: -25px;
            position: relative;
            z-index: 2;
        }
    </style>
@endsection

@section('content')
<div class="login-wrapper columns is-gapless">
    <!-- Form section -->
    <div class="column is-7">
        <div class="hero is-fullheight">
            <!-- <div class="hero-heading">
                <div class="auth-logo">
                    <a href="{{ url('/') }}">
                        <img class="top-logo switcher-logo" src="{{ admin_logo_url() }}" alt="" />
                    </a>
                </div>
            </div> -->
            <div class="hero-body">
                <div class="container">
                <div class="columns">
                        <div class="column">
                            <div class="hero-heading">
                                <div class="auth-logo">
                                    <a href="{{ url('/') }}">
                                        <img class="top-logo switcher-logo" src="{{ admin_logo_url() }}"
                                            alt="" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column"></div>
                        <div class="column is-5">
                            <div class="auth-content">
                                <h2>{{ __('Welcome Back.') }}</h2>
                                <p>{{ __('Please sign in to your account') }}</p>
                                <a href="{{ route('partner.register') }}">{{ __('I do not have an account yet') }} </a>
                            </div>

                            <!-- Login Form -->
                            <form method="POST" class="needs-validation" action="{{ route('partner.login') }}">
                                @csrf
                                <div id="signin-form" class="login-form animated preFadeInLeft fadeInLeft">
                                    <!-- Input -->
                                    <div class="field pb-10">
                                        <div class="control has-icons-right">
                                            <input class="input is-medium has-shadow" type="text"
                                                name="email"
                                                value="{{ old('email') }}"
                                                placeholder="Email" required/>
                                            <span class="icon is-medium is-right">
                                                <i class="sl sl-icon-user"></i>
                                            </span>
                                        </div>
                                        @if(Session::has('error'))
                                        <div class="invalid-feedback" style="color: red;">
                                        {{Session::get('error')}}
                                        </div>
                                        @endif
                                    </div>
                                    <!-- Input -->
                                    <div class="field pb-10">
                                        <div class="control has-icons-right">
                                            <input class="input is-medium has-shadow" id="password-field" type="password"
                                                name="password"
                                                placeholder="Password" required/>
                                                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password icon is-right" style="pointer-events: visible;"></span>
                                        </div>
                                    </div>
                                    @if(env('NOCAPTCHA_SITEKEY') != null)
                                    <div class="field pb-10">
                                      <div class="control">
                                          {!! NoCaptcha::renderJs() !!}
                                          {!! NoCaptcha::display() !!}
                                          @if ($errors->has('g-recaptcha-response'))
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                      </span>
                                      @endif
                                      </div>
                                    </div>
                                    @endif
                                    <div class="field remember-wrap is-grouped pt-20">
                                        <div class="control">
                                            <label class="slide-toggle">
                                                <input type="checkbox" name="remember" id="remember-me" {{ old('remember') ? 'checked' : '' }}/>
                                                <span class="toggler">
                                                    <span class="active">
                                                        <i data-feather="check"></i>
                                                    </span>
                                                <span class="inactive">
                                                    <i data-feather="circle"></i>
                                                </span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="control">
                                            <span style="max-width: 100%;">{{ __('Remember me') }}?</span>
                                        </div>
                                    </div>
                                    <!-- Submit -->
                                    <p class="control login">
                                        <button type="submit" class="button button-cta basicbtn primary-btn btn-align-lg is-bold is-fullwidth rounded raised no-lh">
                                            {{ __('Login') }}
                                        </button>
                                    </p>
                                </div>
                            </form>

                          
                            <!-- Toggles -->
                            <div class="
                          pt-10
                          pb-10
                          forgot-password
                          animated
                          preFadeInLeft
                          fadeInLeft
                        ">
                                <p class="has-text-centered">
                                    <a href="{{ route('password.request') }}">{{ __('Forgot Password?') }}</a>
                                </p>
                            </div>
                        </div>
                        <div class="column"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image section (hidden on mobile) -->
    <div class="column login-column is-5 is-hidden-mobile hero-banner">
        <div class="hero is-fullheight is-theme-primary is-relative">
            <div class="columns has-text-centered">
                <div class="column">
                    <h2 class="title is-2 light-text">{{ __('Become a partner now') }}</h2>
                    <h3 class="subtitle is-5 light-text">
                        {!!  __('Your expertise is our entrepreneur’s growth. <br/>
                        Help merchants to excel in e-commerce by introducing Singlebag.') !!}
                    </h3>
                    <div class="mt-30 has-text-centered">
                        <a href="{{ route('partner.register') }}" class="button button-cta btn-outlined is-bold light-btn rounded">
                            {{ __('Get started') }}
                        </a>
                    </div>
                </div>
            </div>
            <img class="login-city" src="{{ asset('assets/img/auth/login_verify_banner-svg.svg') }}" alt="" />
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/js/form.js') }}"></script>
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
