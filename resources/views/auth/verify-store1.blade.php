<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ env('APP_NAME') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ get_admin_favicon() }}">
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
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
        .login-brand img{
            max-width: 200px !important;
        }

    </style>
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div
                        class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-6 offset-xl-3">
                        <div class="login-brand">
                            <a href="{{ url('/') }}"><img src="{{ admin_logo_url() }}"
                                    alt="{{ env('APP_NAME') }}"></a>
                        </div>

                        <div class="card card-success">
                            <div class="card-header">
                                <h4>{{ __('Verify Store') }}</h4>
                            </div>
                            <form class="basicform" method="post" action="{{ route('verify.store_name') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="shop-name">{{ __('Shop Name') }}</label>
                                                <input id="shop_name" type="text" class="form-control" name="shop_name"
                                                    placeholder="{{ __('Shop Name') }}" />
                                                <!-- <div class="input-group mb-2">
                                                    <input type="text" id="shop_name" class="form-control" name="shop_name" placeholder="{{ __('Shop Name') }}">
                                                    <div class="input-group-append">
                                                    <div class="input-group-text">.{{ env('APP_PROTOCOLESS_URL') }}</div>
                                                    </div>
                                                </div> -->
                                                                        <!-- <div id="shopNameHelp" class="form-text text-muted none">
                                                    {{ __('Store Url') }} : <span class="store-name"></span>.{{ env('APP_PROTOCOLESS_URL') }}</div> -->
                                                <div class="shop-helper none"></div>
                                                <br>
                                                <div class="text-center">
                                                    Not registered yet?
                                                    <a href="{{ route('merchant.form', 1) }}">
                                                    {{ __(' Create a Store') }}
                                                    </a>
                                                </div>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-lg btn-block basicbtn">
                                            {{ __('Verify') }}
                                        </button>
                                    </div>
                            </form>
                        </div>
                    </div>
                    <div class="simple-footer">
                        Copyright &copy; {{ env('APP_NAME') }} {{ date('Y') }}
                    </div>
                </div>
            </div>
    </div>
    </section>
    </div>
    <!-- General JS Scripts -->
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>

    <script type="text/javascript">
        "use strict";

        $('#shop_name').keyup(function () {
            var value = $(this).val();
            var value = value.replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
            $(this).val(value);
            if (value.length > 0) {
                $('#shopNameHelp').show();
                $('span.store-name').text(value);
            } else {
                $('#shopNameHelp').hide();
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
                    $('.shop-helper').removeClass('success-txt error-txt').hide();
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
                    $('.basicbtn').html(basicbtnhtml);
                    $('.basicbtn').removeAttr('disabled')
                    $('.errorarea').show();
                    if (xhr.responseJSON.message) {
                        $('.shop-helper').text(xhr.responseJSON.message).addClass('error-txt')
                        .show();
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
    <style>
        .form-shop-name {
            font-weight: 600;
            color: #34395e;
            font-size: 12px;
            letter-spacing: .5px;
        }

    </style>
</body>

</html>
