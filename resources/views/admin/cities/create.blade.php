@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Create City'])
@endsection
@section('content')
 <div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('Create City') }}</h4>
                
      </div>
      <div class="card-body">

        <form class="basicform_with_reload" action="{{ route('admin.cities.store') }}" method="post">
          @csrf
        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('City Name') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" required="" name="name">
          </div>
        </div>

         <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('State') }}</label>
          <div class="col-sm-12 col-md-7">
            <select class="select2 form-control" name="state">
              @foreach(App\Models\State::get() as $row)
              <option value="{{ $row->id }}">{{ $row->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group row mb-4">
          <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Cost') }}</label>
          <div class="col-sm-12 col-md-7">
            <input type="number" class="form-control" required="" name="cost">
          </div>
        </div>
        <div class="form-group row mb-4">
            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Status') }}</label>
            <div class="col-sm-12 col-md-7">
            <select name="status" class="form-control" id="status">
                {{-- <option selected="" disabled="" label="Status">Status</option> --}}
                <option value="1">Active</option>
                <option value="0" selected="">Inactive</option>
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
@endpush