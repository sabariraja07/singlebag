@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=> trans('Store') ])
@endsection
@section('content')
 <div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Edit Store') }}</h4>
                
      </div>
      <div class="card-body">

        <form class="basicform" action="{{ route('partner.shop.update',$info->id) }}" method="post">
          @csrf
          @method('PUT')

          <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Store Name') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="name" id="shop_name" minlength="4" maxlength="15" value="{{ $info->name }}">
          </div>
        </div>
        {{--
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Shop Email') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="email" class="form-control" required="" name="email" value="{{ $info->email }}">
          </div>
        </div>

         <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Password') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" name="password">
          </div>
        </div>
        --}}

         <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Status') }}</label>
          <div class="col-sm-12 col-md-7">
           <select class="form-control" name="status">
             <option value="active" @if($info->status=='active') selected=""  @endif>{{ __('Active') }}</option>
             <option value="trash" @if($info->status=='trash') selected=""  @endif>{{ __('Trash') }}</option>
             <option value="suspended" @if($info->status=='suspended') selected=""  @endif>{{ __('Suspended') }}</option>
             <option value="pending" @if($info->status=='pending') selected=""  @endif>{{ __('Request') }}</option>
           </select>
          </div>
        </div>      
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
          <div class="col-sm-12 col-md-7">
            <button class="btn btn-success basicbtn" type="submit">{{ __('Save') }}</button>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
<script>
$(function() {
  $('#shop_name').keyup(function() {
              var value = $(this).val();
              var value = value.replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
              $(this).val(value);
          });
});
</script>
@endpush