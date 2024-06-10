@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Shop'])
@endsection
@section('content')
 <div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Create Shop') }}</h4>
                
      </div>
      <div class="card-body">

        <form class="basicform_with_reload" action="{{ route('admin.shop.store') }}" method="post">
          @csrf

        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Customer First Name') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="first_name">
          </div>
        </div>

        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Customer Last Name') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="last_name">
          </div>
        </div>
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Customer Email') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="email" class="form-control" required="" name="email">
          </div>
        </div>
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Customer Mobile Number') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" name="mobile_number" id="mobile_number" class="form-control" />
          </div>
        </div>
         <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Password') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="password">
          </div>
        </div>

        

         <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Transaction Method') }}</label>
          <div class="col-sm-12 col-md-7">
            <select class="form-control" name="payment_method">
              @foreach(App\Models\PaymentMethod::where('status',1)->get() as $row)
              <option value="{{ $row->slug }}">{{ $row->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Transaction Id') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="transaction_id">
          </div>
        </div>

        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Plan') }}</label>
          <div class="col-sm-12 col-md-7">
            <select class="form-control" name="plan">
              @foreach(App\Models\Plan::where('status',1)->get() as $row)
              <option value="{{ $row->id }}">{{ $row->name }}</option>
              @endforeach
            </select>
          </div>
        </div>


        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Shop Name') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="shop_name" id="shop_name" style="text-transform: lowercase" onkeypress="if(event.which ==32) { event.preventDefault(); return false; }";>
          </div>
        </div>

        {{--
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Domain Name Without Protocol') }} <br><small class="text-danger">example.com</small></label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="domain_name">
          </div>
        </div>

        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Full Domain With Protocol') }}  <small class="text-danger">https://example.com</small></label>

         
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="full_domain">
          </div>
        </div>

        --}}

        

    
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
          <div class="col-sm-12 col-md-7">
            <button class="btn btn-success basicbtn" type="submit">{{ __('Save') }}</button>
          </div>
        </div>

        <span>{{ __('Subdomain Example') }}: <b><span class="text-danger">sub1.</span>{{ env('APP_PROTOCOLESS_URL') }}</b></span><br>
        @php
        $ext='\.';
        $ext=str_replace('.', "", $ext);
        $paths=explode($ext, base_path());
        $count=count($paths);
        @endphp
        {{--
        <span>Your Root Path: <b><span class="text-danger">@foreach($paths as $key => $row) 
          @php
          $key=$key+1;
          @endphp 
         @if($key != $count){{ $row }}\@endif 
         @endforeach</span></span>
          <br>
        <span>{{ __('Note') }}: <b><span class="text-danger">{{ __('Before Add New Domain Please Create Domain Manually On Your Server The Domain Root Path Is Same With Your Project Directory') }}</span></b></span>
        --}}
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
<script type="text/javascript">
  "use strict";
    $('#shop_name').keyup(function() {
      var value = $(this).val();
      var value = value.replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
      $(this).val(value);
    });
</script>
@endpush