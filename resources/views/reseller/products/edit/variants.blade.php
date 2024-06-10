@extends('layouts.seller')
@section('title', 'Edit Variants')
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
                                <li class="breadcrumb-item"><a href="{{ url('/seller/product') }}">{{ __('Products') }}</a>
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
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-3">
                            @include('reseller.products.edit.tab')
                        </div>
                        <div class="col-sm-9">
                            <div class="card-body">
                                @if (current_shop_type() != 'reseller')
                                    <button class="btn btn-primary float-right mb-2 add_attr" data-bs-toggle="modal"
                                        data-bs-target="#attribute_modal"
                                        style=" float: right; ">{{ __('Create Variant') }}</button>
                                @endif
                                <div id="accordion">
                                    <div class="">
                                        <form action="{{ route('seller.products.variation', $info->id) }}" class="basicform">
                                            @csrf
                                            <table class="table table-hover table-border">
                                                <thead>
                                                    <tr>
                                                        <th class="text-left">{{ __('Attribute') }}</th>
                                                        <th>{{ __('Values') }}</th>
                                                        @if (current_shop_type() != 'reseller')
                                                            <th>{{ __('Trash') }}</th>
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
                                                                    <select data-id="{{ $i }}"
                                                                        class="form-control parent_attr selec{{ $i }}">
                                                                        <option value="" disabled selected>Select
                                                                            Variant</option>
                                                                        @foreach ($posts as $k => $row)
                                                                            <option
                                                                                data-parentattribute="{{ $row->childrenCategories }}"
                                                                                value="{{ $row->id }}"
                                                                                @if ($key == $row->id) selected @endif>
                                                                                {{ $row->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select name="child[{{ $key }}][]" multiple
                                                                        class="form-control select2 multislect child{{ $i }}">
                                                                        @foreach ($posts as $post)
                                                                            @if ($key == $post->id)
                                                                                @foreach ($post->childrenCategories as $item)
                                                                                    <option class="attr{{ $i }}"
                                                                                        value="{{ $item->id }}"
                                                                                        @if (in_array($item->id, $attribute)) selected @endif>
                                                                                        {{ $item->name }}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                @if (current_shop_type() != 'reseller')
                                                                    <td>
                                                                        <a data-id="{{ $i }}"
                                                                            class="btn btn-danger remove_attr text-white"><i
                                                                                class="ph-trash"></i></a>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <th>{{ $key }}</th>
                                                                <td>
                                                                    @foreach ($value as $variation)
                                                                        <span>
                                                                            {{ $variation->variation->name }}
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
                                                    class="btn btn-primary basicbtn mt-1">{{ __('Save Changes') }}</button>
                                            @endif
                                        </form>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="none d-none">
        <div class="attrs_row">
            @foreach ($posts as $post)
                <option value="{{ $post->id }}" data-parentattribute="{{ $post->childrenCategories }}">
                    {{ $post->name }}</option>
            @endforeach
        </div>
    </div>
@endsection
@section('page-script')
    <script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/seller/product/variants.js') }}"></script>
@endsection
