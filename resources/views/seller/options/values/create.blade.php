@extends('layouts.seller')
@section('title', 'Create Option Value')
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
@if ($errors->any())
  <div class="alert alert-danger">
      <ul style="margin-top: 1rem;">
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
@endif
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">{{ __('Create Option Value') }}</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('seller.option.show',$info->id) }}">{{ __('Option Values') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('Create Option Value') }}
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
        <form class="basicform" action="{{ route('seller.option-value.store') }}" method="post">
          @csrf
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Name') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="title">
          </div>
        </div>
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Select Option') }}</label>
          <div class="col-sm-12 col-md-7">
            <select class="form-control selectric" name="product_option_id" id="p_id">
               @foreach(App\Models\ProductOption::where('shop_id',current_shop_id())->get() as $row)
               <option value="{{ $row->id }}" @if($info->id==$row->id) selected="" @endif>{{ $row->name }}</option>
               @endforeach
            </select>
          </div>
        </div>
        {{-- <div class="form-group row mb-4">
          <label
              class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
          <div class="col-sm-12 col-md-7">
              <select class="form-control selectric select2" name="status">
                  <option value="1">{{ __('Active') }}</option>
                  <option value="0" selected="">{{ __('Inactive') }}</option>

              </select>
          </div>
      </div> --}}
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
@section('page-script')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endsection