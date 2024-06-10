@extends('layouts.seller')
@section('title', 'Add Delivery Agent')
@section('content')
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row mb-2">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Add Delivery Agent') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{ url('/seller/delivery') }}">{{ __('Delivery Agent') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('Add Delivery Agent') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">

                    </div>
                    <div class="card-body">
                        <form class="basicform_with_reset" action="{{ route('seller.delivery.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row mb-1">
                                <label
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Avatar') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="file" accept="Image/*" name="user_image" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('First Name') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" required="" name="first_name">
                                </div>
                            </div>

                            <div class="form-group row mb-1">
                                <label
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Last Name') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" required="" name="last_name">
                                </div>
                            </div>

                            <div class="form-group row mb-1">
                                <label
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Email') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="email" class="form-control" required="" name="email">
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Mobile Number') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" required="" maxlength="10"
                                        id="mobile_number" onkeyup="mobile_validation()" name="mobile">
                                </div>
                            </div>

                            <div class="form-group row mb-1">
                                <label
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Password') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="password" class="form-control" required="" name="password">
                                </div>
                            </div>

                            <div class="form-group row mb-1">
                                <label
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Agent ID') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" required="" name="user_id">
                                </div>
                            </div>

                            <div class="form-group row mb-1">
                                <label
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Agents ID Image') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="file" accept="Image/*" name="user_id_image" class="form-control">
                                </div>
                            </div>


                            <div class="form-group row mb-1">
                                <label
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control selectric" name="status">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0" selected="">{{ __('Inactive') }}</option>

                                    </select>
                                </div>
                            </div>


                            <div class="form-group row mb-1">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7">
                                    <button class="btn btn-primary basicbtn" type="submit"
                                        id="submit">{{ __('Save') }}</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('page-script')
        <script src="{{ asset('assets/js/form.js') }}"></script>
        <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
        <script>
            function mobile_validation() {
                var mobile_number = $('#mobile_number').val();
                var number = /^[0-9]+$/;
                if (mobile_number != "") {
                    if (mobile_number.match(number)) {


                    } else {
                        $('#mobile_number').focus();
                        Sweet('error', '{{ __('Please Enter Valid Mobile Number Only') }}');
                        $('#mobile_number').val('');
                        return false;

                    }
                }
            }
            $('#submit').on('click', function() {
                var mobile_number = $('#mobile_number').val();
                var number = /^[0-9]+$/;
                if (mobile_number != "") {
                    if (mobile_number.length != 10) {
                        $('#mobile_number').focus();
                        Sweet('error', '{{ __('Enter a valid 10 digit mobile number') }}');
                        return false;
                    }
                }
            });
        </script>
    @endsection
