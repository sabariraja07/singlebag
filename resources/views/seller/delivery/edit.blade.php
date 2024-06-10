@extends('layouts.seller')
@section('title', 'Edit Delivery Agent')
@section('page-style')
<style>
    .vertical-layout.vertical-menu-modern.menu-expanded .footer {
        margin-left: -27px !important;

    }
</style>
@endsection
@section('content')
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row mb-2">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">{{ __('Edit Delivery Agent') }}</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                    href="{{ url('/seller/delivery') }}">{{ __('Delivery Agent') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Edit Delivery Agent') }}
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
                    <form class="basicform" action="{{ route('seller.delivery.update', $info->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group row mb-1">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Avatar') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="file" accept="Image/*" name="user_image" class="form-control"
                                    value="{{ $info->preview->avathar }}">
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('First Name') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" class="form-control" name="first_name"
                                    value="{{ $info->first_name }}">
                            </div>
                        </div>

                        <div class="form-group row mb-1">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Last Name') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" class="form-control" name="last_name"
                                    value="{{ $info->last_name }}">
                            </div>
                        </div>

                        <div class="form-group row mb-1">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Email') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="email" class="form-control" name="email" value="{{ $info->email }}">
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Mobile Number') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" class="form-control" id="mobile_number"
                                    onkeyup="mobile_validation()" name="mobile" maxlength="10" required
                                    value="{{ $info->mobile ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group row mb-1">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Password') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>

                        <div class="form-group row mb-1">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <input type="checkbox" name="change_password" id="change_password" value="1">
                                <label for="change_password">{{ __('Confirm Change Password') }}</label>
                            </div>
                        </div>

                        <div class="form-group row mb-1">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Agent ID') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" class="form-control" name="user_id"
                                    value="{{ $info->preview->agent_id }}">
                            </div>
                        </div>

                        <div class="form-group row mb-1">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Agents ID Image') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="file" accept="Image/*" name="user_id_image" class="form-control"
                                    value="{{ $info->preview->image_id }}">
                            </div>
                        </div>


                        <div class="form-group row mb-1">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <select class="form-control selectric" name="status">
                                    <option value="1" @if ($info->preview->status == 1) selected="" @endif>
                                        {{ __('Active') }}</option>
                                    <option value="0" @if ($info->preview->status == 0) selected="" @endif>
                                        {{ __('Inactive') }}</option>

                                </select>
                            </div>
                        </div>


                        <div class="form-group row mb-1">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <button class="btn btn-primary basicbtn" id="submit"
                                    type="submit">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/form.js') }}"></script>
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
        $("#submit").on('click', function() {
            var mobile_number = $('#mobile_number').val();
            if (mobile_number == '') {
                Sweet('error', '{{ __('The mobile number field is empty') }}');
                return false;
            }
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
