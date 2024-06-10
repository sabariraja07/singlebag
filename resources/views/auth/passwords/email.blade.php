@extends('layouts.auth')

@section('title')
    {{ 'Reset Password' }}
@endsection

@push('css')
    <style>
        .auth-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .hero.is-fullheight .hero-body {
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: start;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }

    </style>
@endpush

@section('content')
    <div class="login-wrapper columns is-gapless">
        <!-- Form section -->
        <div class="column is-12">
            <div class="hero is-fullheight is-app-grey">
                <div class="hero-body">
                    <div class="container">
                        <div class="columns">
                            <div class="column">
                                <div class="hero-heading">
                                    <div class="auth-logo">
                                        <a href="{{ url(env('APP_URL')) }}">
                                            <img class="top-logo switcher-logo" src="{{ admin_logo_url() }}"
                                                alt="" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column"></div>
                            <div class="column is-4">
                                <div id="signup-card" class="animated preFadeInLeft fadeInLeft">
                                    <div class="flex-card wavy-signup-card">
                                        <h2 class="has-text-centered text-bold no-margin-bottom pt-20 pb-20">
                                            Reset Password
                                        </h2>
                                        <form method="POST" action="{{ route('password.email') }}">
                                            @csrf
                                            <div id="recover-form" class="login-form animated preFadeInLeft fadeInLeft">
                                                @if (session('status'))
                                                    <div style="color: green;margin-bottom:20px;" role="alert">
                                                        {{ session('status') }}
                                                    </div>
                                                @endif
                                                @if (session('error'))
                                                    <div style="color: red;margin-bottom:20px;" role="alert">
                                                        {{ session('error') }}
                                                    </div>
                                                @endif
                                                @if ($errors->any())
                                                    @foreach ($errors->all() as $error)
                                                        <div style="color: red;margin-bottom:20px;" role="alert">{{$error}}</div>
                                                    @endforeach
                                                @endif
                                                <!-- Input -->
                                                <div class="field pb-10">
                                                    <div
                                                        class="control has-icons-right required @error('email') has-error @enderror">
                                                        <input class="input is-medium has-shadow" name="email" type="email"
                                                            id="email_address" placeholder="{{ __('E-Mail Address') }}"
                                                            value="{{ old('email') }}" autocomplete="email" required
                                                            autofocus />
                                                        <span class="icon is-medium is-right">
                                                            <i class="sl sl-icon-envelope-open"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                @if (env('NOCAPTCHA_SITEKEY') != null)
                                                    <div class="field pb-10">
                                                        <div class="control">
                                                            {!! NoCaptcha::renderJs() !!}
                                                            {!! NoCaptcha::display() !!}
                                                        </div>
                                                    </div>
                                                @endif
                                                <!-- Submit -->
                                                <p class="control login">
                                                    <button
                                                        class="button button-cta basicbtn primary-btn btn-align-lg is-bold is-fullwidth rounded raised no-lh"
                                                        id="reset_click">
                                                        {{ __('Send Password Reset Link') }}
                                                    </button>
                                                </p>
                                            </div>
                                        </form>

                                        <div class="pt-10 pb-10 forgot-password animated preFadeInLeft fadeInLeft">
                                            <p class="has-text-centered">
                                                <a href="{{ url('/login') }}">Back to Sign in</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Reset Form -->
                            </div>
                            <div class="column"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush
