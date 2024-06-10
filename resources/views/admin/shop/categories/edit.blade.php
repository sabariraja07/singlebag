@extends('layouts.app')
@section('head')
    @include('layouts.partials.headersection', ['title' => 'Shop Category'])
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Edit Shop Category') }}</h4>

                </div>
                <div class="card-body">

                    <form class="basicform" action="{{ route('admin.shop-categories.update', $info->id) }}" method="post">
                        @csrf
                        @method('PUT')

                         <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Title') }}</label>
                            <div class="col-sm-12 col-md-7">
                              <input type="text" class="form-control" required="" name="title" value="{{ $info->name }}">
                            </div>
                          </div>
                  
                          <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Description') }}</label>
                            <div class="col-sm-12 col-md-7">
                              <textarea class="form-control" required="" name="description">{{ $info->description ?? "" }}</textarea>
                            </div>
                          </div>

                          <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3" >{{ __('Status') }}</label>
                            <div class="col-sm-12 col-md-7">
                              <select class="form-control" name="status">
                                <option value="1" @if ($info->status == 1) selected="" @endif>Active</option>
                                <option value="0" @if ($info->status == 0) selected="" @endif>Inactive</option>
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
