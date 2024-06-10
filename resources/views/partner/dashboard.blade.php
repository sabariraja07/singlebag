@extends('layouts.app')
@section('content')
<section class="section">
  @if(Auth::user()->isInActivePartner())
  <div class="row">
    <div class="col-12">
      <div class="alert alert-danger">
        You are successfully registered as partner , Please wait for the approval to start your service!
      </div>
    </div>
  </div>
  @endif
  <div class="row">
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-success">
          <i class="far fa-building"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4>{{ __('Total Stores') }}</h4>
          </div>
          <div class="card-body">
            {{ $all }}
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-info">
          <i class="far fa-building"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4>{{ __('Active Stores') }}</h4>
          </div>
          <div class="card-body">
            {{ $actives }}
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-primary">
          <i class="far fa-credit-card"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4>{{ __('Total Earn') }}</h4>
          </div>
          <div class="card-body" >
           {{ $settlements }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<input type="hidden" id="base_url" value="{{ url('/') }}">
<input type="hidden" id="site_url" value="{{ url('/') }}">
<input type="hidden" id="dashboard_static" value="{{ route('partner.dashboard.static') }}">
@endsection

@push('js')
<script src="{{ asset('assets/js/chart.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('assets/partner/dashboard.js') }}"></script>

@endpush