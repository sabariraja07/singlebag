@extends('fashionbag::layouts.app')
@push('css')
    <style>
        .register-wrapper .single-input-item input[type=checkbox]+label:before {
            border: 2px solid #212529 !important;
        }

        input[type=checkbox]+label:after {
            color: #212529 !important;
        }
    </style>
@endpush
@section('content')
@section('breadcrumb')
    @php
        $title = 'Register';
        $type = 1;
    @endphp
    <li class="active"> {{ __('Register') }} </li>
@endsection
<div class="section section-margin">
    <div class="container">
        <div class="row mb-n10">
            <div class="col-lg-6 col-md-8 m-auto pb-10">
                <div class="pb-20">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (Session::has('user_limit'))
                        <div class="alert alert-danger">
                            <ul>
                                <li>{{ Session::get('user_limit') }}</li>
                            </ul>
                        </div>
                    @endif
                </div>
                <!-- Register Wrapper Start -->
                <div class="register-wrapper">

                    <!-- Login Title & Content Start -->
                    <div class="section-content text-center mb-5">
                        <h2 class="title mb-2">{{ __('Create Account') }}</h2>
                        <p class="desc-content">
                            <span>{{ __('Already have an account?') }}
                                <a href="{{ url('/user/login') }}">{{ __('Login here') }}</a></span>
                        </p>
                    </div>
                    <!-- Login Title & Content End -->

                    <!-- Form Action Start -->
                    <form action="{{ url('/user/register-user') }}" method="POST">
                        @csrf

                        <!-- Input First Name Start -->
                        <div class="single-input-item mb-3">
                            <input type="text" placeholder="First Name" value="{{ old('first_name') }}"
                                name="first_name" id="first_name" class="form-control" required="">
                            @if ($errors->has('first_name'))
                                <div class="invalid-feedback" style="display: block;">
                                    {{ $errors->first('first_name') }}
                                </div>
                            @endif
                        </div>
                        <!-- Input First Name End -->

                        <!-- Input Last Name Start -->
                        <div class="single-input-item mb-3">
                            <input type="text" placeholder="Last Name" value="{{ old('last_name') }}"
                                name="last_name" id="last_name" class="form-control" required="">
                            @if ($errors->has('last_name'))
                                <div class="invalid-feedback" style="display: block;">
                                    {{ $errors->first('last_name') }}
                                </div>
                            @endif
                        </div>
                        <!-- Input Last Name End -->

                        <!-- Input Email Or Username Start -->
                        <div class="single-input-item mb-3">
                            <input type="email" placeholder="Email" value="{{ old('email') }}" name="email"
                                id="email" class="form-control" required="">
                            @if ($errors->has('email'))
                                <div class="invalid-feedback" style="display: block;">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>
                        <!-- Input Email Or Username End -->

                        <!-- Input Password Start -->
                        <div class="single-input-item mb-3">
                            <input type="password" name="password" placeholder="Password" class="form-control"
                                required="" id="password">
                            @if ($errors->has('password'))
                                <div class="invalid-feedback" style="display: block;">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                        </div>
                        <!-- Input Password End -->

                        <div class="single-input-item mb-3">
                            <input type="password" placeholder="Confirm password" name="password_confirmation"
                                class="form-control" required="" id="confirm_password">
                        </div>
                        @php
                            $shop_id = domain_info('shop_id');
                            $user_term = App\Models\Menu::where('name', 'user_term')
                                ->where('shop_id', $shop_id)
                                ->first();
                        @endphp
                        <!-- Checkbox & Subscribe Label Start -->
                        <div class="single-input-item mb-3">
                            <div class="login-reg-form-meta d-flex align-items-center justify-content-between">
                                <div class="remember-meta mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="accept_policy">
                                        <label class="custom-control-label" for="accept_policy"> <span>I agree with the
                                                <a href="{{ $user_term->data ?? 'javascript:void(0);' }}">
                                                    terms and conditions</span></a></label>
                                    </div>
                                    <div class="invalid-feedback exampleCheckbox12"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Checkbox & Subscribe Label End -->

                        <!-- Register Button Start -->
                        <div class="single-input-item mb-3">
                            <button id="register" type=submit
                                class="btn btn btn-fb btn-hover-primary rounded-0">Register</button>
                        </div>
                        <!-- Register Button End -->

                    </form>
                    <!-- Form Action End -->

                </div>
                <!-- Register Wrapper End -->
            </div>
        </div>

    </div>
</div>
@endsection
@push('js')
<script>
    $(document)
        .on('click', 'form button[type=submit]', function(e) {
            $('.invalid-feedback').hide();
            if ($("#accept_policy").prop('checked') != true) {
                $('.exampleCheckbox12').text("Please accept terms & policy.").show();
                return false;
            }
            return true;
        });
        
    $(function() {
            $('#password').bind('input', function() {
                $(this).val(function(_, v) {
                    return v.replace(/\s+/g, '');
                });
            });
        }); 
        $(function() {
            $('#confirm_password').bind('input', function() {
                $(this).val(function(_, v) {
                    return v.replace(/\s+/g, '');
                });
            });
        });
</script>
<script>
    $(document).ready(function() {

        $('#register').on('click', function() {

            var first_name = $('#first_name').val();
            var last_name = $('#last_name').val();
            var email = $('#email').val();
            //name and email validation
            var letters = /^[A-Za-z ]+$/;
            var email_validation = /^[a-zA-Z0-9.!#$%&'+/=?^_`{|}~-]+@[a-zA-Z]+(?:\.[a-zA-Z]+)$/;
            if (first_name != '') {
                if (!first_name.match(letters)) {
                    $('#first_name').focus();
                    alert('Please Enter First Name Alphabet Only');
                    return false;
                }
            }
            if (last_name != '') {
                if (!last_name.match(letters)) {
                    $('#last_name').focus();
                    alert('Please Enter Last Name Alphabet Only');
                    return false;
                }
            }
            if (email != '') {
                if (!email.match(email_validation)) {
                    $('#email').focus();
                    alert('Please Enter Valid Email');
                    return false;
                }
            }
        });
    });
</script>
<script>
    //space validation
    $("#email").keypress(function(event) {
        if (event.which == 32) {
            alert('Spaces Not Allowed');
            return false;
        }
    });
</script>
@endpush
