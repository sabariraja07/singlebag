@extends('multibag::layouts.app')
@push('css')
    <style>
        input[type=checkbox] {
            display: none;
        }

        input[type=checkbox]+label {
            position: relative;
            padding-left: 30px;
            line-height: 15px;
            font-size: 14px;
            font-weight: 400;
            margin: 0;
        }

        input[type=checkbox]+label:after,
        input[type=checkbox]+label:before {
            position: absolute;
            left: 0;
            top: 0;
            width: 15px;
            display: block;
            transition: .3s;
        }

        input[type=checkbox]+label:before {
            height: 15px;
            border: 2px solid #212529;
            content: "";
        }

        input[type=checkbox]+label:after {
            content: "\F00C";
            font-family: FontAwesome;
            font-weight: 600;
            font-size: 12px;
            line-height: 15px;
            opacity: 0;
            text-align: center;
            color: #212529;
        }

        input[type=checkbox]:checked+label:after {
            opacity: 1;
        }
    </style>
@endpush
@section('content')
@section('breadcrumb')
    @php
        $title = 'Register';
        $type = 1;
    @endphp
    <li class="active"> Register </li>
@endsection
<div class="login-register-area bg-gray pt-30 pb-160">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-12 ms-auto me-auto">
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
                <div class="login-register-wrapper">
                    {{-- <div class="login-register-tab-list nav">
                        <a class="active" data-bs-toggle="tab" href="#lg1">
                            <h4> {{ __('Register') }} </h4>
                        </a>
                    </div> --}}
                    <div class="tab-content">
                        <div id="lg1" class="tab-pane active">
                            <div class="login-form-container">
                                <div class="login-register-form">
                                    <form id="customer-register" name="customer-register"
                                        action="{{ url('/user/register-user') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" placeholder="First Name"
                                                value="{{ old('first_name') }}" name="first_name" id="first_name"
                                                class="form-control" required="">
                                            @if ($errors->has('first_name'))
                                                <div class="invalid-feedback" style="display: block;">
                                                    {{ $errors->first('first_name') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <input type="text" placeholder="Last Name" value="{{ old('last_name') }}"
                                                name="last_name" id="last_name" class="form-control" required="">
                                            @if ($errors->has('last_name'))
                                                <div class="invalid-feedback" style="display: block;">
                                                    {{ $errors->first('last_name') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <input type="email" placeholder="Email" value="{{ old('email') }}"
                                                name="email" id="email" class="form-control" required="">
                                            @if ($errors->has('email'))
                                                <div class="invalid-feedback" style="display: block;">
                                                    {{ $errors->first('email') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" placeholder="Password"
                                                class="form-control" required="" id="password">
                                            @if ($errors->has('password'))
                                                <div class="invalid-feedback" style="display: block;">
                                                    {{ $errors->first('password') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <input type="password" placeholder="Confirm password"
                                                name="password_confirmation" class="form-control" required="" id="confirm_password">
                                        </div>
                                        @php
                                            $shop_id = domain_info('shop_id');
                                            $user_term = App\Models\Menu::where('name', 'user_term')
                                                ->where('shop_id', $shop_id)
                                                ->first();
                                        @endphp
                                        <div class="button-box">
                                            <div class="login-toggle-btn">
                                                <input type="checkbox" name="accept_policy" id="accept_policy" required>
                                                <label for="accept_policy">
                                                    <span>I agree with the &nbsp;
                                                        <a href="{{ $user_term->data ?? 'javascript:void(0);' }}">
                                                            terms and conditions</span></a>
                                                </label>
                                                {{-- <a href="{{ url('/user/password/reset') }}">{{ __('Forgot Password ?') }}</a> --}}
                                            </div>
                                            <div class="invalid-feedback exampleCheckbox12"></div>
                                            <button id="register" type="submit">{{ __('Sign Up') }}</button>
                                        </div>
                                        <div class="login-toggle-btn mt-30">
                                            <span>{{ __('Already have an account?') }}
                                                <a href="{{ url('/user/login') }}">{{ __('Login here') }}</a></span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
