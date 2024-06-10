@extends('layouts.app')
@section('head')
    @include('layouts.partials.headersection', ['title' => 'PWA Settings'])
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Edit PWA Settings') }}</h4>

                </div>
                <div class="card-body">

                    <form class="basicform" action="{{ route('admin.pwa.update', $shop->id) }}" method="post">
                        @csrf

                        <div class="form-group row mb-4">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('APP Title') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input class="form-control" name="pwa_app_title" value="{{ $pwa->name ?? '' }}"
                                    type="text" readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label id="sub_domain_label"
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('App Name (Short Name)') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input class="form-control" name="pwa_app_name" value="{{ $pwa->short_name ?? '' }}"
                                    type="text" readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label id="sub_domain_label"
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('APP Background Color') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input class="form-control" name="pwa_app_background_color"
                                    value="{{ $pwa->background_color ?? '' }}" type="text" readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label id="sub_domain_label"
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('APP Theme Color') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input class="form-control" name="pwa_app_theme_color" value="{{ $pwa->theme_color ?? '' }}"
                                    type="text" readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label id="sub_domain_label"
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('APP Main Language') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input class="form-control" name="app_lang" value="{{ $pwa->lang ?? '' }}" type="text"
                                    readonly>
                            </div>
                        </div>
                        @php 
                        if(empty($pwa->pwa_status)){
                            $pwa->pwa_status = "Pending"; 
                        }
                        @endphp
                        <div class="form-group row mb-4">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Status') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <select class="form-control" name="pwa_app_status">
                                    <option value="Pending" @if ($pwa->pwa_status == 'Pending')  selected="" @endif>
                                        {{ __('Pending') }}</option>
                                    <option value="Request Accepted" @if ($pwa->pwa_status == 'Request Accepted') selected="" @endif>
                                        {{ __('Request Accepted') }}</option>
                                    <option value="Processing" @if ($pwa->pwa_status == 'Processing') selected="" @endif>
                                        {{ __('Processing') }}</option>
                                    <option value="Completed" @if ($pwa->pwa_status == 'Completed') selected="" @endif>
                                        {{ __('Completed') }}</option>
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
