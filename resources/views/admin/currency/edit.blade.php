@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection', ['title' => 'Edit Currency'])
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ __('Edit Currency') }}</h4>

            </div>
            <div class="card-body">

                <form class="basicform" action="{{ route('admin.currencies.update',$info->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Currency') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <select class="select2 form-control" name="currency" readonly>
                                @foreach(get_currency_codes() as $code => $currency)
                                <option value="{{ $code }}" @if($info->code == $code) selected="" @endif>
                                    {{ currency($code) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
                        <div class="col-sm-12 col-md-7">
                            <select name="status" class="form-control" id="status">
                                <option value="1" @if ($info->status == 1) selected="" @endif>
                                    {{ __('Active') }}
                                </option>
                                <option value="0" @if ($info->status == 0) selected="" @endif>
                                    {{ __('Inactive') }}
                                </option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                        <div class="col-sm-12 col-md-7">
                            <button class="btn btn-success basicbtn" type="submit">{{ __('Update') }}</button>
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