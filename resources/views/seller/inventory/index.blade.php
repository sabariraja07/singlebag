@extends('layouts.seller')
@section('title', 'Inventory')
@section('content')
    <!-- BEGIN: Content-->

    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{ __('Inventory') }}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ __('All Inventory') }}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            @if (current_shop_type() != 'reseller')
            <div>
                <a href="{{ route('seller.inventory.index') }}"
                    class="mr-2 mb-2 btn btn-outline-primary @if ($status == '') active @endif">{{ __('Total') }}
                    ({{ $total }})</a>
                <a href="{{ route('seller.inventory.index', 'status=in') }}"
                    class="mr-2 mb-2 btn btn-outline-success @if ($status == 'in') active @endif">{{ __('In Stock') }}({{ $in_stock }})</a>
                <a href="{{ route('seller.inventory.index', 'status=out') }}"
                    class="mr-2 mb-2 btn btn-outline-danger @if ($status == 'out') active @endif">{{ __('Stock Out') }}
                    ({{ $out_stock }})</a>
            </div>
            @endif
            @if (current_shop_type() != 'reseller')
            <div class="card-body">
                <div class="d-flex flex-row justify-content-end">
                    <form class="card-header-form">
                        <div class="input-group">
                            <input type="text" id="src" class="form-control " placeholder="#ABC-123" required=""
                                name="src" autocomplete="off" value="{{ $src ?? '' }}" />
                            <button class="btn btn-outline-primary waves-effect" type="submit">
                                <i class="ph-magnifying-glass-bold"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
            <div class="card">
                <div class="card-body mb-1">
                    @php
                        $url = my_url();
                    @endphp
                    <table class="table" id="pagenate">
                        <thead>
                            <tr>
                                <th>{{ __('Product') }}</th>
                                <th>{{ __('SKU') }}</th>
                                <th>{{ __('STOCK MANAGE') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('QUANTITY AVAILABLE') }}</th>
                                @if (current_shop_type() != 'reseller')
                                    <th>{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventories as $row)
                                <tr id="row{{ $row->id }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-50">
                                                <a href="{{ route('seller.products.edit', $row->product_id) }}">
                                                    <img class="avatar"
                                                        src="{{ asset($row->product->image ?? 'uploads/default.png') }}"
                                                        alt="{{ Str::limit($row->product->title, 5) ?? '' }}"
                                                        width="38" height="38" />
                                                </a>
                                            </div>
                                            <div class="more-info">
                                                <h6 class="mb-0">{{ Str::limit($row->product->title, 20) }}</h6>
                                                <p class="mb-0">#{{ $row->product_id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $row->sku }}
                                    </td>
                                    <td>
                                        @if ($row->stock->mange_stock == 1)
                                            <span class="badge badge-light-success">{{ __('Yes') }}</span>
                                        @elseif($row->stock->mange_stock == 0)
                                            <span class="badge badge-light-danger">{{ __('No') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $row->stock->quantity }}
                                    </td>
                                    <td>
                                        @if ($row->stock->mange_stock == 0)
                                            <a href="#" data-id="{{ base64_encode($row->stock->id) }}"
                                                data-qty="{{ $row->stock->quantity }}" data-stock="{{ $row->stock->status }}"
                                                class="btn btn-icon btn-primary waves-effect waves-float waves-light edit-inventory">
                                                <i class="ph-pencil-bold"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            </form>

            <div class="d-flex flex-row justify-content-end mt-2">
                {{ $inventories->appends(['status' => $status])->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- END: Content-->

    <div class="modal fade" id="editModal" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{ __('Edit') }}</h1>
                        <p></p>
                    </div>
                    <form class="basicform" method="post" action="">
                        @csrf
                        @method('PUT')
                        <div class="col-12 mb-1">
                            <label class="form-label" for="modalEditManageStock">{{ __('Stock Status') }}</label>
                            <select class="form-control" id="stock_status_intput" name="stock_status">
                                <option value="1">{{ __('In Stock') }}</option>
                                <option value="0">{{ __('Out Of Stock') }}</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="modalEditQty">{{ __('Quantity') }}</label>
                            <input type="number" name="stock_qty" id="stock_qty_intput" class="form-control"
                                min="0" required value="{{ $row->stock->quantity ?? '' }}"
                                placeholder="{{ __('Quantity') }}">
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit"
                                class="btn btn-primary me-1 waves-effect waves-float waves-light basicbtn">{{ __('Submit') }}</button>
                            <button type="reset" class="btn btn-outline-secondary waves-effect" data-bs-dismiss="modal"
                                aria-label="Close">
                                {{ __('Close') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script>
        $('.edit-inventory').click(function() {
            $('.basicform').attr('action', '{{ url('seller/inventory') }}/' + $(this).data("id"));
            $('#stock_qty_intput').val($(this).data("qty"));
            $('#stock_status_intput').val($(this).data("stock"));
            $('#editModal').modal('show');
        });
    </script>
@endsection
