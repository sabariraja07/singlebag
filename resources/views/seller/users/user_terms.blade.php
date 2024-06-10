@extends('layouts.seller')
@section('title', 'User Terms')
@section('page-style')
    <style>
        @media screen and (max-width: 900px) {
            .save_mobile_view {
                display: block;
                margin: auto;
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
<div class="content-header row">
      @if (Session::has('success'))
        <div class="alert alert-success">
            <ul style="margin-top: 1rem;">
                <li>{{ Session::get('success') }}</li>
            </ul>
        </div>
      @endif
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">{{ __('User Terms') }}</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">{{ __('Settings') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('User Terms') }}
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
                    <form class="basicform" method="post" action="{{ route('seller.user.term_store') }}">
                        @csrf
        
                        <div class="form-group row mb-4">
                            <label
                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('User Terms Link') }}</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" class="form-control" name="term" required
                                    value="{{ $info->data ?? ''}}">
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <button class="btn btn-primary basicbtn save_mobile_view" id="submit"
                                    type="submit">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
