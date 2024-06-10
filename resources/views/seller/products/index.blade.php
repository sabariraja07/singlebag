@extends('layouts.seller')
@section('title', 'Products')
@section('page-style')
    <style>
        @media screen and (max-width: 900px) {
            .active {
                border: 1px solid blue;
            }

            #action_mobile {
                margin: auto;
                display: block !important;
            }

            #action_buttons,
            #card_title {
                text-align: center !important;
            }

            .desktop_view {
                display: none;
            }

            /* .product_count{
                        margin-bottom: 14px;
                    } */

        }

        @media only screen and (max-width: 768px) {
            div.dt-buttons {
                justify-content: center;
                margin-top: 5rem !important;
            }

        }
    </style>

@endsection
@section('content')
    <!-- BEGIN: Content-->
    @php
        $url = domain_info('full_domain');
    @endphp
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            @if (Session::has('error'))
                <div class="alert alert-danger">
                    <ul style="margin-top: 1rem;">
                        <li>{{ Session::get('error') }}</li>
                    </ul>
                </div>
            @endif
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
                        <h2 class="content-header-title float-start mb-0">{{ __('Products') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('All Products') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-header row">
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('seller.products.index', 'type=1') }}"
                        class="mr-2 mb-2 btn btn-outline-success @if ($type == 1) active @endif">{{ __('Publish') }}
                        ({{ $actives }})</a>

                    <a href="{{ route('seller.products.index', 'type=2') }}"
                        class="mr-2 mb-2 btn btn-outline-info @if ($type == 2) active @endif">{{ __('Draft') }}
                        ({{ $drafts }})</a>

                    <a href="{{ route('seller.products.index', 'type=3') }}"
                        class="mr-2 mb-2 btn btn-outline-warning product_count @if ($type == 3) active @endif">{{ __('Incomplete') }}
                        ({{ $incomplete }})</a>
                    <a href="{{ route('seller.products.index', 'type=0') }}"
                        class="mr-2 mb-2 btn btn-outline-danger product_count @if ($type == 0 && $type != 'all') active @endif">{{ __('Trash') }}
                        ({{ $trash }})</a>
                </div>
            </div>
            <div class="dt-action-buttons text-end" id="action_buttons" style="margin-top: -62px;">
                <div class="dt-buttons d-inline-flex">
                    @if (current_shop_type() == 'reseller')
                        <a href="{{ route('seller.supplier.products') }}" type="button"
                            class="dt-button create-new btn btn-primary">
                            <span>
                                <i class="ph-plus-bold"></i>
                                {{ __('Find Products to Sell') }}
                            </span>
                        </a>
                    @else
                        <a href="{{ route('seller.products.create') }}" type="button"
                            class="dt-button create-new btn btn-primary">
                            <span>
                                <i class="ph-plus-bold"></i>
                                {{ __('Create Product') }}
                            </span>
                        </a>
                    @endif &nbsp;
                    @if (current_shop_type() != 'reseller')
                        <div>
                            <a href="#" class="btn btn-info float-right mr-3" class="edit" data-bs-toggle="modal"
                                data-bs-target="#import">+{{ __('Import') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="d-flex flex-row justify-content-end">
                <div class="mt-1">
                    <form>
                        <div class="input-group">
                            <input type="text" id="src" class="form-control" placeholder="Search..."
                                name="src" autocomplete="off" value="{{ $src ?? '' }}" required>
                            <input type="hidden" name="type" value="{{ $type }}">
                            <select class="form-control selectric" name="search_type" id="type">
                                <option value="title">{{ __('Search By Name') }}</option>
                                <option value="id">{{ __('Search By Id') }}</option>
                            </select>
                            <button class="btn btn-outline-primary waves-effect" type="submit">
                                <i class="ph-magnifying-glass-bold"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <form method="post" action="{{ route('seller.products.destroy') }}" class="basicform_with_reload">
                @csrf
                <div class="d-flex flex-row justify-content-between" id="action_mobile">
                    <div class="mt-1 desktop_view">
                        <div class="input-group">
                            <select class="form-select" name="method">
                                <option disabled selected="">{{ __('Select Action') }}</option>
                                <option value="1">{{ __('Publish Now') }}</option>
                                <option value="2">{{ __('Draft') }}</option>
                                @if ($type == 0 && $type != 'all')
                                    {{-- <option value="delete" class="text-danger">{{ __('Delete Permanently') }}</option> --}}
                                @else
                                    <option value="0">{{ __('Move To Trash') }}</option>
                                @endif
                                <option value="delete">{{ __('Delete') }}</option>
                            </select>
                            <button class="btn btn-primary waves-effect basicbtn"
                                type="submit">{{ __('Submit') }}</button>
                        </div>
                    </div>
                </div>
                <div class="card desktop_view mt-2">
                    <div class="card-body">
                        <div class="table-responsive mb-1">
                            <table class="table" id="pagenate">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="checkAll"></th>
                                        <th>{{ __('Name') }}</th> 
                                        <th>{{ __('Total Sales') }}</th>
                                        @if (current_shop_type() == 'reseller')
                                        <th>{{ __('Supplier Status') }}</th>
                                        @else
                                        <th>{{ __('Status') }}</th>
                                        @endif
                                        <th>{{ __('LAST UPDATE') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($posts as $row)
                                        <tr id="row{{ $row->id }}">
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="ids[]" class="custom-control-input"
                                                        id="customCheck{{ $row->id }}" value="{{ $row->id }}">
                                                    <label class="custom-control-label"
                                                        for="customCheck{{ $row->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar me-50">
                                                        <a href="{{ route('seller.products.edit_new', $row->id) }}">
                                                            <img class="avatar" src="{{ asset($row->image ?? 'uploads/default.png') }}"
                                                                alt="{{ $row->title ?? '' }}" width="38" height="38" />
                                                        </a>
                                                    </div>
                                                    <div class="more-info">
                                                        @if($row->id == $request->highlight)
                                                            <h6 class="mb-0 text-primary">
                                                                {{ $row->title }}
                                                            </h6>
                                                        @else
                                                            <h6 class="mb-0">
                                                                {{ $row->title }}
                                                            </h6>
                                                        @endif
                                                        <p class="mb-0">#{{ $row->id }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $row->orders_count }}</td>
                                            <td>
                                                @if ($row->status == 1)
                                                    <span class="badge badge-light-success">{{ __('Active') }}</span>
                                                @elseif($row->status == 2)
                                                    <span class="badge badge-light-info">{{ __('Draft') }}</span>
                                                @elseif($row->status == 3)
                                                    <span class="badge badge-light-warning">{{ __('Incomplete') }}</span>
                                                @elseif($row->status == 0)
                                                    <span class="badge badge-light-danger">{{ __('Trash') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $row->updated_at->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ route('seller.products.edit_new', $row->id) }}"
                                                    class="btn btn-icon btn-outline-success waves-effect waves-float waves-light">
                                                    <i class="ph-pencil-bold"></i>
                                                </a>
                                                @if (current_shop_type() != 'supplier')
                                                    @if ($type == 1)
                                                        <a href="{{ $url . '/product/' . $row->slug . '/' . $row->id }}"
                                                            class="btn btn-icon btn-outline-primary waves-effect waves-float waves-light">
                                                            <i class="ph-eye-bold"></i>
                                                        </a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row gy-2 mobile_view mt-1">
                @foreach ($posts as $row)
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title card-title d-flex align-items-start justify-content-start">
                                    <div class="card-image me-50">
                                        <a href="javascript:void(0)">
                                            <img class="" src="{{ asset($row->image ?? 'uploads/default.png') }}"
                                                alt="{{ $row->title ?? '' }}" />
                                        </a>
                                    </div>
                                    <div class="more-info">
                                        <h6 class="mb-0">{{ $row->title }}</h6>
                                        <p class="mb-0">#{{ $row->id }}s</p>
                                        <div class="text-muted mb-0">
                                            @if ($row->status == 1)
                                                <span class="badge badge-light-primary mb-1">{{ __('Active') }}</span>
                                            @elseif($row->status == 2)
                                                <span class="badge badge-light-info mb-1">{{ __('Draft') }}</span>
                                            @elseif($row->status == 3)
                                                <span
                                                    class="badge badge-light-warning mb-1">{{ __('Incomplete') }}</span>
                                            @elseif($row->status == 0)
                                                <span class="badge badge-light-danger mb-1">{{ __('Trash') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown chart-dropdown card-dropdown">
                                    <i data-feather="more-vertical" class="font-medium-3 cursor-pointer"
                                        data-bs-toggle="dropdown"></i>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item d-flex align-items-center"
                                            href="{{ route('seller.products.edit', $row->id) }}">
                                            <i data-feather="edit-2" class="me-50"></i>
                                            {{ __('Edit') }}
                                        </a>
                                        @if($row->status != 0)
                                        <form method="post" action="{{ route('seller.products.destroy') }}" class="basicform_with_reload">
                                            @csrf
                                            <input type="hidden" name="ids[]" value="{{ $row->id }}">
                                            <input type="hidden" name="method" value="0">
                                            <button type="submit" class="dropdown-item d-flex align-items-center"
                                                href="{{ route('seller.products.edit', $row->id) }}">
                                                <i data-feather="trash" class="me-50"></i>
                                                {{ __('Trash') }}
                                            </button>
                                        </form>
                                        @endif
                                        {{-- @if (current_shop_type() != 'supplier')
                                            @if ($type == 1)
                                                <a class="dropdown-item d-flex align-items-center"
                                                    href="{{ $url . '/product/' . $row->slug . '/' . $row->id }}">
                                                    <i data-feather="eye" class="me-50"></i>
                                                    <span>{{ __('View') }}</span>
                                                </a>
                                            @endif
                                        @endif --}}
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-start justify-content-start">
                                    <div><b>{{ $row->orders_count }}</b> <small>{{ __('Sales') }}</small></div>
                                    <span>Last Update on {{ $row->updated_at->diffForHumans() ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex flex-row justify-content-end mt-2">
                {{ $posts->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>


    <!-- END: Content-->
    <div class="modal fade" id="import" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{ __('Product Import') }}</h1>
                        <p></p>
                    </div>

                    <form action="{{ route('seller.products.import') }}" method="POST" class="product_upload"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="col-12 mb-1 mb-sm-0">
                                <input class="form-control" type="file" name="file" id="file"
                                    accept=".csv">
                            </div>
                        </div>
                        @if (current_shop_type() == 'seller')
                            <div class="col-12 mt-2 pt-50">
                                <div class="import_area">
                                    <div>
                                        <p class="text-left"><a
                                                href="{{ asset('assets/pdf/seller_product_upload.csv') }}">{{ __('Download Sample') }}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (current_shop_type() == 'supplier')
                            <div class="col-12 mt-2 pt-50">
                                <div class="import_area">
                                    <div>
                                        <p class="text-left"><a
                                                href="{{ asset('assets/pdf/supplier_product_upload.csv') }}">{{ __('Download Sample') }}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-12 text-center mt-2 pt-50">

                            <button type="submit" class="btn btn-primary basicbtn">+{{ __('Import') }}</button>
                            <a type="reset" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal"
                                aria-label="Close">
                                {{ __('Close') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="import" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Product Import') }}</h5>


                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/seller/product/index.js') }}"></script>
    <script>
        $('.mobile_view_card').on('click', function(e) {
            e.stopPropagation();
            var id = $(this).attr("data-id");
            if ($(this).hasClass('active') == true) {
                $(this).removeClass('active');
                $('#product_id_' + id).prop("checked", false);
            } else {
                $('#product_id_' + id).prop("checked", true);
                $(this).addClass('active');
            }
            var id = $(this).attr("data-id");
            $('#product_id_' + id).prop("checked", true);
        });
    </script>
@endsection
