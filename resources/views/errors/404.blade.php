
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>404 - {{ env('APP_NAME') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ get_admin_favicon() }}">

    <!--Core CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/green.css') }}">
</head>

<body class="is-theme-green">
    {{-- <div class="pageloader"></div>
    <div class="infraloader is-active"></div> --}}
    @include('components.pageloader')
    <!-- Hero and nav -->
    <div class="hero is-relative is-fullheight">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-gapless is-vcentered">
                    <!-- Error Wrapper -->
                    <div class="column error-wrap error-centered has-text-centered">
                        <div class="bg-404">404</div>
                        <img src="{{ asset('assets/img/auth/error-1-green.svg') }}" alt="" />
                        <div class="error-caption">
                            <h2>Oops, We couldn't find that page.</h2>
                            <p>
                                Please try again or contact the website administrator to get some
                                help.
                            </p>
                            <div class="button-wrap">
                                <a href="{{ URL::previous() }}" class="
                      button button-cta
                      btn-outlined
                      is-bold
                      btn-align
                      primary-btn
                      rounded
                      raised
                    ">
                                    Back to Homepage
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Back To Top Button -->
    <div id="backtotop"><a href="#"></a></div>
    <script src="{{ asset('asset/js/app.js') }}"></script>
    <script src="{{ asset('asset/js/core.js') }}"></script>
</body>

</html>