@extends('layouts.seller')
@section('title', 'Edit Images')
@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.css') }}">
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">{{ __('Images') }}</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ url('/seller/product') }}">{{ __('Products') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Images') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-3">
                            @include('reseller.products.edit.tab')
                        </div>
                        <div class="col-sm-9">
                            <lable><span style="color:red;">(</span> 650 x 650 mm <span style="color:red;">)</span></label>
                                <form action="{{ route('seller.media.store') }}" enctype="multipart/form-data"
                                    class="dropzone" id="mydropzone">
                                    @csrf
                                    <input type="hidden" name="term" value="{{ $info->id }}">
                                </form>
                                <div class="row">
                                    @foreach ($info->gellery as $key => $row)
                                        <div class="col-sm-3" id="m_area{{ $key }}">
                                            <div class="card">
                                                <div class="card-body">
                                                    <img src="{{ asset($row->url) }}" alt="" height="100"
                                                        width="150">
                                                </div>
                                                <div class="card-footer">
                                                    <button class="btn btn-danger col-12"
                                                        onclick="remove_image('{{ base64_encode($row->id) }}',{{ $key }})">{{ __('Remove') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>



                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    </div>

    <form class="basicform" action="{{ route('seller.medias.destroy') }}">
        @csrf
        <input type="hidden" name="m_id" id="m_id">
    </form>
@endsection
@section('page-script')
    <script type="text/javascript" src="{{ asset('assets/js/dropzone.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/seller/product/images.js') }}"></script>
@endsection
