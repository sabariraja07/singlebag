@extends('layouts.auth')

@section('title')
    {{ __('OTP') }}
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
                <!-- <div class="hero-heading">
                                                <div class="auth-logo">
                                                    <a href="{{ url('/') }}">
                                                        <img class="top-logo switcher-logo" src="{{ admin_logo_url() }}" alt="" />
                                                    </a>
                                                </div>
                                            </div> -->

                <!-- Body -->
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
                            <div class="column is-8 is-offset-2" id="partner-form">
                                <div class="auth-content">
                                    <h2>{{ __('OTP Confirmation') }}</h2>
                                </div>
                                @php
                                    function hideEmailAddress($email)
                                    {
                                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                            [$first, $last] = explode('@', $email);
                                            $first = str_replace(substr($first, '3'), str_repeat('*', strlen($first) - 3), $first);
                                            $last = explode('.', $last);
                                            $last_domain = str_replace(substr($last['0'], '1'), str_repeat('*', strlen($last['0']) - 1), $last['0']);
                                            $hideEmailAddress = $first . '@' . $last_domain . '.' . $last['1'];
                                            return $hideEmailAddress;
                                        }
                                    }
                                    
                                    $email = $info->email;
                                    $get_email = hideEmailAddress($email);
                                @endphp
                                <!-- otp up form -->
                                <form class="basicform" method="post" action="{{ route('admin.otp_verify', $info->id) }}">
                                    @csrf
                                    <div id="signup-form" class="login-form animated preFadeInLeft fadeInLeft">
                                        <div class="field pb-10">
                                            <div class="control has-icons-right required">
                                                <input class="input is-medium has-shadow" type="text" name="otp_verify"
                                                    id="otp_verify" maxlength="4" placeholder="{{ __('Enter OTP') }}"
                                                    required />
                                                <span class="icon is-medium is-right">
                                                    <i class="sl sl-icon-pin"></i>
                                                </span>
                                            </div><br>
                                            <p style="color: red;
                                            text-align: center;
                                            font-size: 16px;">OTP has been sent to <span style="color:green"> {{ $get_email ?? '' }}</span></p>

                                        </div>
                                        <p class="control login">
                                            <button
                                                class="button button-cta basicbtn primary-btn btn-align-lg btn-outlined is-bold is-fullwidth rounded raised no-lh">
                                                Submit
                                            </button>
                                        </p>
                                    </div>
                                </form>
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
@push('js')
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#otp").hide();
        });
    </script>
    <script type="text/javascript">
        "use strict";

        $(".basicform").on('submit', function(e) {
            var otp_verify = $('#otp_verify').val();
            //otp validation
            var number = /^[0-9]+$/;
            if (otp_verify != "") {
                if (otp_verify.match(number)) {
                    if (otp_verify.length != 4) {
                        $('#otp_verify').focus();
                        Sweet('error', '{{ __('Please Enter OTP 4 Digit only') }}');
                        return false;
                    }
                } else {
                    $('#otp_verify').focus();
                    Sweet('error', '{{ __('Please Enter Valid OTP Only') }}');
                    return false;
                }
            }

            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var basicbtnhtml = $('.basicbtn').html();
            $.ajax({
                type: 'POST',
                url: this.action,
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {

                    $('.basicbtn').html("Please Wait....");
                    $('.basicbtn').attr('disabled', '');

                },

                success: function(response) {
                    $('.basicbtn').removeAttr('disabled');
                    $('.basicbtn').html(basicbtnhtml);
                    if (response.redirect == true) {
                        Sweet('success', '{{ __('OTP Verified') }}');
                        window.location.href = response.domain;
                    } else {
                        Sweet('error', '{{ __('OTP Mismatch') }}');
                    }

                },

            })


        });
    </script>
@endpush
