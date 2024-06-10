@extends('layouts.seller')
@section('title', 'Edit Options')
@section('page-style')
    <style>
        .select2-container--classic .select2-selection--single .select2-selection__arrow b,
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            padding-right: 0px !important;
        }
        .table_head{
                text-align: center !important;
        }
        .detail-amt {
            float: right !important;
            margin-top: -17px !important;
        }
        @media screen and (max-width: 900px) {
            /* .table_head {
                letter-spacing: 7.5px !important;
            } */
            .table > :not(caption) > * > * {
            padding: 0.72rem 0rem !important;
            }
            .save_mobile_view {
                display: block;
                margin: auto;
                text-align: center;
            }
        }
        @media screen and (min-width: 992px) {
            .new-option{
                float: right;
            }
            
        }
    </style>
@endsection
@section('content')
    <div class="content-header row">
        @if (Session::has('success'))
            <div class="alert alert-success">
                <ul style="margin-top: 1rem;">
                    <li>{{ Session::get('success') }}</li>
                </ul>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin-top: 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Options') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ url('/seller/products') }}">{{ __('Products') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('Options') }}
                                </li>
                            </ol>
                        </div>
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
                        @if (current_shop_type() != 'reseller')
                            <div class="col-sm-9">
                                <form class="basicform" method="post"
                                    action="{{ route('seller.products.option_update', $info->id) }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-md-12 col-lg-12 save_mobile_view">
                                            <a href="javascript:void(0)" data-bs-target="#add_option" data-bs-toggle="modal"
                                                class="new-option">
                                                <span class="btn btn-primary mb-1">{{ __('Add New Option') }}</span>
                                            </a>
                                        </div>
                                        <div class="col-12 col-md-12 col-lg-12 mt-3">
                                            @foreach ($info->options as $key => $row)
                                                <div class="card border">
                                                    <div class="card-header">
                                                        <h4 class="card-title">
                                                            <span
                                                                id="option_name{{ $row->id }}">{{ $row->name }}</span>
                                                            @if ($row->is_required == 1)
                                                                <span class="text-danger">*</span>
                                                            @endif
                                                        </h4>
                                                        <div class="heading-elements">
                                                            <ul class="list-inline mb-0 mt-1">
                                                                <li>
                                                                    <a class="btn btn-outline-success btn-sm text-success row_edit"
                                                                        data-bs-target="#editform" data-bs-toggle="modal"
                                                                        data-target="#editform"
                                                                        data-selecttype="{{ $row->select_type }}"
                                                                        data-name="{{ $row->name }}"
                                                                        data-required="{{ $row->is_required }}"
                                                                        data-id="{{ $row->id }}"><i
                                                                            class="ph-pencil-bold"></i></a>
                                                                </li>
                                                                <li>
                                                                    <a class="btn btn-outline-danger btn-sm text-danger option_delete"
                                                                        data-id="{{ $row->id }}"><i
                                                                            class="ph-trash"></i></a>
                                                                </li>
                                                                <li>
                                                                    <a data-action="collapse" class="">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="14" height="14"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="feather feather-chevron-down">
                                                                            <polyline points="6 9 12 15 18 9"></polyline>
                                                                        </svg>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="card-content collapse show" style="">
                                                        <div class="card-body">
                                                            <div class="mb-1">
                                                                <table class="options table table-bordered table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="table_head">{{ __('LABEL') }}&nbsp;</th>
                                                                            <th class="table_head">{{ __('Price') }}&nbsp;</th>
                                                                            <th class="table_head" style="text-align: center">&nbsp;{{ __('PRICE TYPE') }}</th>
                                                                            <th class="table_head"></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                        @if (count($row->variants) == 0)
                                                                            <tr>
                                                                                <td
                                                                                    class="text-center text-danger"colspan="4">
                                                                                    {{ __('Options not found') }}
                                                                                </td>
                                                                            </tr>
                                                                        @endif

                                                                        @foreach ($row->variants ?? [] as $item)
                                                                            <tr class="option{{ $item->id }}">
                                                                                <td>
                                                                                    <input type="text"
                                                                                        name="options[{{ $row->id }}][values][{{ $item->id }}][label]"
                                                                                        class="form-control"
                                                                                        value="{{ $item->name }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number"
                                                                                        name="options[{{ $row->id }}][values][{{ $item->id }}][price]"
                                                                                        class="form-control"
                                                                                        value="{{ $item->amount }}"
                                                                                        step="any" min="0">
                                                                                </td>
                                                                                <td>
                                                                                    <select
                                                                                        name="options[{{ $row->id }}][values][{{ $item->id }}][price_type]"
                                                                                        class="form-select select2 custom-select-black">
                                                                                        <option value="1"
                                                                                            @if ($item->amount_type == 1) selected="" @endif>
                                                                                            {{ __('Fixed') }}
                                                                                        </option>
                                                                                        <option value="0"
                                                                                            @if ($item->amount_type == 0) selected="" @endif>
                                                                                            {{ __('Percent') }}
                                                                                        </option>
                                                                                    </select>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <button type="button"
                                                                                        class="btn btn-outline-danger btn-sm delete-row bg-white option_delete text-danger"
                                                                                        data-id="{{ $item->id }}">
                                                                                        <i class="ph-trash"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <button type="button" data-bs-toggle="modal"
                                                                data-bs-target="#new_row_modal"
                                                                class="btn btn-success add_new_row save_mobile_view"
                                                                data-id="{{ $row->id }}">
                                                                {{ __('Add New Row') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if (count($info->options) > 0)
                                                <button type="submit"
                                                    class="btn btn-primary basicbtn mt-1 save_mobile_view" >{{ __('Save Changes') }}</button>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="col-sm-9">
                                <form class="basicform" method="post"
                                    action="{{ route('seller.products.option_update', $info->id) }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-md-12 col-lg-12 mt-3">
                                            @foreach ($info->options as $key => $row)
                                                <div class="card border">
                                                    <div class="card-header">
                                                        <h4 class="card-title">
                                                            <span
                                                                id="option_name{{ $row->id }}">{{ $row->name }}</span>
                                                            @if ($row->is_required == 1)
                                                                <span class="text-danger">*</span>
                                                            @endif
                                                        </h4>
                                                        <div class="heading-elements">
                                                            <ul class="list-inline mb-0">
                                                                <li>
                                                                    <a data-action="collapse" class="">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="14" height="14"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="feather feather-chevron-down">
                                                                            <polyline points="6 9 12 15 18 9"></polyline>
                                                                        </svg>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="card-content collapse show" style="">
                                                        <div class="card-body">
                                                            <div class="mb-1">
                                                                <table class="options table table-bordered table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>{{ __('LABEL') }}</th>
                                                                            <th>{{ __('Supplier Price') }}</th>
                                                                            <th>{{ __('Price') }}</th>
                                                                            <th>{{ __('PRICE TYPE') }}</th>
                                                                            {{-- <th>Price Type</th> --}}
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                        @if (count($row->variants) == 0)
                                                                            <tr>
                                                                                <td
                                                                                    class="text-center text-danger"colspan="4">
                                                                                    {{ __('Options not found') }} 
                                                                                </td>
                                                                            </tr>
                                                                        @endif

                                                                        @foreach ($row->variants ?? [] as $item)
                                                                            <tr class="option{{ $item->id }}">

                                                                                <td>{{ $item->name }}</td>
                                                                                <td>
                                                                                    {{ $item->amount }}
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number"
                                                                                        name="options[{{ $row->id }}][values][{{ $item->id }}][price]"
                                                                                        class="form-control"
                                                                                        value="{{ $item->ResellerOption->amount ?? 0 }}"
                                                                                        required
                                                                                        min="{{ $item->amount }}"
                                                                                        step="any" min="0">
                                                                                </td>
                                                                                <td>
                                                                                    @if ($item->amount_type == 1)
                                                                                    {{ __('Fixed') }}
                                                                                    @endif
                                                                                    @if ($item->amount_type == 0)
                                                                                    {{ __('Percent') }}
                                                                                    @endif
                                                                                </td>
                                                                                {{-- <td>
                                                                                    <select
                                                                                        name="options[{{ $row->id }}][values][{{ $item->id }}][price_type]"
                                                                                        class="form-select select2 custom-select-black">
                                                                                        <option value="1"
                                                                                            @if ($item->ResellerOption->amount_type ?? 0 == 1) selected="" @endif>
                                                                                            {{ __('Fixed') }} {{$item->ResellerOption->amount_type ?? 0}}
                                                                                        </option>
                                                                                        <option value="0"
                                                                                            @if ($item->ResellerOption->amount_type ?? 0 == 0) selected="" @endif>
                                                                                            {{ __('Percent') }} {{$item->ResellerOption->amount_type ?? 0}}
                                                                                        </option>
                                                                                    </select>
                                                                                </td> --}}
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if (count($info->options) > 0)
                                                <button type="submit"
                                                    class="btn btn-primary basicbtn mt-1">{{ __('Save Changes') }}</button>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="add_option" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('seller.products.store_group', $info->id) }}" class="basicform_with_reload"
                method="post">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Add New Option') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-1">
                            <label for="name">{{ __('Option Name') }}</label>
                            <input type="text" name="name" required class="form-control">
                        </div>
                        <div class="form-group mb-1 mb-1">
                            <label>{{ __('Select Type') }}</label>

                            <select name="select_type" class="form-control">
                                <option value="1">{{ __('Multiple Select') }}</option>
                                <option selected value="0">{{ __('Single Select') }}</option>
                            </select>
                        </div>
                        <label for="is_required"><input type="checkbox" name="is_required" value="1"
                                id="is_required"> {{ __('Required') }}</label>

                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            aria-label="Close">
                            {{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary basicbtn">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="new_row_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('seller.products.add_row') }}" class="basicform_with_reload" method="post">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Add New Row') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-1">
                            <label for="add_row">{{ __('Row Name') }}</label>
                            <input type="text" id="add_row" class="form-control" name="name" required>
                            <input type="hidden" id="row_id" name="row_id">
                        </div>
                        <div class="form-group mb-1">
                            <label for="price">{{ __('Price') }}</label>
                            <input type="number" step="any" id="price" class="form-control" name="price"
                                required>
                        </div>
                        <div class="form-group mb-1">
                            <label for="price_type">{{ __('Price Type') }}</label>
                            <select name="amount_type" id="price_type" class="form-control">
                                <option value="1">{{ __('Fixed') }}</option>
                                <option value="0">{{ __('Percentage') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            aria-label="Close">
                            {{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary basicbtn">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="editform" tabindex="-1" aria-labelledby="editform" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editform">{{ __('Edit Option') }}</h5>
                    </button>
                </div>
                <form action="{{ route('seller.products.row_update') }}" class="basicform row_update_form">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-1">
                            <label for="name">{{ __('Option Name') }}</label>
                            <input type="text" id="edit_name" name="name" required class="form-control">
                        </div>
                        <div class="form-group mb-1">
                            <label for="name">{{ __('Select Type') }}</label>

                            <select id="edit_select" name="select_type" class="form-control">
                                <option value="1">{{ __('Multiple Select') }}</option>
                                <option value="0">{{ __('Single Select') }}</option>
                            </select>
                        </div>
                        <input type="hidden" id="edit_id" name="id">
                        <label for="edit_required"><input id="edit_required" type="checkbox" name="is_required"
                                value="1"> {{ __('Required') }}</label>

                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">{{ __('Close') }}</button> -->
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            aria-label="Close">
                            {{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary basicbtn">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form class="basicform delete_from" action="{{ route('seller.products.option_delete') }}" method="post">
        @csrf
        <input type="hidden" name="id" id="option_id">
    </form>
@endsection
@section('page-script')
    <script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/product_option.js') }}"></script>
@endsection
