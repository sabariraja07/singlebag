@extends('layouts.auth')

@section('title')
    {{ __('OTP Error Page') }}
@endsection
@push('css')
    <style>
        .store-name {
            color: green !important;
        }

        #shopNameHelp {
            font-size: 14px !important;
        }

        .error-txt {
            color: red;
            font-size: 14px !important;
        }

        .success-txt {
            color: green;
            font-size: 14px !important;
        }

        .login-brand img {
            max-width: 200px !important;
        }

        .form-shop-name {
            font-weight: 600;
            color: #34395e;
            font-size: 12px;
            letter-spacing: .5px;
        }

        .signup-hero img.mockup {
            position: absolute;
            bottom: 5%;
            left: 0;
            right: 0;
            z-index: 0;
            max-width: 80%;
            margin: 0 auto;
        }

        .signup-hero img.mockup-alt {
            position: absolute;
            bottom: -15px;
            margin: 0 auto;
            width: 60%;
            left: 0;
            right: 0;
        }

        @media screen and (max-height: 600px) {
            .signup-hero img.mockup-alt {
                width: 40%;
            }
        }
    </style>
@endpush
@section('content')
    <div class="login-wrapper columns is-gapless">
        <!-- Form section -->
        <div class="column is-5">
            <div class="hero is-fullheight">
                <!-- Header -->
                <!-- Body -->
                <div class="hero-body">
                    <div class="container">
                        <div class="columns">
                            <div class="column">
                                <div class="hero-heading">
                                    <div class="auth-logo">
                                        <a href="https://singlebag.com/">
                                            <img class="top-logo switcher-logo" src="{{ admin_logo_url() }}"
                                                alt="" />
                                        </a>
                                        <h2 style="color: #5e5873;font-size: 35px; margin-bottom: 1rem !important;">Mail Not
                                            Sent ⛔</h2>
                                        <p style="font-size:15px;color:#6e6b7b;margin-bottom: 2rem !important;">Oops! 😖 The
                                            OTP mail is not sent. Please try again after sometime or contact our support 📞.
                                        </p>
                                        <button onclick="location.href='https://singlebag.com/partner-program/'"
                                            class="button button-cta basicbtn primary-btn btn-align-lg btn-outlined is-bold is-fullwidth rounded raised no-lh">
                                            Back to home
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Image section (hidden on mobile) -->
        <div class="column login-column is-7 is-hidden-mobile hero-banner">
            <div class="hero signup-hero is-fullheight is-theme-primary is-relative">
                <div class="columns has-text-centered">
                    <div class="column">
                        <h1 class="title is-2 light-text">{{ __('Start associating') }}</h1>
                        <h3 class="subtitle is-5 light-text">
                            {{ __('Shoulder the responsibility of introducing fresh merchants to Singlebag and offering solutions that help them initiate, trade, sell, and manage their businesses.') }}
                        </h3>
                        <!-- App mockup -->
                        <img class="mockup-alt" src="{{ asset('assets/img/auth/signup_banner.png') }}" alt="" />

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
