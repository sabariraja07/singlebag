@extends('layouts.seller')
@section('title', 'Add Shipping Location')
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
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">{{ __('Add Shipping Location') }}</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a
                                    href="{{ url('/seller/shipping_location') }}">{{ __('Shipping Location') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Add Shipping Location') }}
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
                    <form class="basicform_with_reset" action="{{ route('seller.shipping_location.store') }}" method="post">
                        @csrf
                        <div class="form-group row mb-1">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Select Country') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <select class="select2 form-control" name="country">
                                    <option value="0">{{ __('Select Country') }}</option>
                                    @foreach(App\Models\Country::where('status',1)->get() as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                  </select>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Select State') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <select class="select2 form-control" name="state">
                                    <option value="0">{{ __('Select State') }}</option>
                                    @foreach(App\Models\State::where('status',1)->get() as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                  </select>
                            </div>
                        </div>
                        <div class="form-group row mb-1">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Select City') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <select class="select2 form-control" name="city">
                                    <option value="0">{{ __('Select City') }}</option>
                                    @foreach(App\Models\City::where('status',1)->get() as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                  </select>
                            </div>
                        </div>


                        <div class="form-group row mb-1">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Estimated Delivery Day') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" class="form-control" name="delivery_time" placeholder="1">
                            </div>
                        </div>

                        <div class="form-group row mb-1">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Rate') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" class="form-control" required="" name="rate" id="rate" onkeyup="rate_validation()">
                            </div>
                        </div>

                        <div class="form-group row mb-1">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Zip Code') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" class="form-control" name="zipcode">
                            </div>
                        </div>

                        <div class="form-group row mb-1">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <select class="form-control selectric" name="status">
                                    <option value="1" selected="">{{ __('Active') }}</option>
                                    <option value="2">{{ __('Inactive') }}</option>

                                </select>
                            </div>
                        </div>


                        <div class="form-group row mb-1">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <button class="btn btn-primary basicbtn" type="submit"
                                    id="submit">{{ __('Save') }}</button>
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
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <script>
   function rate_validation() {
        var rate = $('#rate').val();
        var number = /^[0-9]+$/;
        if (rate != "") {
            if (rate.match(number)) {

            } else {
                $('#rate').focus();
                Sweet('error', '{{ __('Only numbers allowed') }}');
                $('#rate').val('');
                return false;

            }
        }
    }
    </script>
 
@endsection
