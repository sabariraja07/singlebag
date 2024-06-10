@extends('layouts.seller')
@section('title', 'Edit User')

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
                            <h2 class="content-header-title float-start mb-0">{{ __('Edit User') }}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ url('/seller/users') }}">{{ __('Users') }}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ __('Edit User') }}
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
                                    {{-- <h4 class="card-title">Multiple Column</h4> --}}
                                </div>
                                <div class="card-body">
                                    <form class="basicform" action="{{ route('seller.users.update', $info->id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label">{{ __('User First Name') }}</label>
                                                    <input type="text" class="form-control" required=""
                                                        name="first_name" value="{{ $info->first_name }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label">{{ __('User Last Name') }}</label>
                                                    <input type="text" class="form-control" required=""
                                                        name="last_name" value="{{ $info->last_name }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label">{{ __('User Email') }}</label>
                                                    <input type="email" class="form-control" required="" name="email"
                                                        value="{{ $info->email }}" />
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label">{{ __('Password') }}</label>

                                                <div class="mb-1 input-group input-group-merge form-password-toggle mb-2">

                                                    <input type="password" class="form-control" name="password" />
                                                    <span class="input-group-text cursor-pointer"><i
                                                            data-feather="eye"></i></span>
                                                </div>
                                                <input type="checkbox" name="change_password" id="change_password"
                                                    value="1">
                                                <label for="change_password"
                                                    class="form-label">{{ __('Confirm Change Password') }}</label>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <label class="form-label">{{ __('Status') }}</label>
                                                <select class="form-select" id="select_status" name="status">
                                                    <option value="1" {{ $info->status == 1 ? 'selected' : '' }}>
                                                        {{ __('Active') }}</option>
                                                    <option value="0" {{ $info->status == 0 ? 'selected' : '' }}>
                                                        {{ __('In active') }}</option>

                                                </select>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <label class="form-label">{{ __('Select Role') }}</label>
                                                <select class="form-select" id="role_id" name="select_role">
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}"
                                                            {{ $info->role_id == $role->id ? 'selected' : '' }}>
                                                            {{ $role->name }}</option>
                                                    @endforeach

                                                </select><br>
                                            </div>

                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary me-1 basicbtn">{{ __('Update') }}</button>

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
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
@endsection
