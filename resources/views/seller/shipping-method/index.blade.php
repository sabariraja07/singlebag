@extends('layouts.seller')
@section('title', 'Shipping Methods')
@section('content')

    <!-- BEGIN: Content-->

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
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Shipping Method') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item">{{ __('Settings') }}
                                </li>
                                <li class="breadcrumb-item active">{{ __('Shipping Method') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="content-body">
            <section>

                <!-- api key list -->
                {{-- <div class="card"> --}}
                <div class="card-header">
                    <h4 class="card-title">{{ __('Shipping Method') }}</h4>
                    <a href="javascript:void(0)" data-bs-target="#addshippingmethod" data-bs-toggle="modal"
                        style="float: right !important;
                            margin-top: -45px !important;">
                        <span class="btn btn-primary mb-1"> {{ __('create') }}</span>
                    </a>
                    <form method="post" action="{{ route('seller.shipping-method.destroy') }}"
                    class="basicform_with_reload">
                    @csrf
                    <div class="d-flex flex-row justify-content-between mt-1">
                        <div class="desktop_view">
                            <div class="input-group">
                                <select class="form-control" name="method">
                                    <option disabled selected="">{{ __('Select Action') }}</option>
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                    <option value="5">{{ __('Delete') }}</option>
                                </select>
                                <button class="btn btn-primary waves-effect basicbtn"
                                    type="submit">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    

                    <!-- Mobile View Start-->
                    <div class="row gy-2 mobile_view">
                        @foreach ($posts as $row)
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
                                                        <a class="dropdown-item d-flex align-items-center"
                                                            href="{{ route('seller.shipping-methods.edit', $row->id) }}">
                                                            <i data-feather="edit-2"
                                                                class="me-50"></i><span>{{ __('Edit') }}</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div>
                                                <h3 style="word-break: break-all;margin-right: 25px;">{{ $row->title }}</h3>
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

                    <!-- Desktop View Start -->
                    <div class="card mobile_view desktop_view">
                        <div class="card-body">
                            <table class="datatables-basic table" id="pagenate">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="checkAll"></th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('DELIVERY CHARGES') }}</th>
                                        <th>{{ __('ESTIMATED DELIVERY DAY') }}</th>
                                        <th>{{ __('LAST UPDATE') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($posts as $row)
                                        <tr id="row{{ $row->id }}">
                                            <td><input type="checkbox" name="ids[]" value="{{ $row->id }}"></td>
                                            <td>{{ $row->title }}</td>
                                            <td>{{ $row->cost }}</td>
                                            <td>{{ $row->estimated_delivery }}</td>
                                            <td>{{ \Carbon\Carbon::parse($row->updated_at)->diffForHumans() }}</td>

                                            <td>
                                                <a href="{{ route('seller.shipping-methods.edit', $row->id) }}"
                                                    class="btn btn-icon btn-outline-success waves-effect waves-float waves-light">
                                                    <i class="ph-pencil-bold"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--Desktop View End -->

                </div>
                {{-- </div> --}}
            </form>
                <div class="d-flex flex-row justify-content-end mt-2">
                    {{ $posts->links('vendor.pagination.bootstrap-4') }}
                </div>
            </section>

        </div>
    </div>
    <div class="modal fade" id="addshippingmethod" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{ __('Add New Shipping Method') }}</h1>

                    </div>
                    <form method="post" action="{{ route('seller.shipping-methods.store') }}" class="row gy-1 pt-75">
                        @csrf

                        <div class="col-12 mb-1">
                            <label class="form-label">{{ __('Title') }}</label>
                            <input type="text" class="form-control" required="" name="title">
                        </div>
                        <div class="col-12 mb-1">
                            <label class="form-label">{{ __('Estimated Delivery Days') }}</label>
                            <input type="text" step="any" class="form-control" required=""
                                name="estimated_delivery" placeholder="1-2 days">
                        </div>
                        <div class="col-12 mb-1">
                            <label class="form-label">{{ __('DELIVERY CHARGES') }}</label>
                            <input type="number" step="any" class="form-control" required="" name="cost">
                        </div>
                        <div class="col-12 mb-1">
                            <label class="form-label">{{ __('Status') }}</label>
                            <select name="status" class="form-select @if ($errors->has('status')) is-invalid @endif"
                                value="{{ old('status') }}" id="statusSelect">
                                <option selected="" disabled="" >{{ __('Select Status') }}</option>
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Inactive') }}</option>
                            </select>
                            @if ($errors->has('status'))
                                <label id="statusSelect-error"
                                    class="error invalid-feedback">{{ $errors->first('status') }}</label>
                            @endif
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" id="submit"
                                class="btn btn-primary me-1 basicbtn">{{ __('Save') }}</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                aria-label="Close">
                                {{ __('Discard') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- END: Content-->

@endsection
@section('vendor-script')
    <script src="./admin/js/scripts/forms/form-select2.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
@endsection
