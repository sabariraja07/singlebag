@extends('singlebag::layouts.app')
@push('css')
@endpush
@section('content')
@section('breadcrumb')
    <span></span> {{ __('Register') }}
@endsection
<div class="page-content pt-150 pb-150">
    <div class="container">
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
                        @if (Session::has('agent_error'))
                        <div class="alert alert-danger">
                            <ul>
                                <li>{{ Session::get('agent_error') }}</li>
                            </ul>
                        </div>
                    @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-8">
                        <div class="login_wrap widget-taber-content background-white">
                            <div class="padding_eight_all bg-white">
                                <div class="heading_s1">
                                    <h1 class="mb-5">{{ __('Register') }}</h1>
                                    <p class="mb-30">{{ __('Already have an account?') }} <a
                                            href="{{ url('/user/login') }}">{{ __('Login here') }}</a></p>
                                </div>
                                <form id="customer-register" name="customer-register"
                                    action="{{ url('/user/register-user') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" placeholder="First Name" value="{{ old('first_name') }}"
                                            name="first_name" id="first_name" class="form-control" required="">
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
                                    $shop_id= domain_info('shop_id');
                                    $user_term = App\Models\Menu::where('name',"user_term")->where('shop_id', $shop_id)->first();
                                    @endphp
                        
                                    <div class="login_footer form-group mb-50">
                                        <div class="chek-form">
                                            <div class="custome-checkbox">
                                                <input class="form-check-input" type="checkbox" name="checkbox"
                                                    id="exampleCheckbox12" required="" value="" />
                                                <label class="form-check-label" for="exampleCheckbox12">{{ __('I agree with the') }}
                                                    <a href="{{  $user_term->data ?? "javascript:void(0);"}}">{{ __('terms and conditions') }}</a></label>    
                                            </div>
                                            <div class="invalid-feedback exampleCheckbox12"></div>
                                        </div>
                                        <!-- <a href="#"><i class="fi-rs-book-alt mr-5 text-muted"></i>Lean more</a> -->
                                    </div>
                                    <div class="form-group mb-30">
                                        <button type="submit"
                                            class="btn btn-fill-out btn-block hover-up font-weight-bold" id="register"
                                            name="login">{{ __('Sign Up') }}</button>
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
@endsection
@push('js')
<script>
    $(document)
        .on('click', 'form button[type=submit]', function(e) {
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
$( "#email" ).keypress(function( event ) {
    if ( event.which == 32 ) {
       alert('Spaces Not Allowed');
       return false;
    }
});
</script>
@endpush
