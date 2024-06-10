@extends('layouts.seller')
@section('title', 'Users')

@section('content')
    <!-- BEGIN: Content-->
    @php
        $url = domain_info('full_domain');
    @endphp
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Users') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item">{{ __('Point Of Sales') }}
                                </li>
                                <li class="breadcrumb-item active">{{ __('Users') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="d-flex justify-content-end mb-50">
                <a href="{{ route('seller.users.create') }}">
                    <span class="btn btn-primary mb-1">{{ __('create') }}</span>
                </a>
            </div>
            <div class="card-body">
                <div class="d-flex flex-row justify-content-end">
                    <div>
                        <form>
                            <input type="hidden" name="type"
                                value="@if ($type === 0) trash @else {{ $type }} @endif">
                            <div class="input-group" style="margin-right: 51px;">
                                <input type="text" id="src" class="form-control" placeholder="Search..."
                                    required="" name="src" autocomplete="off" value="{{ $src ?? '' }}">
                                <select class="form-select" name="term" id="term">
                                    <option value="first_name">{{ __('Search By First Name') }}</option>
                                    <option value="last_name">{{ __('Search By Last Name') }}</option>
                                    <option value="email">{{ __('Search By User Mail') }}</option>
                                </select>
                                <button class="btn btn-outline-primary waves-effect" type="submit">
                                    <i class="ph-magnifying-glass-bold"></i>
                                </button>
    
                            </div>
                            {{-- </form> --}}
                    </div>
                </div>
            </div>
            <!-- Start: mobile view -->
            <div class="row gy-2 mobile_view">
                @foreach ($users as $index => $row)
                    <div class="col-12">
                        <div class="card" style="margin-bottom: 0rem !important;">
                            <div class="position-relative rounded p-2">
                                <div class="dropdown dropstart btn-pinned">
                                    <a class="btn btn-icon rounded-circle hide-arrow dropdown-toggle p-0"
                                        href="javascript:void(0)" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i data-feather="more-vertical" class="font-medium-4"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="{{ route('seller.users.edit', $row->id) }}">
                                                <i data-feather="edit-2" class="me-50"></i><span>{{ __('Edit') }}</span>
                                            </a>
                                        </li>

                                    </ul>

                                </div>
                                <div class="d-flex align-items-center flex-wrap">
                                    {{-- <div class="form-check form-check-inline mb-1"> --}}
                                    {{-- <input name="ids[]" class="form-check-input" type="checkbox"
                                                id="customCheck{{ $row->id }}" value="{{ $row->id }}" /> --}}

                                    {{-- </div> --}}
                                    <h6 class="align-items-center fw-bolder" style="max-width: -webkit-fill-available !important;">
                                        <span class=""> {{ $row->first_name . ' ' . $row->last_name }}
                                        </span>
                                    </h6>

                                </div>
                                {{-- <h6 class="d-flex align-items-center fw-bolder">
                                        <span class="me-50"><a
                                                href="mailto:{{ $row->email }}">{{ $row->email }}</a></span>
                                    </h6> --}}
                                {{-- <h6 class="d-flex align-items-center fw-bolder">
                                        <span
                                            class="me-50">{{ $row->shop_user && $row->shop_user->role ? $row->shop_user->role->name : '' }}</span>

                                    </h6> --}}
                                <h6 class="d-flex align-items-center fw-bolder">
                                    @if ($row->status == 1)
                                        <span class="badge badge-light-primary mb-1">{{ __('Active') }}</span>
                                    @elseif($row->status == 0)
                                        <span class="badge badge-light-danger mb-1">{{ __('Trash') }}</span>
                                    @elseif($row->status == 2)
                                        <span class="badge badge-light-warning mb-1">{{ __('Suspended') }}</span>
                                    @elseif($row->status == 3)
                                        <span class="badge badge-light-info mb-1">{{ __('Pending') }}</span>
                                    @endif
                                </h6>
                                <span>Created at {{ $row->created_at->format('d-F-Y') ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- End: mobile view -->
            <!-- Start: desktop view -->
            <div class="card mobile_view desktop_view">
                <div class="card-body card-company-table">
                    <table class="datatables-basic table mb-1" id="pagenate">
                        <thead>
                            <tr>
                                <th class="am-title">{{ __('#') }}</th>
                                <th class="am-title">{{ __('Name') }}</th>
                                <th class="am-title">{{ __('Email') }}</th>
                                <th class="am-date">{{ __('Roles') }}</th>
                                <th class="am-date">{{ __('Status') }}</th>
                                <th class="am-date">{{ __('Join at') }}</th>
                                <th class="am-date">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                                // $firstRowOnThisPage = $users->currentPage() * $users->perPage() - $users->perPage();
                            @endphp
                            @foreach ($users as $index => $row)
                                <tr id="row{{ $row->id }}">
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $row->first_name . ' ' . $row->last_name }}</td>
                                    <td><a href="mailto:{{ $row->email }}">{{ $row->email }}</a></td>
                                    <td>{{ $row->shop_user && $row->shop_user->role ? $row->shop_user->role->name : '' }}
                                    </td>
                                    <td>
                                        @if ($row->status == 1)
                                            <span
                                                class="badge rounded-pill badge-light-success">{{ __('Active') }}</span>
                                        @elseif($row->status == 0)
                                            <span
                                                class="badge rounded-pill badge-light-danger">{{ __('Trash') }}</span>
                                        @elseif($row->status == 2)
                                            <span
                                                class="badge rounded-pill badge-light-warning">{{ __('Suspended') }}</span>
                                        @elseif($row->status == 3)
                                            <span
                                                class="badge rounded-pill badge-light-info">{{ __('Pending') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $row->created_at->format('d-F-Y') }}</td>

                                    <td>
                                        <div class="btn-group">
                                            <button type="button"
                                                class="btn btn-primary waves-effect waves-float waves-light">{{ __('Action') }}</button>
                                            <button type="button"
                                                class="btn btn-primary dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item"
                                                    href="{{ route('seller.users.edit', $row->id) }}"><i
                                                        data-feather='edit'></i> {{ __('Edit') }}</a>

                                            </div>
                                        </div>
                                    </td>


                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

                <!-- End: desktop view -->
            </form>

            <div class="d-flex flex-row justify-content-end mt-2">
                {{ $users->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- END: Content-->

@endsection
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
@endsection
