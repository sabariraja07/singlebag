@extends('layouts.seller')
@section('title', 'Edit Shipping Location')
@section('content')
<div class="content-wrapper container-xxl p-0">
  <div class="content-header row">
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
  </div>
<div class="content-header row mb-2">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">{{ __('Edit Shipping Location') }}</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                </li>
                <li class="breadcrumb-item"><a href="#">{{ __('Shipping') }}</a>
                </li>   
                <li class="breadcrumb-item active">{{ __('Edit Shipping Location') }}
                </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
 <div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">

      </div>
      <div class="card-body">
        <form class="basicform" action="{{ route('seller.location.update',$info->id) }}" method="post">
          @csrf
          @method('PUT')
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Title') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="title" value="{{ $info->name }}">
          </div>
        </div>     
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
          <div class="col-sm-12 col-md-7">
            <button class="btn btn-primary basicbtn" type="submit">{{ __('Update') }}</button>
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