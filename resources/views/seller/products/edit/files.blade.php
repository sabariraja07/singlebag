@extends('layouts.seller')
@section('title', 'Edit Files')
@section('page-style')
<style>
        @media screen and (max-width: 900px) {
        #create_mobile_view {
            display: block;
            text-align: center;
        }
    }
</style>
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">{{ __('Files') }}</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ url('/seller/products') }}">{{ __('Products') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Files') }}
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
                            @include('seller.products.edit.tab')
                        </div>
                        <div class="col-sm-9">
                            @if(current_shop_type() != 'reseller')
                                <span id="create_mobile_view">
                                    <a href="javascript:void(0)" data-bs-target="#attribute_modal" data-bs-toggle="modal">
                                        <span class="btn btn-primary mb-1"
                                            >{{ __('Create File') }}</span><br><br>
                                    </a>
                                </span>
                            @endif
                            <p class="text-left text-danger">
                                {{ __('Your customer will automatically receive the download link via email') }}</p>

                            <div class="table-responsive desktop_view">
                                <table class="table table-hover table-nowrap card-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Url') }}</th>
                                            <th width="200">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($info->files as $row)
                                            <tr>
                                                <td>{{ $row->url }}</td>
                                                @if(current_shop_type() != 'reseller')
                                                    <td class="text-right">
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-success edit"
                                                                data-bs-toggle="modal" data-bs-target="#editModal"
                                                                data-id="{{ $row->id }}"
                                                                data-attribute="{{ $row->attribute_id }}"
                                                                data-name="{{ $row->name }}"
                                                                data-url="{{ $row->url }}"><i
                                                                    class="ph-pencil-bold"></i></button>

                                                            <button type="button"
                                                                onclick="make_trash('{{ base64_encode($row->id) }}')"
                                                                class="btn btn-danger"><i class="ph-trash"
                                                                    aria-hidden="true"></i></button>
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row gy-2 mobile_view">
        <h2>{{ __('Url') }}</h2>
        @foreach ($info->files as $row)
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
                                        <a class="dropdown-item d-flex align-items-center edit"
                                        data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="{{ $row->id }}"
                                        data-attribute="{{ $row->attribute_id }}"
                                        data-name="{{ $row->name }}"
                                        data-url="{{ $row->url }}">
                                            <i data-feather="edit-2"
                                                class="me-50"></i><span>{{ __('Edit') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center"
                                        onclick="make_trash('{{ base64_encode($row->id) }}')">
                                            <i data-feather="trash"
                                                class="me-50"></i><span>{{ __('Delete') }}</span>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                            <div class="d-flex">
                                <div class="blog-info" style="max-width: 271px !important;">
                                    <h6 class="blog-recent-post-title">
                                        <span class="text-body-heading">{{ $row->url ?? '' }}</span>
                                    </h6>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>

            </div>
        @endforeach
    </div>
    <!-- Modal -->
    <form action="{{ route('seller.file.store') }}" class="basicform">
        @csrf
        <div class="modal fade" id="attribute_modal" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{ __('Add New File') }}</h5>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="term" value="{{ $info->id }}">

                        <div class="form-group">
                            <label>{{ __('Url') }}</label>
                            <input type="text" name="url" class="form-control" required="">
                        </div>

                    </div>
                    <div class="modal-footer">

                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                            {{ __('Close') }} 
                        </button>
                        <button type="submit" class="btn btn-primary basicbtn">{{ __('Save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
	
    <form method="post" action="{{ route('seller.files.update') }}" class="basicform">
        @csrf
        <div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Edit</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="term" value="{{ $info->id }}">

                        <div class="form-group">
                            <label>{{ __('Url') }}</label>
                            <input type="text" name="url" class="form-control" required="" id="url">
                        </div>

                    </div>
                    <div class="modal-footer">

                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            aria-label="Close">
                            {{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary basicbtn">{{ __('Save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="{{ route('seller.files.destroy') }}" id="basicform">
        @csrf
        <input type="hidden" name="a_id" id="m_id">
    </form>
@endsection
@section('page-script')
    <script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/seller/product/files.js') }}"></script>
@endsection
