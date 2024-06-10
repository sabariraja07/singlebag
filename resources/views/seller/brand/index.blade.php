@extends('layouts.seller')
@section('title', 'Brands')
@section('page-style')
    <style>
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

    <!-- BEGIN: Content-->

    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Brands') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('All Brands') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="d-flex flex-row justify-content-end">
                <div class="mt-1">
                    <a href="{{ route('seller.brand.create') }}" style=" float: right; ">
                        <span class="btn btn-primary mb-1">+{{ __('create') }}</span>
                    </a>
                    <form>
                        <div class="input-group">
                            <input type="text" id="src" class="form-control" placeholder="Search..."
                                name="src" autocomplete="off" value="{{ $src ?? '' }}" required>
                            <select class="form-control selectric" name="search_type" id="type">
                                <option value="name">{{ __('Search By Name') }}</option>
                                {{-- <option value="id">{{ __('Search By Id') }}</option> --}}
                            </select>
                            <button class="btn btn-outline-primary waves-effect" type="submit">
                                <i class="ph-magnifying-glass-bold"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <form method="post" action="{{ route('seller.brands.destroy') }}"
            class="basicform_with_reload">
            @csrf
                <div class="desktop_view">
                    <div class="d-flex flex-row justify-content-between mt-1">
                        <div>
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
                <div class="row gy-2 mobile_view">
                    @php
                        $url = my_url();
                    @endphp
                    @foreach ($posts as $row)
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title d-flex align-items-center">
                                        <div class="avatar me-50">
                                            <a href="javascript:void(0)">
                                                <img class="avatar" src="{{ $row->image }}"
                                                    alt="{{ $row->name ?? '' }}" width="38" height="38" />
                                            </a>
                                        </div>
                                        <div class="more-info">
                                            <h6 class="mb-50">{{ $row->name ?? '' }}</h6>
                                            <p class="mb-0">
                                                @if ($row->status == 1)
                                                    <span class="badge badge-light-primary mb-1">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge badge-light-danger mb-1">{{ __('Inactive') }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
    
                                    <div class="dropdown chart-dropdown card-dropdown">
                                        <i data-feather="more-vertical" class="font-medium-3 cursor-pointer" data-bs-toggle="dropdown"></i>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="{{ route('seller.brand.edit', $row->id) }}">
                                                <i data-feather="edit-2" class="me-50"></i><span>{{ __('Edit') }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div><span> Created at :<b> {{ $row->created_at->format('d-F-Y') ?? '' }}</b></span></div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div><br>
                <div class="card mobile_view desktop_view">
                    <div class="card-body">
                        <div class="table-responsive mb-1">
                            @php
                                $url = my_url();
                            @endphp
                            <table class="table" id="pagenate">
                                <div class="d-flex flex-row justify-content-end">
                                    {{ $posts->links('vendor.pagination.bootstrap-4') }}
                                </div>
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="checkAll"></th>
                                        <th>{{ __('Name') }}</th>
                                        {{-- <th>{{ __('Url') }}</th> --}}
                                        @if (current_shop_type() != 'supplier')
                                            <th>{{ __('Featured') }}</th>
                                        @endif
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('CREATED AT') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($posts as $row)
                                        <tr id="row{{ $row->id }}">
                                            <td><input type="checkbox" name="ids[]" value="{{ $row->id }}"></td>
                                            {{-- <td><input type="checkbox" name="ids[]"
                                                        value="{{ base64_encode($row->id) }}"></td>  --}}
                                            <td>
                                                <div class="d-flex">
                                                    <img src="{{ $row->image }}" class="rounded me-1" height="50"
                                                        alt="">
                                                    <h6 class="align-self-center mb-0">{{ $row->name }}</h6>
                                                </div>
                                            </td>
                                            {{-- <td><a
                                                    href="{{ $url . '/brand/' . $row->slug . '/' . $row->id }}">{{ $url . '/brand/' . $row->slug . '/' . $row->id }}</a>
                                            </td> --}}
                                            @if (current_shop_type() != 'supplier')
                                                <td>
                                                    @if ($row->featured == 1)
                                                        <span
                                                            class="badge badge-light-success">{{ __('Yes') }}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-light-danger">{{ __('No') }}</span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                @if ($row->status == 1)
                                                    <span class="badge badge-light-success">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge badge-light-danger">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $row->created_at->diffforHumans() }}</td>
                                            <td>
                                                <a href="{{ route('seller.brand.edit', $row->id) }}"
                                                    class="btn btn-icon btn-primary waves-effect waves-float waves-light">
                                                    <i class="ph-pencil-bold"></i>
                                                </a>
                                                @if (current_shop_type() != 'supplier')
                                                    @if ($row->status == 1)
                                                        <a href="{{ $url . '/brand/' . $row->slug . '/' . $row->id }}"
                                                            class="btn btn-icon btn-primary waves-effect waves-float waves-light">
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
                        </form>
                        <div class="d-flex flex-row justify-content-end mt-2">
                            {{ $posts->links('vendor.pagination.bootstrap-4') }}
                        </div>
                        {{-- <span>{{ __('Note') }}: <b
                                    class="text-danger">{{ __('For Better Performance Remove Unusual brands') }}</b></span> --}}
                    </div>
                </div>
                <span>{{ __('Note') }}: <b
                        class="text-danger">{{ __('For Better Performance Remove Unusual brands') }}</b></span>
            </form>
        </div>
    </div>

    <!-- END: Content-->

@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
@endsection
