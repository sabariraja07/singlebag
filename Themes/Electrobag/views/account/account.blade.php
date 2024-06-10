@extends('electrobag::account.layout.app')
@section('account_title',  __('My Account') )
@section('account_breadcrumb')
    <a href="{{ url('/user/dashboard') }}">{{ __('My Account') }}</a>
@endsection
@section('user_content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (Session::get('success'))
        <div class="alert alert-success">
            {{ session::get('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-6 col-md-8 col-md-offset-2 col-lg-offset-3">
            <div style="border: 1px #eee solid; padding: 15px;">
                <form method="post" class="basicform" name="enq" action="{{ url('/user/settings/update') }}" autocomplete="off">
                    @csrf
                    <div class="empty-space col-xs-b30"></div>
                    <input class="simple-input" required="" name="first_name" id="first_name" type="text"
                        value="{{ Auth::guard('customer')->user()->first_name }}"
                        placeholder="{{ __('First Name') }}*" />
                    <div class="empty-space col-xs-b10 col-sm-b20"></div>
                    <input class="simple-input" name="last_name" id="last_name" type="text"
                        value="{{ Auth::guard('customer')->user()->last_name }}"
                        placeholder="{{ __('Last Name') }}" />
                    <div class="empty-space col-xs-b30"></div>
                    <input class="simple-input" required="" name="mobile" id="phone" type="text"
                    @keypress="$isMobileNumber($event)" maxlength="10" minlength="10"
                        value="{{ Auth::guard('customer')->user()->mobile }}"
                        placeholder="{{ __('Phone*') }} " />
                    <div class="empty-space col-xs-b30"></div>
                    <input class="simple-input" required="" name="email" id="email" type="text"
                        value="{{ Auth::guard('customer')->user()->email }}"
                        placeholder="{{ __('Email*') }} " />
                    <div class="empty-space col-xs-b30"></div>
                    <input class="simple-input" type="password" name="password_current" id="password_current"
                        autocomplete="off"
                        placeholder="{{ __('Current password (leave blank to leave unchanged)') }} " />
                    <div class="empty-space col-xs-b30"></div>
                    <input class="simple-input" name="password" type="password" id="password_1"
                        autocomplete="off"
                        placeholder="{{ __('New password (leave blank to leave unchanged)') }}" />
                    <div class="empty-space col-xs-b30"></div>
                    <input class="simple-input" type="password" name="password_confirmation" id="password_2"
                        autocomplete="off" placeholder="{{ __('Confirm new password (leave blank to leave unchanged)') }}" />
                    <div class="empty-space col-xs-b30"></div>
                    <div class="row">
                        <div class="col-sm-5 text-right">
                            <a class="button size-2 style-3" href="javascript:void(0)">
                                <span class="button-wrapper">
                                    <span class="icon"><img
                                            src="{{ asset('frontend/electrobag/img/icon-4.png') }}"
                                            alt="" /></span>
                                    <input type="submit" class="btn btn-fill-out submit font-weight-bold"
                                        name="submit" value="Submit">
                                    <span class="text">{{ __('Save change') }}</span>

                                </span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <!-- <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
        <script src="{{ asset('assets/js/form.js') }}"></script> -->
    <script>
        function mobile_validation() {
            var mobile_number = $('#phone').val();
            var number = /^[0-9]+$/;
            if (mobile_number != "") {
                if (mobile_number.match(number)) {

                } else {
                    $('#phone').focus();
                    alert('Please Enter Valid Mobile Number Only');
                    $('#phone').val('');
                    return false;

                }
            }
        }
        $(function() {
            $('#password_current').bind('input', function() {
                $(this).val(function(_, v) {
                    return v.replace(/\s+/g, '');
                });
            });
        }); 
        $(function() {
            $('#password_1').bind('input', function() {
                $(this).val(function(_, v) {
                    return v.replace(/\s+/g, '');
                });
            });
        });
        $(function() {
            $('#password_2').bind('input', function() {
                $(this).val(function(_, v) {
                    return v.replace(/\s+/g, '');
                });
            });
        });
    </script>
@endpush
