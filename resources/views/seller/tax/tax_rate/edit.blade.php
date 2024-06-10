@extends('layouts.seller')
@section('title', 'Edit Tax Rate')
@section('content')
        <div class="content-header row mb-2">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Edit Tax Rate') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('seller.tax_classes.show', $info->tax_class_id) }}">{{ __('Tax Rate') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('Edit Tax Rate') }}
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
                        <form class="basicform" action="{{ route('seller.tax_rate.update', $info->id) }}">
                            @csrf
                            @method('POST')
                            {{-- <div class="form-group row mb-1">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Select Rate Class') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="select2 form-control" name="rate_class">
                                        @if( !empty($info->tax_class->name))
                                            <option>{{  $info->tax_class->name ?? '' }}</option>
                                            @foreach(App\Models\TaxClass::where('status',1)->get() as $row)
                                            <option value="{{ $row->id }}"
                                                @if ($info->tax_class_id == $row->id) selected=""  {{ $info->tax_class->name }} @endif>
                                                {{ $row->name }}</option>
                                            @endforeach
                                        @else
                                        <option value="0">{{ __('Select Tax Rate') }}</option>
                                        @foreach(App\Models\TaxClass::where('status',1)->get() as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                        @endif
                                      </select>
                                </div>
                            </div> --}}
                            <input type="hidden" value="{{ $info->tax_class_id }}" name="rate_class">
                            <div class="form-group row mb-1">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Select Country') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="select2 form-control" name="country">
                                        @if( empty($info->country))
                                        <option>{{ __('Select Country') }}</option>
                                        @foreach(App\Models\Country::where('status',1)->get() as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                        @else
                                         @foreach(App\Models\Country::where('status',1)->get() as $row)
                                         @php
                                        $country_get = App\Models\Country::where('name', $info->country)->first(); 
                                         @endphp
                                         <option value="{{ $row->id }}"
                                             @if ( $country_get->name == $row->name) selected=""  {{ $country_get->country }} @endif>
                                             {{ $row->name }}</option>
                                         @endforeach
                                        @endif
                                        
                                      </select>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Select State') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="select2 form-control" name="state">
                                        @if( empty($info->state_id))
                                        <option value="0">{{ __('Select State') }}</option>
                                        @foreach(App\Models\State::where('status',1)->get() as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                        @else
                                        @foreach(App\Models\State::where('status',1)->get() as $row)
                                        @php
                                       $state_get = App\Models\State::where('id', $info->state_id)->first(); 
                                        @endphp
                                        <option value="{{ $row->id }}"
                                            @if ( $state_get->id == $row->id) selected=""  {{ $state_get->name }} @endif>
                                            {{ $row->name }}</option>
                                        @endforeach

                                        @endif

                                      </select>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Zip Code') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" name="zipcode" autocomplete="off"
                                        value="{{ $info->zipcode ?? '' }}">
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Rate') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" required="" name="rate" id="rate" onkeyup="rate_validation()"  
                                    value="{{ $info->rate ?? '' }}">
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control selectric" name="status">
                                        <option value="1" @if ($info->status == 1) selected="" @endif>
                                            {{ __('Active') }}</option>
                                        <option value="2" @if ($info->status == 2) selected="" @endif>
                                            {{ __('Inactive') }}</option>
                                       

                                    </select>
                                </div>
                            </div>


                            <div class="form-group row mb-1">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7">
                                    <button class="btn btn-primary basicbtn" id="submit"
                                        type="submit">{{ __('Update') }}</button>
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
