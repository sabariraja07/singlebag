@extends('layouts.seller')
@section('title', 'Edit Shipping Method')
@section('page-style')
    <style>
        /* .select2-selection__arrow b {
                            border-style: hidden !important;
                        } */
        .select2-container--classic .select2-selection--single .select2-selection__arrow b,
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23d8d6de' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-chevron-down'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-size: 18px 14px, 18px 14px;
            background-repeat: no-repeat;
            height: 1rem;
            padding-right: 1.5rem;
            margin-left: 0;
            margin-top: 0;
            left: -8px;
            border-style: none;
        }
    </style>

@endsection
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
                <div class="alert alert-success">
                    <ul style="margin-top: 1rem;">
                        <li>{{ Session::get('error') }}</li>
                    </ul>
                </div>
            @endif
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Edit Shipping Method') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a
                                        href="{{ url('seller/shipping-methods') }}">{{ __('Shipping Methods') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('Edit Shipping Method') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
        </div>

        <div class="content-body">

            <div class="row justify-content-center">
                <div class="col-md-5 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('Edit Shipping Method') }}</h4>
                        </div>
                        <div class="card-body">
                            <form class="form form-vertical"
                                action="{{ route('seller.shipping-methods.update', $info->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-1">
                                            <label class="form-label"
                                                for="shipping-method-title">{{ __('Title') }}</label>
                                            <input type="text" name="title" id="shipping-method-title"
                                                class="form-control @if ($errors->has('title')) is-invalid @endif"
                                                value="{{ $info->title ?? old('title') }}" placeholder="Your Shop name" />
                                            @if ($errors->has('title'))
                                                <span id="shipping-method-title-error"
                                                    class="error">{{ $errors->first('title') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1">
                                            <label class="form-label"
                                                for="estimated_delivery">{{ __('ESTIMATED DELIVERY DAY') }}</label>
                                            <input type="text" name="estimated_delivery" id="estimated_delivery"
                                                class="form-control @if ($errors->has('estimated_delivery')) is-invalid @endif"
                                                value="{{ $info->estimated_delivery ?? old('estimated_delivery') }}"
                                                placeholder="Estimate delivery time" />
                                            @if ($errors->has('estimated_delivery'))
                                                <span id="estimated_delivery-error"
                                                    class="error">{{ $errors->first('estimated_delivery') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 mb-1">
                                        <label class="form-label" for="costInput">{{ __('Delivery Cost') }}</label>
                                        <input type="number" name="cost" id="costInput"
                                            class="form-control @if ($errors->has('cost')) is-invalid @endif"
                                            value="{{ $info->cost ?? old('cost') }}" placeholder="Delivery Cost" />
                                        @if ($errors->has('cost'))
                                            <span id="cost-error" class="error">{{ $errors->first('cost') }}</span>
                                        @endif
                                    </div>
                                    <div class="mb-2 col-12">
                                        <label class="form-label">{{ __('Status') }}</label>
                                        <select name="status"
                                            class="form-select @if ($errors->has('status')) is-invalid @endif"
                                            value="{{ old('status') }}" id="statusSelect">
                                            <option selected="" disabled="" label="Select Status">
                                                {{ __('Select Status') }}
                                            </option>
                                            <option value="1"
                                                @if ($info->status == 1) {{ 'selected' }} @endif>
                                                {{ __('Active') }}
                                            </option>
                                            <option value="0"
                                                @if ($info->status == 0) {{ 'selected' }} @endif>
                                                {{ __('Inactive') }}
                                            </option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <label id="statusSelect-error"
                                                class="error invalid-feedback">{{ $errors->first('status') }}</label>
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <button type="submit"
                                            class="btn btn-primary me-1 waves-effect waves-float waves-light">{{ __('Submit') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('Create Shipping Location') }}</h4>
                        </div>
                        <div class="card-body">
                            <form class="form form-vertical" action="{{ route('seller.shipping-locations.store') }}"
                                method="POST">
                                @csrf
                                <div class="row">
                                    <div class="mb-2 col-12">
                                        <label class="form-label">{{ __('Location') }}</label>
                                        <input type="hidden" name="shipping_method" value="{{ $info->id }}">
                                        {{-- <select name="location"
                                            class="location select2 form-select @if ($errors->has('location')) is-invalid @endif"
                                            value="{{ old('location') }}" id="locationSelect">
                                            <option selected="" disabled="" label="Select City">Select City</option> --}}
                                        {{-- <option value="1"  @if ($info->location == 1) {{ 'selected' }} @endif>Active</option>
                                <option value="0"  @if ($info->location == 0) {{ 'selected' }} @endif>Inactive</option> --}}
                                        {{-- </select> --}}
                                        <select name="location" id="locationSelect" class="select2 form-select">

                                        </select>
                                        @if ($errors->has('location'))
                                            <label id="statuslocationSelect-error"
                                                class="error invalid-feedback">{{ $errors->first('location') }}</label>
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <button type="submit"
                                            class="btn btn-primary me-1 waves-effect waves-float waves-light">{{ __('Submit') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card mobile_view desktop_view">
                        <div class="card-header mb-10">
                            <div class="head-label">
                                <h4 class="card-title">{{ __('Locations') }}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="datatables-basic table" id="pagenate">
                                <thead>
                                    <tr>
                                        <th>{{ __('#') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('LAST UPDATE') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($info->locations as $row)
                                        <tr id="row{{ $row->id }}">

                                            <td>{{ $i++ }}</td>
                                            <td>{{ $row->city->name ?? '' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($row->updated_at)->diffForHumans() }}</td>
                                            <td>
                                                @if ($row->status == 0)
                                                    <a href="{{ route('seller.shipping-locations.destroy', ['id' => $row->id, 'type' => 1]) }}"
                                                        class="btn btn-icon btn-outline-primary waves-effect waves-float waves-light">
                                                        <i class="ph-eye-bold"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('seller.shipping-locations.destroy', ['id' => $row->id, 'type' => 0]) }}"
                                                        class="btn btn-icon btn-outline-danger waves-effect waves-float waves-light">
                                                        <i class="ph-trash-bold"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Mobile View Start-->
                    <div class="row gy-2 mobile_view">

                        @foreach ($info->locations as $row)
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body" style="padding: 0.5rem !important;">
                                        <div class="position-relative rounded p-25">
                                            <div class="dropdown dropstart btn-pinned" style="top: 0.2rem !important;">
                                                <a class="btn btn-icon rounded-circle hide-arrow dropdown-toggle p-0"
                                                    href="javascript:void(0)" id="dropdownMenuButton1"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i data-feather="more-vertical" class="font-medium-4"></i>
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>

                                                        @if ($row->status == 0)
                                                            <a class="dropdown-item d-flex align-items-center"
                                                                href="{{ route('seller.shipping-locations.destroy', ['id' => $row->id, 'type' => 1]) }}">
                                                                <i data-feather="eye"
                                                                    class="me-50"></i><span>{{ __('Enable') }}</span>
                                                            </a>
                                                        @else
                                                            <a class="dropdown-item d-flex align-items-center"
                                                                href="{{ route('seller.shipping-locations.destroy', ['id' => $row->id, 'type' => 0]) }}">
                                                                <i data-feather="trash"
                                                                    class="me-50"></i><span>{{ __('Disable') }}</span>
                                                            </a>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="d-flex">
                                                <h3>{{ $row->city->name ?? '' }}</h3>
                                                <div class="blog-info">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!--Mobile View End -->
                </div>

            </div>
        </div>
    </div>
    {{-- <div class="content-wrapper container-xxl p-0">
        
        <div class="content-header row mb-2">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Edit Shipping Method') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{ __('Shipping') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('Edit Shipping Method') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
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
            <div class="row">
                <div class="col-12 col-md-5">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Edit Shipping Method') }}</h4>
                        </div>
                        <div class="card-body">
                            <form class="basicform" action="{{ route('seller.shipping-methods.update', $info->id) }}"
                                method="post">
                                @csrf
                                @method('PUT')
                                <div class="form-group row mb-4">
                                    <label
                                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Title') }}</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" class="form-control" required="" name="title"
                                            value="{{ $info->name }}">
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label
                                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Estimated Delivery Day') }}</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" step="any" class="form-control" required=""
                                            placeholder="1-2 days" name="delivery_time"
                                            value="{{ $delivery_time->content ?? '' }}">
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label
                                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">{{ __('Delivery Charges') }}</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="number" step="any" class="form-control" required=""
                                            name="price" value="{{ $info->slug }}">
                                    </div>
                                </div>
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                    <div class="col-sm-12 col-md-7">
                                        <button class="btn btn-primary basicbtn"
                                            type="submit">{{ __('Update') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script type='text/javascript'>
        // $(document).ready(function() {
        //     $("#locationSelect").select2({
        //         ajax: {
        //             url: "{{ url('search-cities') }}",
        //             type: "post",
        //             delay: 250,
        //             dataType: 'json',
        //             data: function(params) {
        //                 return {
        //                     q: params.term, // search term
        //                     "_token": "{{ csrf_token() }}",
        //                 };
        //             },
        //             processResults: function(response) {
        //                 return {
        //                     results: response
        //                 };
        //             },
        //             cache: true
        //         }
        //     });
        // });
        // $(function() {
        $(document).ready(function() {
            $("#locationSelect").select2({
                ajax: {
                    url: "{{ url('search-cities') }}",
                    dataType: 'json',
                    delay: 250,
                    type: "post",
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
                            "_token": "{{ csrf_token() }}"
                        };
                    },
                    processResults: function(params) {
                        params.page = params.page || 1;

                        return {
                            results: params.cities,
                            pagination: {
                                // more: (params.page) 
                                more: (params.page * 10) < params.count
                            }
                        };
                    },
                    cache: true
                },
                placeholder: 'Search for a City',
                //   minimumInputLength: 1,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            });

            function formatRepo(repo) {
                if (repo.loading) {
                    return repo.text;
                }

                var $container = $(
                    "<div class='select2-result-repository clearfix'>" +
                    "<div class='select2-result-repository__meta'>" +
                    "<div class='select2-result-repository__title'></div>" +
                    "<div class='select2-result-repository__description'></div>" +
                    "<div class='select2-result-repository__statistics'>" +
                    "</div>" +
                    "</div>" +
                    "</div>"
                );

                $container.find(".select2-result-repository__title").text(repo.text);


                return $container;
            }

            function formatRepoSelection(repo) {
                return repo.text;
            }
        });
    </script>

@endsection
