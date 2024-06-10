@extends('layouts.auth')

@section('title')
    {{ __('Partner Confirmation') }}
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
                                    <h2>{{ __('Partner Confirmation') }}</h2>
                                </div>
                                @php
                                $partner_email  = $info->email ?? '';
                                $partner_number  = $info->partner->mobile_number ?? '';
                                @endphp
                                <!-- otp up form -->
                                <form class="basicform" method="get" action="{{ route('admin.partner.updatemobile', $info->id) }}">
                                    @csrf
                                    <div id="signup-form" class="login-form animated preFadeInLeft fadeInLeft">
                                        <div class="field pb-10">
                                            <div class="control has-icons-right required">
                                                <input class="input is-medium has-shadow" type="email" name="email"
                                                    id="email" value="{{ $info->email ?? '' }}"
                                                    placeholder="{{ __('Email') }}" required />
                                                <span class="icon is-medium is-right">
                                                    <i class="sl sl-icon-envelope-open"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="field pb-10">
                                            <div class="control has-icons-right required">
                                                <input class="input is-medium has-shadow" type="text"
                                                    name="mobile_number" id="mobile_number" maxlength="10"
                                                    placeholder="{{ __('Enter Mobile Number') }}" required
                                                    value="{{ $info->partner->mobile_number ?? '' }}" />
                                                <span class="icon is-medium is-right">
                                                    <i class="sl sl-icon-phone"></i>
                                                </span>
                                            </div>

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
    <script type="text/javascript">
        "use strict";
       
         $(document).ready(function () {
            var partner_email = '<?php echo $partner_email; ?>';
            var partner_number = '<?php echo $partner_number; ?>';
            var mobile_number = $('#mobile_number').val();
            var email = $('#email').val();
            if(partner_email == email){
                $("#email").attr("disabled", "disabled");  
            }
            if(mobile_number == ''){
                $("#mobile_number").removeAttr("disabled"); 
            }
            else{
                $("#mobile_number").attr("disabled", "disabled"); 
            }
        $(".basicform").on('submit', function(e) {
            var mobile_number = $('#mobile_number').val();
            var email = $('#email').val();
            // //mobile validation
            var number = /^[0-9]+$/;
            var email_validation = /^[a-zA-Z0-9.!#$%&'+/=?^_`{|}~-]+@[a-zA-Z]+(?:\.[a-zA-Z]+)$/;
            if (mobile_number != "") {
                if (mobile_number.match(number)) {
                    if (mobile_number.length != 10) {
                        $('#mobile_number').focus();
                        Sweet('error', '{{ __('Please Enter Mobile Number 10 Digit only') }}');
                        return false;
                    }
                } else {
                    $('#mobile_number').focus();
                    Sweet('error', '{{ __('Please Enter Valid Mobile Number Only') }}');
                    return false;
                }
            }
            if (email != '') {
                if (!email.match(email_validation)) {
                    $('#email').focus();
                    Sweet('error', '{{ __('Please Enter Valid Email') }}');
                    return false;
                }

            } else {
                $('#email').focus();
                Sweet('error', '{{ __('Please Enter Email') }}');
                return false;
            }

            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var basicbtnhtml = $('.basicbtn').html();
            $.ajax({
                type: 'GET',
                url: this.action,
                data:  {'mobile_number': mobile_number,
                    'email': email},
                dataType: 'json',
                beforeSend: function() {

                    $('.basicbtn').html("Please Wait....");
                    $('.basicbtn').attr('disabled', '')

                },

                success: function(response) {
                  
                    $('.basicbtn').removeAttr('disabled')
                    Sweet('success', '{{ __("Check Your Mail For Verify OTP") }}');
                    $('.basicbtn').html(basicbtnhtml);

                    if (response.redirect == true) {
                        window.location.href = response.domain;
                    }

                },
                error: function(xhr, status, error) {
                  
                    $('.basicbtn').html(basicbtnhtml);
                    $('.basicbtn').removeAttr('disabled')
                    $('.errorarea').removeClass('is-hidden');
                    $.each(xhr.responseJSON.errors, function(key, item) {
                        Sweet('error', item)
                        $("#errors").html("<li class='text-danger'>" + item + "</li>")
                    });
                    errosresponse(xhr, status, error);
                }
            })


        });
    });
    </script>
@endpush
