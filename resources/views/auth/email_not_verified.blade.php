@extends('layouts.partials.auth')
@section('title')
    {{ 'Email Not Verified' }}
@endsection
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="auth-wrapper auth-basic px-2">
                    <div class="auth-inner my-2">
                        <!-- verify email basic -->
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="/" class="brand-logo">
                                    <img src="./images/logo.png" alt="" srcset="" style="max-width: 250px">
                                </a>
                                        <div id="signup-form" class="login-form animated preFadeInLeft fadeInLeft">
                                            <div class="field pb-10">
                                                <div class="control has-icons-right required">
                                                <h4 style="color: #5e5873; margin-bottom: 1rem !important;">Oops... Your Email has not been verified with OTP</h2>
                                                <p style="font-size:15px;color:#6e6b7b;margin-bottom: 2rem !important;">Verify OTP and Please try again after some time or contact our Support 📞.
                                                </p>
                                                </div><br>   
                                            </div>
                                        </div>
                            </div>
                        </div>
                        <!-- / verify email basic -->
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
@push('js')
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/form.js') }}"></script>
@endpush
