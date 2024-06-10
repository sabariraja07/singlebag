@extends('layouts.auth')

@section('title') {{'Singlebag Login | Enter your store name'}} @endsection
@push('css')
<style>
    .store-name{
      color:green !important;
    }
    #shopNameHelp{
      font-size: 14px !important;
    }
    .error-txt{
      color: red;
      font-size: 14px !important;
    }
    .success-txt{
      color: green;
      font-size: 14px !important;
    }
    .login-brand img{
      max-width: 200px !important;
    }
    .form-shop-name{
        font-weight: 600;
        color: #34395e;
        font-size: 12px;
        letter-spacing: .5px;
    }
</style>
@endpush
@section('content')
<div class="login-wrapper columns is-gapless">
    <!-- Form section -->
    <div class="column is-7">
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
                                        <img class="top-logo switcher-logo" src="{{ admin_logo_url() }}" alt="" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column"></div>
                        <div class="column is-5">
                            <div class="auth-content">
                                <h2>Enter your store name.</h2>
                                <!-- <p>Please start by creating an account.</p> -->
                                <a href="{{ route('seller.signup') }}">Not registered yet? {{ __(' Create a Store') }} </a>
                            </div>

                            <!-- Sign up form -->
                            <form class="basicform"  method="post" action="{{ route('verify.store_name') }}">
                                @csrf
                                <div id="signup-form" class="login-form animated preFadeInLeft fadeInLeft">
                                    <div class="field pb-10">
                                        <div class="control has-icons-right required @if ($errors->has('shop_name')) has-error @endif">
                                            <input id="shop_name" class="input is-medium has-shadow"
                                                name="shop_name"  type="text"
                                                value="{{ old('shop_name') }}"
                                                placeholder="{{ __('Shop Name') }}" required/>
                                            <span class="icon is-medium is-right">
                                                <i class="fa fa-store"></i>
                                            </span>
                                        </div>
                                        @if ($errors->has('shop_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('shop_name') }}</strong>
                                        </span>
                                        @endif
                                        <div class="shop-helper is-hidden"></div>
                                    </div>
                                    @if(env('NOCAPTCHA_SITEKEY') != null)
                                    <div class="field pb-10">
                                      <div class="control">
                                          {!! NoCaptcha::renderJs() !!}
                                          {!! NoCaptcha::display() !!}
                                      </div>
                                    </div>
                                    @endif
                                    <p class="control login">
                                        <button class="button button-cta primary-btn btn-align-lg btn-outlined is-bold is-fullwidth rounded raised no-lh">
                                        {{ __('Verify') }}
                                        </button>
                                    </p>
                                </div>
                            </form>
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
                    <h2 class="title is-2 light-text">Start creating store now</h2>
                    <h3 class="subtitle is-5 light-text">
                    Stop struggling with manual order managements and focus on the real choke points. 
                    Discover a full featured E-Commerce management platform.
                    </h3>
                    <div class="mt-30 has-text-centered">
                        <a href="{{ route('seller.signup') }}" class="button button-cta basicbtn btn-outlined is-bold light-btn rounded">
                            Get started
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
<script src="{{ asset('assets/js/scripts.js') }}"></script>

<script type="text/javascript">
    "use strict";

    $('#shop_name').keyup(function () {
        var value = $(this).val();
        var value = value.replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
        $(this).val(value);
        if (value.length > 0) {
            $('#shopNameHelp').removeClass('is-hidden');
            $('span.store-name').text(value);
        } else {
            $('#shopNameHelp').addClass('is-hidden');
        }
    });

    // $('shop_name').on('change', function(){

    // });

    $(".basicform").on('submit', function (e) {
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
            beforeSend: function () {
                $('.basicbtn').html("Please Wait....");
                $('.basicbtn').attr('disabled', '')
                $('.shop-helper').addClass('is-hidden').removeClass('success-txt error-txt');
            },

            success: function (response) {
                $('.basicbtn').removeAttr('disabled');
                $('.basicbtn').html(basicbtnhtml);
                console.log(response.domain);
                if (response.domain) {
                    window.location.href = response.domain;
                }
            },
            error: function (xhr, status, error) {
                if($(".g-recaptcha").length > 0) {
                    grecaptcha.reset();
                }
                $('.basicbtn').html(basicbtnhtml);
                $('.basicbtn').removeAttr('disabled')
                $('.errorarea').removeClass('is-hidden');
                $.each(xhr.responseJSON.errors, function (key, item) 
				{
					Sweet('error',item)
					$("#errors").html("<li class='text-danger'>"+item+"</li>")
				});
                if (xhr.responseJSON.message) {
                    $('.shop-helper').text(xhr.responseJSON.message).addClass('error-txt')
                    .removeClass('is-hidden');
                }
            }
        })


    });

    function Sweet(icon, title, time = 3000) {

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: time,
            timerProgressBar: true,
            onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })


        Toast.fire({
            icon: icon,
            title: title,
        })
    }

</script>
@endpush
