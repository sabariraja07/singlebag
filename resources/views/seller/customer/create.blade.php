@extends('layouts.seller')
@section('title', 'Add New Customer')
@section('page-style')
<style>
/* .select2-selection__arrow b {
    border-style: hidden !important;
} */
.select2-container--classic .select2-selection--single .select2-selection__arrow b,
.select2-container--default .select2-selection--single .select2-selection__arrow b {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23d8d6de' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-down'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
  background-size: 18px 14px, 18px 14px;
  background-repeat: no-repeat;
  height: 1rem;
  padding-right: 1.5rem;
  margin-left: 0;
  margin-top: 0;
  left: -8px;
  border-style: none; }
</style>

@endsection
@section('content')
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
                <h2 class="content-header-title float-start mb-0">{{ __('Add New Customer') }}</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ url('/seller/customer') }}">{{ __('CUSTOMER') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('Add New Customer') }}
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
        <h4>{{ __('Create Customer') }}</h4>
      </div>
      <div class="card-body">
        <form class="basicform_with_reset" action="{{ route('seller.customer.store') }}" method="post">
          @csrf
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('First Name') }}</label>
            <div class="col-sm-12 col-md-7">
              <input type="text" class="form-control" required="" name="first_name">
            </div>
          </div>

          <div class="form-group row mb-4">
            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Last Name') }}</label>
            <div class="col-sm-12 col-md-7">
              <input type="text" class="form-control" required="" name="last_name">
            </div>
          </div>

         <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Email') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="email" class="form-control" required="" name="email">
          </div>
        </div>

        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Password') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="password" class="form-control" required="" name="password">
          </div>
        </div>

        <div class="form-group row mb-4">
          <label
              class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
          <div class="col-sm-12 col-md-7">
              <select class="form-control selectric select2" name="status">
                  <option value="1">{{ __('Active') }}</option>
                  <option value="0" selected="">{{ __('Inactive') }}</option>

              </select>
          </div>
        </div>
       
  
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
          <div class="col-sm-12 col-md-7">
            <button class="btn btn-primary basicbtn" type="submit">{{ __('Save') }}</button>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection