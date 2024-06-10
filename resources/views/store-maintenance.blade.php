@extends('main.maintenance')
@section('content')
    <style>
        .error-wrap.error-centered img {
            max-height: 500px !important;
        }

        .error-wrap.error-centered img {
            position: relative !important;
            display: block !important;
            margin: 0 auto !important;
            max-width: 516px !important;
            z-index: 1 !important;
        }
    </style>
    <div class="hero is-relative is-fullheight">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-vcentered">
                    <!-- Error Wrapper -->
                    <div class="column error-wrap error-centered has-text-centered">
                        <div class="error-caption">
                            <h2>We'll Be Back Soon</h2>
                        </div>
                        <img src="{{ asset('assets/img/auth/store-maintenance.png') }}" alt="" />
                        <div class="error-caption">
                            <p style="font-size: 15px !important;">
                                Site is under maintenance and expect to back online very soon.<br>
                                <Span>Please try again later.</Span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
