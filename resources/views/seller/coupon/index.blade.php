@extends('layouts.seller')
@section('title', 'Coupons')
@section('content')
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Coupons') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('All Coupons') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <form method="post" action="{{ route('seller.coupons.destroy') }}"
            class="basicform_with_reload">
                @csrf
                <div class="d-flex flex-row justify-content-between mt-1">
                    <div class="desktop_view">
                        <div class="input-group">
                            <select class="form-control" name="method">
                                <option disabled selected="">{{ __('Select Action') }}</option>
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Inactive') }}</option>
                            </select>
                            <button class="btn btn-primary waves-effect basicbtn"
                                type="submit">{{ __('Submit') }}</button>
                        </div>
                    </div>
                    <div class="mobile_view"></div>
                    <a href="{{ route('seller.coupon.create') }}">
                        <span class="btn btn-primary mb-1">+{{ __('create') }}</span>
                    </a>
                </div>
                <div class="row gy-2 mobile_view">
                    @foreach ($posts as $row)
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title d-flex align-items-center">
                                        {{-- <div class="avatar me-50">
                                            <a href="javascript:void(0)">
                                                <img class="avatar" src="{{ $row->image }}"
                                                    alt="{{ $row->title ?? '' }}" width="38" height="38" />
                                            </a>
                                        </div> --}}
                                        <div class="more-info">
                                            <h6 class="mb-50">{{ $row->title ?? '' }}</h6>
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
                                                href="{{ route('seller.coupon.edit', $row->id) }}">
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
                            <table class="table" id="pagenate">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="checkAll"></th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('CODE') }}</th>
                                        <th>{{ __('EXPIRED DATE') }}</th>
                                        <th>{{ __('PERCENTAGE') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('LAST UPDATE') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($posts as $row)
                                        <tr id="row{{ $row->id }}">
                                            <td><input type="checkbox" name="ids[]" value="{{ $row->id }}"></td>
                                            <td>{{ $row->title }}</td>
                                            <td>{{ $row->code }}</td>
                                            <td>{{ $row->expiry_date }}</td>
                                            <td>{{ $row->amount }}%</td>
                                            <td>
                                                @if ($row->status == 1)
                                                    <span class="badge badge-light-success">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge badge-light-danger">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $row->updated_at->diffforHumans() }}</td>
                                            <td>
                                                <a href="{{ route('seller.coupon.edit', $row->id) }}"
                                                    class="btn btn-icon btn-primary waves-effect waves-float waves-light">
                                                    <i class="ph-pencil-bold"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <span>{{ __('Note') }}: <b
                            class="text-danger">{{ __('For Better Performance Remove Unusual Coupons') }}</b></span>
                </div>
            </form>

            <div class="d-flex flex-row justify-content-end mt-2">
                {{ $posts->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- END: Content-->

@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
@endsection
