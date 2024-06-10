@extends('layouts.auth')

@section('title') {{'Reset Password'}} @endsection

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
                        <div class="column">
                            <div id="signup-card" class="animated preFadeInLeft fadeInLeft">
                                <div class="flex-card wavy-signup-card">
                                    <h2 class="has-text-centered text-bold no-margin-bottom pt-20 pb-20">
                                        {{ __('Reset Password') }}
                                    </h2>
                                    <form method="POST" action="{{ route('password.update') }}">
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $token }}"/>
                                        <div id="recover-form" class="login-form animated preFadeInLeft fadeInLeft">
                                            @if (session('status'))
                                            <div style="color: green;margin-bottom:20px;" role="alert">
                                                {{ session('status') }}
                                            </div>
                                            @endif
                                            <!-- Input -->
                                            <div class="field pb-10">
                                                <div
                                                    class="control has-icons-right required @if ($errors->has('email')) has-error @endif">
                                                    <input class="input is-medium has-shadow" type="email" name="email"
                                                        value="{{ $email ?? old('email') }}" required
                                                        autocomplete="email" autofocus
                                                        placeholder="{{ __('Email') }}" />
                                                    <span class="icon is-medium is-right">
                                                        <i class="sl sl-icon-envelope-open"></i>
                                                    </span>
                                                </div>
                                                @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                            <div class="field pb-10">
                                                <div
                                                    class="control has-icons-right required @if ($errors->has('password')) has-error @endif">
                                                    <input class="input is-medium has-shadow" type="password"
                                                        name="password" placeholder="{{ __('Password') }}" />
                                                    <span class="icon is-medium is-right">
                                                        <i class="sl sl-icon-lock"></i>
                                                    </span>
                                                </div>
                                                @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                            <div class="field pb-10">
                                                <div class="control has-icons-right">
                                                    <input class="input is-medium has-shadow" type="password"
                                                        name="password_confirmation"
                                                        placeholder="{{ __('Password Confirmation') }}" />
                                                    <span class="icon is-medium is-right">
                                                        <i class="sl sl-icon-lock"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <!-- Submit -->
                                            <p class="control login">
                                                <button
                                                    class="button button-cta basicbtn primary-btn btn-align-lg is-bold is-fullwidth rounded raised no-lh">
                                                    Reset password
                                                </button>
                                            </p>
                                        </div>
                                    </form>
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
<script src="{{ asset('assets/js/scripts.js') }}"></script>
@endpush
