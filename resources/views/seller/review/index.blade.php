@extends('layouts.seller')
@section('title', 'Review And Ratings')

@section('content')

    <!-- BEGIN: Content-->

    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Review & Ratings') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('All Review And Ratings') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
                @if (current_shop_type() != 'reseller')
                    <div class="card-body desktop_view">
                        <div class="d-flex flex-row justify-content-between"
                            style="margin-left: -19px !important;margin-top: -2rem !important;">
                            <div>
                                <form method="post" action="{{ route('seller.reviews.destroy') }}"
                                    class="basicform_with_reload">
                                    @csrf
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

                        </div>
                    </div>
                @endif

                <div class="row gy-2 mobile_view">
                    @php
                        $url = my_url();
                    @endphp
                    @if (current_shop_type() == 'supplier')
                        @foreach ($posts as $post)
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title d-flex align-items-center">
                                            <div class="avatar me-50">
                                                <a href="javascript:void(0)">
                                                    <img class="avatar" src="admin/images/man-2-circle.svg"
                                                        alt="{{ $post->name ?? '' }}" width="38" height="38" />
                                                </a>
                                            </div>
                                            <div class="more-info">
                                                <h6 class="mb-0">{{ $post->name }}</h6>
                                                <p class="mb-0">#{{ $post->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="position-relative rounded p-2">
                                        <div class="d-flex align-items-center flex-wrap">
                                            <h4 class="mb-1 me-1">{{ $post->supplier_list[0]->name ?? '' }}</h4>
                                            @if (current_shop_type() == 'seller')
                                                @if ($post->supplier_list[0]->status == 1)
                                                    <span class="badge badge-light-primary mb-1">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge badge-light-danger mb-1">{{ __('Inactive') }}</span>
                                                @endif
                                                </td>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    @else
                        @foreach ($posts as $post)
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-50">
                                                <a href="javascript:void(0)">
                                                    <img class="avatar" src="admin/images/man-2-circle.svg"
                                                        alt="{{ $post->name ?? '' }}" width="38" height="38" />
                                                </a>
                                            </div>
                                            <div class="more-info">
                                                <h6 class="mb-0">{{ $post->name }}</h6>
                                                <p class="mb-0">{{ $post->email }}</p>
                                            </div>
                                        </div>

                                        <div class="dropdown chart-dropdown card-dropdown">
                                            <i data-feather="more-vertical" class="font-medium-3 cursor-pointer" data-bs-toggle="dropdown"></i>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item d-flex align-items-center"
                                                    href="{{ route('seller.review.edit', $post->id) }}">
                                                    <i data-feather="edit-2" class="me-50"></i><span>{{ __('Edit') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-25">
                                            <div class="d-flex flex-row justify-content-between">
                                                @if ($post->status == 1)
                                                    <span class="badge badge-light-primary mb-1">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge badge-light-danger mb-1">{{ __('Inactive') }}</span>
                                                @endif
                                                <div class="d-flex flex-row">
                                                    <i class="ph-fill ph-star font-medium-3 me-25 text-warning"></i>
                                                    {{ $post->rating ?? '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <span>Created on {{ $post->created_at->format('d-F-Y') ?? '' }}</span>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="card mobile_view desktop_view">
                    <div class="card-body">
                        <div class="table-responsive mb-1">
                            @php
                                $url = my_url();
                            @endphp
                            <table class="table" id="pagenate">
                                <thead>
                                    <tr>
                                        <!-- <th class="am-select">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input type="checkbox" class="custom-control-input checkAll" id="selectAll">
                                                                                <label class="custom-control-label checkAll" for="selectAll"></label>
                                                                            </div>
                                                                        </th> -->
                                        @if (current_shop_type() != 'reseller')
                                            <th><input type="checkbox" class="checkAll"></th>
                                        @endif
                                        <th class="text-left">{{ __('RATINGS') }}</th>
                                        <th class="text-left">{{ __('COMMENT') }}</th>
                                        <th>{{ __('Product') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        @if (current_shop_type() != 'reseller')
                                            <th>{{ __('Status') }}</th>
                                        @endif
                                        <th>{{ __('CREATED AT') }}</th>
                                        @if (current_shop_type() != 'reseller')
                                            <th>{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($posts as $post)
                                        <tr>
                                            <!-- <td>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" name="ids[]" class="custom-control-input" id="customCheck{{ $post->id }}" value="{{ $post->id }}">
                                                                        <label class="custom-control-label" for="customCheck{{ $post->id }}"></label>
                                                                    </div>
                                                                </td> -->
                                            <td><input type="checkbox" name="ids[]" value="{{ $post->id }}">
                                            </td>
                                            <td>{{ $post->rating ?? '' }}</td>
                                            <td>{{ $post->comment ?? '' }}</td>
                                            <td>
                                                @if ($post->product->status == 1 && current_shop_type() == 'seller')
                                                    <a href="{{ url('/product/' . $post->product->slug . '/' . $post->product->id) }}"
                                                        target="_blank">{{ Str::limit($post->product->title, 10) }}</a>
                                                @else
                                                    <a
                                                        href="{{ route('seller.products.edit', $post->product->id) }}">{{ Str::limit($post->product->title, 10) }}</a>
                                                @endif

                                            </td>
                                            <td>{{ $post->name ?? '' }}</td>
                                            <td>{{ $post->email ?? '' }}</td>
                                            @if (current_shop_type() != 'reseller')
                                                <td>
                                                    @if ($post->status == 1)
                                                        <span
                                                            class="badge badge-light-success">{{ __('Active') }}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-light-danger">{{ __('Inactive') }}</span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td>{{ $post->created_at->format('d-F-Y') ?? '' }}</td>
                                            @if (current_shop_type() != 'reseller')
                                                <td>
                                                    <a href="{{ route('seller.review.edit', $post->id) }}"
                                                        class="btn btn-icon btn-primary waves-effect waves-float waves-light">
                                                        <i class="ph-pencil-bold"></i>
                                                    </a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                @if (current_shop_type() == 'seller')
                    </form>
                @endif

                <div class="d-flex flex-row justify-content-end mt-2">
                    {{ $posts->links('vendor.pagination.bootstrap-4') }}
                </div>

        </div>
    </div>


@endsection

@section('page-script')
    <script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
    <script>
        function loadmessage() {
            alert('Reviews can not be shown for unpublished product');
        }
    </script>
@endsection
