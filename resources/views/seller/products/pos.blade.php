@extends('layouts.seller')
@section('title', 'Point Of Sale Products')
@php
    $url = domain_info('full_domain');
    $i = 1;
@endphp
@section('content')
    <!-- BEGIN: Content-->
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">

            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('POS Products') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item">{{ __('Point Of Sales') }}
                                </li>
                                <li class="breadcrumb-item active">{{ __('POS Products') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="card-body">
                <div class="d-flex flex-row justify-content-between mt-1">
                    <div>
                    </div>
                    <div style="margin-top: -45px !important;">
                        <form>

                            <div class="input-group" style="margin-right: 51px;">
                                <input type="text" id="src" class="form-control" placeholder="Search..."
                                    required="" name="src" autocomplete="off" value="{{ $src ?? '' }}">
                                <select class="form-select" name="type" id="type">
                                    <option value="title">{{ __('Search By Name') }}</option>
                                </select>
                                <button class="btn btn-outline-primary waves-effect" type="submit">
                                    <i class="ph-magnifying-glass-bold"></i>
                                </button>

                            </div>
                            {{-- </form> --}}
                    </div>
                </div>
            </div>
            <div class="row gy-2 mobile_view">
                @foreach ($posts as $row)
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body" style="padding: 0.5rem !important;">
                                <div class="position-relative rounded p-25">
                                    <div class="d-flex">
                                        <a href="javascript:void(0)" class="me-2">
                                            <img class="rounded img"
                                                src="{{ asset($row->image ?? 'uploads/default.png') }}"
                                                alt="" />
                                        </a>
                                        <div class="blog-info">
                                            <h6 class="blog-recent-post-title">
                                                <span class="text-body-heading">{{ $row->title }}
                                                    (#{{ $row->id }})
                                                </span>

                                            </h6>
                                            <div class="text-muted mb-0" style="margin-bottom: 7px !important;">
                                                @if ($row->pos == 1)
                                                    <span
                                                        class="badge badge-light-primary mb-1">{{ __('Active') }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card mobile_view desktop_view">
                <div class="card-body card-company-table">
                    <table class="datatables-basic table mb-1" id="pagenate">
                        <thead>
                            <tr>
                                <th class="am-title">{{ __('#') }}</th>
                                <th class="am-title"><i data-feather='image'></i></th>
                                <th class="am-title">{{ __('Name') }}</th>
                                <th class="am-title">{{ __('POS STATUS') }}</th>
                                <th class="am-date">{{ __('Status') }}</th>
                                <th class="am-date">{{ __('LAST UPDATE') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $row)
                                <tr id="row{{ $row->id }}">
                                    <td>{{ $i++ }}</td>
                                    <td><img src="{{ asset($row->image ?? 'uploads/default.png') }}" height="50"
                                            alt=""></td>
                                    <td>{{ $row->title }} (#{{ $row->id }})

                                        {{-- <div>
                                <a href="{{ route('seller.products.edit', $row->id) }}">{{ __('Edit') }}</a>

                            </div> --}}

                                    </td>
                                    <td>
                                        @if ($row->pos == 1)
                                            <span
                                                class="badge rounded-pill badge-light-success">{{ __('Active') }}</span>
                                        @endif
                                    </td>


                                    <td>
                                        @if ($row->status == 1)
                                            <span
                                                class="badge rounded-pill badge-light-success">{{ __('Active') }}</span>
                                        @elseif($row->status == 2)
                                            <span
                                                class="badge rounded-pill badge-light-info">{{ __('Draft') }}</span>
                                        @elseif($row->status == 3)
                                            <span
                                                class="badge rounded-pill badge-light-warning">{{ __('Incomplete') }}</span>
                                        @elseif($row->status == 0)
                                            <span
                                                class="badge rounded-pill badge-light-danger">{{ __('Trash') }}</span>
                                        @endif

                                    </td>
                                    <td>{{ $row->updated_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>


                    </table>

                </div>
            </div>
            </form>

            <div class="d-flex flex-row justify-content-end mt-2">
                {{ $posts->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- END: Content-->



@endsection
