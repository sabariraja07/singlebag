@extends('layouts.seller')
@section('title', 'Edit Tax Classes')
{{-- @section('page-style')
    <style>
        .vertical-layout.vertical-menu-modern.menu-expanded .footer {
            margin-left: -27px !important;

        }
    </style>
@endsection --}}
@section('content')
        @if (Session::has('success'))
            <div class="alert alert-success">
                <ul style="margin-top: 1rem;">
                    <li>{{ Session::get('success') }}</li>
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
        <div class="content-header row mb-2">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Edit Tax Classes') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{ url('/seller/tax_classes') }}">{{ __('Tax Classes') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('Edit Tax Classes') }}
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
                        <form class="basicform" action="{{ route('seller.tax_classes.update', $info->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group row mb-1">
                                <label
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Name') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" name="name" required
                                        value="{{ $info->name }}">
                                </div>
                            </div>

                            <div class="form-group row mb-1">
                                <label
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Based On') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" name="based_on" required
                                        value="{{ $info->based_on }}">
                                </div>
                            </div>

                            <div class="form-group row mb-1">
                                <label
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control selectric" name="status">
                                        <option value="1" @if ($info->status == 1) selected="" @endif>
                                            {{ __('Active') }}</option>
                                        <option value="2" @if ($info->status == 2) selected="" @endif>
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
    @endsection
