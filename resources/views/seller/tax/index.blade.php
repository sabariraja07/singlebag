@extends('layouts.seller')
@section('title', 'Tax Classes')
@section('content')

    <!-- BEGIN: Content-->
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Tax Classes') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item">{{ __('Tax Classes') }}
                                </li>
                                <li class="breadcrumb-item active">{{ __('All Tax Classes') }}
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
                <div class="card-body">
                    <div style="margin-top: -31px !important;">
                        <form method="post" action="{{ route('seller.tax_classes.destroy') }}"
                            class="basicform_with_reload">
                            @csrf
                            <div class="d-flex flex-row justify-content-between mt-1">
                                <div class="desktop_view">
                                    <div class="input-group">
                                        <select class="form-control" name="method">
                                            <option disabled selected="">{{ __('Select Action') }}</option>
                                            <option value="1">{{ __('Active') }}</option>
                                            <option value="2">{{ __('Inactive') }}</option>
                                            <option value="0">{{ __('Delete') }}</option>
                                        </select>
                                        <button class="btn btn-primary waves-effect basicbtn"
                                            type="submit">{{ __('Submit') }}</button>
                                    </div>
                                </div>
                                <div class="mobile_view"></div>
                                <a href="{{ route('seller.tax_classes.create') }}">
                                    <span class="btn btn-primary mb-1">+{{ __('create') }}</span>
                                </a>
                            </div>
                    </div>
                    <!-- Start: mobile view -->
                    <div class="row gy-2 mobile_view">
                        @foreach ($taxes as $row)
                            <div class="col-12">
                                <div class="card" style="margin-top: 33px;">
                                    <div class="card-body" style="padding: 0.5rem !important;">
                                        <div class="position-relative rounded p-2">
                                            <div class="dropdown dropstart btn-pinned">
                                                <a class="btn btn-icon rounded-circle hide-arrow dropdown-toggle p-0"
                                                    href="javascript:void(0)" id="dropdownMenuButton1"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i data-feather="more-vertical" class="font-medium-4"></i>
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center"
                                                            href="{{ route('seller.tax_classes.edit', $row->id) }}">
                                                            <i data-feather="edit-2"
                                                                class="me-50"></i><span>{{ __('Edit Tax Class') }}</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center"
                                                            href="{{ route('seller.tax_classes.show', $row->id) }}">
                                                            <i data-feather="edit-2"
                                                                class="me-50"></i><span>{{ __('Tax Rate') }}</span>
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>
                                            <div class="d-flex">
                                                <div class="blog-info">
                                                    <h4 class="mb-1 me-1" style="word-break: break-all;margin-right: 10px;">
                                                        {{ $row->name }}
                                                    </h4>
                                                    <div class="text-muted mb-0">
                                                        @if ($row->status == 1)
                                                            <span
                                                                class="badge badge-light-primary mb-1">{{ __('Active') }}</span>
                                                        @elseif($row->status == 2)
                                                            <span
                                                                class="badge badge-light-danger mb-1">{{ __('Inactive') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="d-flex align-items-center flex-wrap">
                                                <h4 class="mb-1 me-1"> {{ $row->fullname }} (#{{ $row->id }})</h4>
                                                <td>
                                                    @if ($row->preview->status == 1)
                                                        <span class="badge badge-light-primary mb-1">Active</span>
                                                    @elseif($row->preview->status == 0)
                                                        <span class="badge badge-light-danger mb-1">Inactive</span>
                                                    @endif
                                                </td>
    
                                            </div>
                                            <h6 class="d-flex align-items-center fw-bolder">
                                                <span class="me-50">{{ $row->email ?? '' }}</span>
                                            </h6>
                                            <span>Registred at {{ $row->updated_at->diffForHumans() ?? '' }}</span> --}}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                    <!-- End: mobile view -->


                    <!-- Start: Desktop view -->
                    <div class="card  mobile_view desktop_view">
                        <div class="card-body card-company-table">
                            <table class="datatables-basic table mb-1" id="pagenate">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="checkAll"></th>
                                        <th class="am-title">{{ __('Name') }}</th>
                                        <th class="am-title">{{ __('Based On') }}</th>
                                        <th class="am-date">{{ __('Status') }}</th>
                                        <th class="am-date">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($taxes as $row)
                                        <tr id="row{{ $row->id }}">
                                            <td><input type="checkbox" name="ids[]" value="{{ $row->id }}"></td>
                                            <td>
                                                <div class="d-flex flex-row">
                                                    <div class="user-info">
                                                        <h5 class="mb-0">{{ $row->name ?? '' }}
                                                        </h5>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="mb-0">{{ $row->based_on ?? '' }} </h5>

                                            </td>
                                            <td>
                                                @if ($row->status == 1)
                                                    <span
                                                        class="badge rounded-pill badge-light-success">{{ __('Active') }}</span>
                                                @elseif($row->status == 2)
                                                    <span
                                                        class="badge rounded-pill badge-light-info">{{ __('Inactive') }}</span>
                                                @endif

                                            </td>


                                            <td>
                                                <a href="{{ route('seller.tax_classes.show', $row->id) }}"
                                                    class="btn btn-icon btn-primary waves-effect waves-float waves-light">
                                                    <i class="ph-gear-bold"></i>
                                                </a>
                                                <a href="{{ route('seller.tax_classes.edit', $row->id) }}"
                                                    class="btn btn-icon btn-success waves-effect waves-float waves-light">
                                                    <i class="ph-pencil-bold"></i>
                                                </a>
                                            </td>

                                        
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <!-- End: Desktop view -->



                </div>
                {{-- </div> --}}
                </form>

                <div class="d-flex flex-row justify-content-end mt-2">
                    {{ $taxes->links('vendor.pagination.bootstrap-4') }}
                </div>
            </section>

        </div>
    </div>


    <!-- END: Content-->

@endsection
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/js/success.js') }}"></script>

@endsection
