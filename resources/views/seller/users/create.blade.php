@extends('layouts.seller')
@section('title', 'Create User')

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

                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">{{ __('Create User') }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ url('seller/users')}}">{{ __('Users') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ __('Create User') }}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <!-- Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    {{-- <h4 class="card-title">Create Plan</h4> --}}
                                 
                                </div>
                                <div class="card-body">
                                    <form class="basicform_with_reload" action="{{ route('seller.users.store') }}"
                                        method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label">{{ __('First Name') }}</label>
                                                    <input type="text" class="form-control" required="" name="first_name">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label">{{ __('Last Name') }}</label>
                                                    <input type="text" class="form-control" required="" name="last_name">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label">{{ __('Email') }}</label>
                                                    <input type="email" class="form-control" required="" name="email">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <label class="form-label">{{ __('Password') }}</label>
                                                <div class="mb-1 input-group input-group-merge form-password-toggle mb-2">

                                                    <input type="password" class="form-control" name="password"
                                                        required="" />
                                                    <span class="input-group-text cursor-pointer"><i
                                                            data-feather="eye"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label">{{ __('Status') }}</label>
                                                    <select class="form-select" name="status"  id="select_status">
                                                        <option value="1">{{ __('Active') }}</option>
                                                        <option value="0">{{ __('Inactive') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label">{{ __('Select Role') }}</label>
                                                    <select class="form-select" name="role_id" id="select_role">
                                                        @foreach($roles as $role)
                                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                         

                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary me-1 basicbtn">{{ __('Save') }}</button>
                                                <button type="reset" class="btn btn-outline-secondary">{{ __('Reset') }}</button>

                                            </div>
                                           
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Basic Floating Label Form section end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
@endsection
