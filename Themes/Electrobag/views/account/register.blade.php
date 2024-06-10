@extends('electrobag::layouts.app')
@push('css')
<style>
.checkbox-entry span:before {
    border: 2px solid #212529 !important;
}
::-moz-selection { 
color: #1e1e1e;;
background: #ededed;
}

::selection {
color: #1e1e1e;
background: #ededed;
}
</style>
@endpush
@section('content')
<div class="container">
    <div class="empty-space col-xs-b15 col-sm-b30"></div>
    <div class="breadcrumbs">
        <a href="{{ url('/') }}">{{ __('Home') }}</a>
        <a href="{{ url('/user/register') }}">{{  __('Register') }}</a>
    </div>
    <div class="empty-space col-xs-b15 col-sm-b50 col-md-b100"></div>

    <div class="text-center">
        <div class="h2">{{ __('Register') }}</div>
        <div class="title-underline center"><span></span></div>
    </div>
    <div class="row">
        <div class="col-xl-8 col-lg-10 col-md-12 m-auto">
            <div class="row">
                <div class="col-md-8">
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
            </div>

            <div class="empty-space col-xs-b15 col-sm-b30"></div>
            <div class="row">
                <div class="col-lg-6 col-md-8 col-md-offset-2 col-lg-offset-3">
                    <div style="border: 1px #eee solid; padding: 15px;">
                        <div class="login_wrap widget-taber-content background-white">
                            <div class="padding_eight_all bg-white">
                                <div class="heading_s1">
                                    <div class="h5">{{ __('Already have an account?') }} <a
                                            href="{{ url('/user/login') }}">{{ __('Login here') }}</a></a></div>
                                    <div class="empty-space col-xs-b15 col-sm-b30"></div>
                                </div>
                                <form id="customer-register" name="customer-register"
                                    action="{{ url('/user/register-user') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" placeholder="First Name" value="{{ old('first_name') }}"
                                            name="first_name" class="simple-input" required="">
                                        @if ($errors->has('first_name'))
                                            <div class="invalid-feedback" style="display: block;">
                                                {{ $errors->first('first_name') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <input type="text" placeholder="Last Name" value="{{ old('last_name') }}"
                                            name="last_name" class="simple-input" required="">
                                        @if ($errors->has('last_name'))
                                            <div class="invalid-feedback" style="display: block;">
                                                {{ $errors->first('last_name') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <input type="email" placeholder="Email" value="{{ old('email') }}"
                                            name="email" class="simple-input" required="">
                                        @if ($errors->has('email'))
                                            <div class="invalid-feedback" style="display: block;">
                                                {{ $errors->first('email') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" placeholder="Password"
                                            class="simple-input" required="" id="password">
                                        @if ($errors->has('password'))
                                            <div class="invalid-feedback" style="display: block;">
                                                {{ $errors->first('password') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <input type="password" placeholder="Confirm password"
                                            name="password_confirmation" class="simple-input" required="" id="confirm_password">
                                    </div>
                                    @php
                                    $shop_id= domain_info('shop_id');
                                    $user_term = App\Models\Menu::where('name',"user_term")->where('shop_id', $shop_id)->first();
                                    @endphp
                                    <div class="login_footer form-group mb-50">
                                        <div class="chek-form">
                                            <label class="checkbox-entry">
                                                <input type="checkbox" name="exampleCheckbox12" id="exampleCheckbox12"
                                                    value="" required="" /><span style="margin-bottom: 17px !important;">I agree with the
                                                    <a href="{{  $user_term->data ?? "javascript:void(0);"}}">
                                                        terms and conditions</span></a>

                                            </label>
                                            <div class="invalid-feedback exampleCheckbox12"></div>
                                        </div>
                                        <!-- <a href="javascript:void(0)"><i class="fi-rs-book-alt mr-5 text-muted"></i>Lean more</a> -->
                                    </div>
                                    <div class="button size-2 style-3">
                                        <span class="button-wrapper">
                                            <span class="icon"><img
                                                    src="{{ asset('frontend/electrobag/img/icon-4.png') }}"
                                                    alt=""></span>
                                            <span class="text">{{ __('Sign Up') }}</span>
                                        </span>
                                        <input type="submit" name="register" value="" />
                                    </div>
                                    
                                    <div class="empty-space col-xs-b15 col-sm-b30"></div>
                                    <div class="empty-space col-xs-b15 col-sm-b30"></div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="empty-space col-xs-b15 col-sm-b70"></div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(document)
        .on('click', 'form input[type=submit]', function(e) {
            $('.invalid-feedback').hide();
            if ($("#exampleCheckbox12").prop('checked') != true) {
                $('.exampleCheckbox12').text("Please accept terms & policy.").show();
            }
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
@endpush
