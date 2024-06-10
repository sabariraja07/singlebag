@extends('layouts.app')
@section('head')
    @include('layouts.partials.headersection', ['title' => 'Profile Settings'])
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    @if (Session::has('error'))
                        <ul class="alert alert-danger">
                            <li>{{ Session::get('error') }}</li>
                        </ul>
                    @endif
                    @if (Session::has('success'))
                        <ul class="alert alert-success">
                            <li>{{ Session::get('success') }}</li>
                        </ul>
                    @endif
                </div>
                <div class="col-md-6">


                    <form method="post" class="basicform" action="{{ route('my.profile.update') }}">
                        @csrf
                        <h4 class="mb-20">{{ __('Edit General Settings') }}</h4>
                        <div class="custom-form">
                            <div class="form-group">
                                <label for="name">{{ __('First Name') }}</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" required
                                    placeholder="Enter First Name" value="{{ Auth::user()->first_name }}">
                            </div>
                            <div class="form-group">
                                <label for="name">{{ __('Last Name') }}</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" required
                                    placeholder="Enter Last Name" value="{{ Auth::user()->last_name }}">
                            </div>
                            <div class="form-group">
                                <label for="email">{{ __('Email') }}</label>
                                <input type="text" name="email" id="email" class="form-control" required
                                    placeholder="Enter Email" value="{{ Auth::user()->email }}">
                            </div>
                            @if(Auth::user()->isPartner())
                            <div class="form-group">
                                <label for="mobile_number">{{ __('Mobile Number') }}</label>
                                <input type="text" name="mobile_number" id="mobile_number" maxlength="10" onkeyup="mobile_validation()" class="form-control" required
                                    placeholder="Enter Mobile Number" value="{{ Auth::user()->partner->mobile_number ?? '' }}">
                            </div>
                            @endif

                            <div class="form-group">
                                <button type="submit" class="btn btn-info basicbtn">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="col-md-6">

                    <form method="post" class="basicform" action="{{ route('my.profile.update') }}">
                        @csrf
                        <h4 class="mb-20">{{ __('Change Password') }}</h4>
                        <div class="custom-form">
                            <div class="form-group">
                                <label for="oldpassword">{{ __('Old Password') }}</label>
                                <input type="password" name="password_current" id="oldpassword" class="form-control"
                                    placeholder="Enter Old Password" required>
                            </div>
                            <div class="form-group">
                                <label for="password">{{ __('New Password') }}</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Enter New Password" required>
                            </div>
                            <div class="form-group">
                                <label for="password1">{{ __('Enter Again Password') }}</label>
                                <input type="password" name="password_confirmation" id="password1" class="form-control"
                                    placeholder="Enter Again" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success basicbtn">{{ __('Change') }}</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    @if(Auth::user()->isPartner())
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
    
                        <form method="post" class="basicform" action="{{ route('my.profile.update') }}">
                            @csrf
                            <h4 class="mb-20">{{ __('Add Bank Details') }}</h4>
                               <input type="hidden" name="mobile_number" id="mobile_number"  value="{{ Auth::user()->partner->mobile_number ?? '' }}">
                            <div class="custom-form">
                                <div class="form-group">
                                    <label for="bank_details[bank_name]">{{ __('Bank Name') }}</label>
                                    <input type="text" class="form-control" name="bank_details[bank_name]"
                                        placeholder="Enter Bank Name" value="{{ $info->bank_details['bank_name'] ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="bank_details[account_no]">{{ __('Bank Account No') }}</label>
                                    <input type="text" class="form-control" name="bank_details[account_no]"
                                     placeholder="Enter Account No" value="{{ $info->bank_details['account_no'] ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="bank_details[ifsc_code]">{{ __('IFSC Code') }}</label>
                                    <input type="text" class="form-control" name="bank_details[ifsc_code]"
                                        placeholder="Enter IFSC Code" value="{{ $info->bank_details['ifsc_code'] ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="bank_details[kyc]">{{ __('KYC') }}</label>
                                    <input type="text" class="form-control" name="bank_details[kyc]"
                                        placeholder="Enter KYC" value="{{ $info->bank_details['kyc'] ?? ''}}" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success basicbtn">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
@endsection
@push('js')
    <!-- <script src="{{ asset('assets/js/form.js') }}"></script> -->
    <script>
        function mobile_validation() {
            var mobile_number = $('#mobile_number').val();
            var number = /^[0-9]+$/;
            if (mobile_number != "") {
                if (mobile_number.match(number)) {
                } else {
                    $('#mobile_number').focus();
                    alert('Please Enter Valid Mobile Number Only');
                    $('#mobile_number').val('');
                    return false;
                }
            }
        }
    </script>
@endpush