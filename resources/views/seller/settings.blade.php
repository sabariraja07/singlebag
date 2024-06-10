@extends('layouts.seller')
@section('title', 'Profile Settings')

@section('content')

    <!-- BEGIN: Content-->
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            @if (Session::has('success'))
                <div class="alert alert-success">
                    <ul style="margin-top: 1rem;">
                        <li>{{ Session::get('success') }}</li>
                    </ul>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin-top: 1rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger">
                    <ul style="margin-top: 1rem;">
                        <li>{{ Session::get('error') }}</li>
                    </ul>
                </div>
            @endif
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Profile Settings') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('Profile Settings') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">

                <!-- Basic Horizontal form layout section start -->
                <section id="basic-horizontal-layouts">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('Edit General Settings') }}</h4>
                                </div>
                                <div class="card-body">
                                    <form method="post" class="form form-horizontal"
                                        action="{{ route('seller.profile.update') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label"
                                                            for="first_name">{{ __('First Name') }}</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="first_name" class="form-control"
                                                            name="first_name" placeholder="Enter First Name"
                                                            value="{{ Auth::user()->first_name }}" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label"
                                                            for="last_name">{{ __('Last Name') }}</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="last_name" class="form-control"
                                                            name="last_name" placeholder="Enter Last Name"
                                                            value="{{ Auth::user()->last_name }}" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label"
                                                            for="email">{{ __('Email') }}</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <input type="email" id="email" class="form-control"
                                                            name="email" placeholder="Enter Email"
                                                            value="{{ Auth::user()->email }}" required />
                                                    </div>
                                                </div>
                                            </div>
                                            @if (Auth::user()->isPartner())
                                                <div class="col-12">
                                                    <div class="mb-1 row">
                                                        <div class="col-sm-3">
                                                            <label class="col-form-label"
                                                                for="mobile_number">{{ __('Mobile Number') }}</label>
                                                        </div>
                                                        <div class="col-sm-9">
                                                            <input type="email" id="mobile_number" class="form-control"
                                                                name="mobile_number" placeholder="Enter Mobile Number"
                                                                value="{{ Auth::user()->partner->mobile_number ?? '' }}"
                                                                maxlength="10" onkeyup="mobile_validation()" required />
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-sm-9 offset-sm-3">
                                                <button type="submit" class="btn btn-primary me-1"
                                                    id="update">{{ __('Update') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('Change Password') }}</h4>
                                </div>
                                <div class="card-body">
                                    <form method="post" class="form form-horizontal"
                                        action="{{ route('seller.profile.update') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label"
                                                            for="oldpassword">{{ __('Old Password') }}</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <input type="password" name="password_current" id="oldpassword"
                                                            class="form-control" placeholder="Enter Old Password"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label"
                                                            for="password">{{ __('New Password') }}</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <input type="password" name="password" id="password"
                                                            class="form-control" placeholder="Enter New Password"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-1 row">
                                                    <div class="col-sm-3">
                                                        <label class="col-form-label"
                                                            for="password1">{{ __('Confirm Password') }}</label>
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <input type="password" name="password_confirmation"
                                                            id="password1" class="form-control"
                                                            placeholder="Enter Confirm Password" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-9 offset-sm-3">
                                                <button type="submit"
                                                    class="btn btn-primary me-1">{{ __('Update') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Basic Horizontal form layout section end -->

            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript">
        $('#update').on('click', function() {
            var email = $('#email').val();
            // var email_validation = /^[a-zA-Z0-9.!#$%&'+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)$/;
            var email_validation = /^[a-zA-Z0-9.!#$%&'+/=?^_`{|}~-]+@[a-zA-Z]+(?:\.[a-zA-Z]+)$/;
            if (email != '') {
                if (!email.match(email_validation)) {
                    $('#email').focus();
                    Sweet('error', '{{ __('Please Enter Valid Email') }}');
                    // $('#email').val('');
                    return false;
                }
            }

        });
    </script>
@endsection
