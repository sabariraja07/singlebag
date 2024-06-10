@extends('layouts.seller')
@section('title', 'Edit Variants')
@section('page-style')
    <style>
        @media screen and (max-width: 900px) {
            .add_attr {
                display: none !important;
            }

            .add_attr_mobile_view {
                display: block !important;
            }
        }    
        @media screen and (min-width: 992px) {
            .add_attr {
                display: block !important;
            }

            .add_attr_mobile_view {
                display: none !important;
            }

        }  
        @media screen and (max-width: 900px) {
            .table_head {
                letter-spacing: 7.5px !important;
            }
            .table_space {
            margin-left: -41px !important;
            }
            /* .new-option{
                float: left;
                /* padding: 62px; */
            /* } */ */
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
                        <h2 class="content-header-title float-start mb-0">{{ __('Variants') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ url('/seller/products') }}">{{ __('Products') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('Variants') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-lg-12">

            <div class="card"> --}}
                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-3">
                            @include('seller.products.edit.tab')
                        </div>
                        <div class="col-sm-9 save_mobile_view">
                            <div class="card-body">
                                @if (current_shop_type() != 'reseller')
                                    <button class="btn btn-primary float-right mb-2 add_attr new-option d-none" data-bs-toggle="modal"
                                        data-bs-target="#attribute_modal"
                                     >{{ __('Create Variant') }}</button>
                                     <button class="btn btn-primary float-right mb-2 add_attr_mobile_view new-option d-none" data-bs-toggle="modal"
                                     data-bs-target="#attribute_modal" style=" margin: auto; "
                                  >{{ __('Create Variant') }}</button>
                                @endif
                                <div id="accordion">
                                    <div class="desktop_view">
                                        <form action="{{ route('seller.products.variation', $info->id) }}" class="basicform">
                                            @csrf
                                            <table class="table table-bordered table-striped table_space">
                                                <thead>
                                                    <tr>
                                                        <th class="table_head">{{ __('Attributes') }}</th>
                                                        <th class="table_head">&nbsp;{{ __('VALUES') }}</th>
                                                        @if (current_shop_type() != 'reseller')
                                                            <th class="table_head">&nbsp;{{ __('Trash') }}</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody id="data-body">
                                                    @php
                                                        $i = 1;
                                                    @endphp
                                                    @foreach ($variations as $key => $value)
                                                        @php
                                                            $i++;
                                                        @endphp
                                                        @if (current_shop_type() != 'reseller')
                                                            <tr class="attr_{{ $i }}">
                                                                <td>
                                                                    <select data-id="{{ $i }}" class="form-control parent_attr selec{{ $i }}" required>
                                                                        <option value="" disabled selected>
                                                                            Select Variant
                                                                        </option>
                                                                        @foreach ($attributes as $k => $row)
                                                                            <option
                                                                                data-parentattribute="{{ $row->options }}"
                                                                                value="{{ $row->id }}"
                                                                                @if ($value->id == $row->id) selected @endif>
                                                                                {{ $row->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select name="child[{{ $value->id }}][]" multiple
                                                                        class="form-control select2 multislect child{{ $i }}" required>
                                                                        @foreach ($attributes as $post)
                                                                            @if ($value->id == $post->id)
                                                                                @foreach ($post->options as $item)
                                                                                    <option class="attr{{ $i }}"
                                                                                        value="{{ $item->id }}"
                                                                                        @if (in_array($item->id, $options)) selected @endif>
                                                                                        {{ $item->name }}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <a data-id="{{ $i }}"
                                                                        class="btn btn-danger remove_attr text-white"><i
                                                                            class="ph-trash"></i></a>
                                                                </td>
                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <th>{{ $value->name }}</th>
                                                                <td>
                                                                    @foreach ($value->options as $option)
                                                                        <span>
                                                                            {{ $option->name }}
                                                                            @if (!$loop->last)
                                                                                ,
                                                                            @endif
                                                                        </span>
                                                                    @endforeach
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach


                                                </tbody>
                                            </table>
                                            @if (current_shop_type() != 'reseller')
                                                <button
                                                    class="btn btn-primary basicbtn mt-1 save_mobile_view">{{ __('Save Changes') }}</button>
                                            @endif
                                        </form>
                                    </div>
                                    <div class="mobile_view">
                                        <form action="{{ route('seller.products.variation', $info->id) }}" class="basicform">
                                            @csrf
                                                <div id="data-body-mobile-view">
                                                    @php
                                                        $i = 1;
                                                    @endphp
                                                    @foreach ($variations as $key => $value)
                                                        @php
                                                            $i++;
                                                        @endphp
                                                        @if (current_shop_type() != 'reseller')
                                                        <div class="col-12">
                                                            <div class="card">
                                                                {{-- <div class="card-body" style="padding: 0.5rem !important;"> --}}
                                                                    {{-- <div class="rounded p-25"> --}}
                                                                        <div style="padding-left: 0.5rem !important; padding-right: 0.5rem !important;">
                                                                            <div class="attr_{{ $i }}">
                                                                                <div class="mb-1">
                                                                                    <br><b><label class="mb-1">Attributes</label></b>
                                                                                    <select data-id="{{ $i }}" class="form-control parent_attr selec{{ $i }}" required>
                                                                                        <option value="" disabled selected>
                                                                                            Select Variant
                                                                                        </option>
                                                                                        @foreach ($attributes as $k => $row)
                                                                                            <option
                                                                                                data-parentattribute="{{ $row->options }}"
                                                                                                value="{{ $row->id }}"
                                                                                                @if ($value->id == $row->id) selected @endif>
                                                                                                {{ $row->name }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="mb-1">
                                                                                    <b><label class="mb-1">Values</label></b>
                                                                                    <select name="child[{{ $value->id }}][]" multiple
                                                                                        class="form-control select2 multislect child{{ $i }}" required>
                                                                                        @foreach ($attributes as $post)
                                                                                            @if ($value->id == $post->id)
                                                                                                @foreach ($post->options as $item)
                                                                                                    <option class="attr{{ $i }}"
                                                                                                        value="{{ $item->id }}"
                                                                                                        @if (in_array($item->id, $options)) selected @endif>
                                                                                                        {{ $item->name }}</option>
                                                                                                @endforeach
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="mb-1">
                                                                                    <a data-id="{{ $i }}"
                                                                                        class="btn btn-danger remove_attr text-white"><i
                                                                                            class="ph-trash"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    {{-- </div> --}}
                                                                {{-- </div> --}}
                                                            </div>
                                                        </div>
                                                        @else
                                                            <tr>
                                                                <th>{{ $value->name }}</th>
                                                                <td>
                                                                    @foreach ($value->options as $option)
                                                                        <span>
                                                                            {{ $option->name }}
                                                                            @if (!$loop->last)
                                                                                ,
                                                                            @endif
                                                                        </span>
                                                                    @endforeach
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach


                                                </div>
                                            
                                            @if (current_shop_type() != 'reseller')
                                                <button
                                                    class="btn btn-primary basicbtn mt-1 save_mobile_view">{{ __('Save Changes') }}</button>
                                            @endif
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            {{-- </div>

        </div>

    </div> --}}

    <div class="none d-none">
        <div class="attrs_row">
            @foreach ($attributes as $post)
                <option value="{{ $post->id }}" data-parentattribute="{{ $post->options }}">
                    {{ $post->name }}</option>
            @endforeach
        </div>
    </div>
@endsection
@section('page-script')
    <script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/seller/product/variants.js') }}"></script>
@endsection
