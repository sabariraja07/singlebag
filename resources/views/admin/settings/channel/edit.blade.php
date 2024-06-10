
@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Edit Channel'])
@endsection
@section('content')
 <div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        <form class="basicform_with_reload" action="{{ route('admin.site.channel_update',$channel->id) }}" method="post">
        @csrf
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Name') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="name" value="{{ $channel->name }}">
          </div>
        </div>
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Type') }}</label>

         
          <div class="col-sm-12 col-md-7">
            <select class="form-control selectric" name="type">
                <option value="online" @if($channel->type =='online') selected="" @endif>{{ __('Online') }}</option>
                <option value="pos" @if($channel->type =='pos') selected="" @endif>{{ __('POS') }}</option>
              </select>
          </div>
        </div> 
        <div class="form-group row mb-4">
            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('URL') }}</label>
  
           
            <div class="col-sm-12 col-md-7">
              <input type="text" class="form-control" required="" name="url" id="url" value="{{ $channel->url }}">
            </div>
          </div>  
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Default') }}</label>
  
           
            <div class="col-sm-12 col-md-7 mt-2">
                <div class="form-check form-switch" style=" padding-left: 0px; ">
                    <label>
                      @if($channel->default =='1')
                        <input type="checkbox" name="default"
                            class="custom-switch-input sm form-check-input" checked value="1">
                          @else
                          <input type="checkbox" name="default"
                          class="custom-switch-input sm form-check-input" value="1">
                      @endif
                        <span class="custom-switch-indicator"></span>
                    </label>
                </div>
              <small class="text-right">{{ __('Note') }}: <span class="text-danger">{{ __('Set whether this channel is the default, this will override the current default.') }}</span></small>
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
@endpush