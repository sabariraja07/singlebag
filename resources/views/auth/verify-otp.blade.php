@extends('layouts.partials.auth')
@section('title')
    {{ 'Verify OTP' }}
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
                                @if (Session::has('error'))
                                    <div class="alert alert-danger">
                                        <ul style="margin-top: 1rem;">
                                            <li>{{ Session::get('error') }}</li>
                                        </ul>
                                    </div>
                                @endif
                                @php
                                    $user = Auth::user();
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
                                    
                                    $email =  $user->email;
                                    $get_email = hideEmailAddress($email);
                                @endphp
                                <h2 class="card-title fw-bolder mb-1">Verify your email ✉️</h2>
                                    <!-- otp up form -->
                                    <form class="basicform" method="post" action="{{ route('otp_verify') }}">
                                        @csrf
                                        <div id="signup-form" class="login-form animated preFadeInLeft fadeInLeft">
                                            <div class="field pb-10">
                                                <div class="control has-icons-right required">
                                                    <input class="input is-medium has-shadow form-control" type="text" name="otp_verify" id="otp_verify"
                                                    maxlength="4" placeholder="{{ __('Enter OTP') }}" required />
                                                    <span class="icon is-medium is-right">
                                                        <i class="sl sl-icon-pin"></i>
                                                    </span>
                                                </div><br>   
                                            </div>
                                            <p class="control login">
                                                <button
                                                    class="btn btn-primary w-100">
                                                    Submit
                                                </button>
                                            </p>
                                        </div>
                                    </form>
                                    <p style="color: red;
                                    text-align: center;
                                    font-size: 16px;">OTP has been sent to <span style="color:green"> {{ $get_email ?? '' }}</span><br>
                                    </p>

                                <p class="text-center mt-2">
                                    <span>Didn't receive an email? </span><a href="{{ route('resend_store_otp',$user->id) }}"><span>&nbsp;Resend</span></a>
                                </p>
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
