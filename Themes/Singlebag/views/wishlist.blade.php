@extends('singlebag::layouts.app')
@section('content')
@php
$wishlist = app('wishlist')->getContent();
@endphp
@section('breadcrumb')
  <span></span> {{__('Wishlist')}}
@endsection
<my-wishlist inline-template>
    <div class="container mb-30 mt-50">
        <div class="row">
            <div class="col-xl-10 col-lg-12 m-auto">
                <div class="mb-50">
                    <h1 class="heading-2 mb-10">{{ __('Your Wishlist') }}</h1>
                    <h6 class="text-body">{!! __('There are :total products in this list' , ['total' => '<span class="text-brand">' . count($wishlist) . '</span>']) !!} </h6>
                </div>
                <div class="table-responsive shopping-summery">
                    <table class="table table-wishlist">
                        <thead>
                            <tr class="main-heading">
                                <th class="custome-checkbox start pl-30">
                                    <!-- <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox11" value="" />
                                    <label class="form-check-label" for="exampleCheckbox11"></label> -->
                                </th>
                                <th scope="col" colspan="2">{{ __('Product') }}</th>
                                <th scope="col">{{ __('Price') }}</th>
                                <th scope="col">{{ __('Stock Status') }}</th>
                                <!-- <th scope="col">Action</th> -->
                                <th scope="col" class="end">{{ __('Remove') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($wishlist) > 0)
                            @foreach($wishlist as $row)
                            @php
                            $product = \App\Models\Product::find($row->id);
                            @endphp
                            <tr class="pt-30" style="border-bottom: 2px solid #edecec;">
                                <td class="custome-checkbox pl-30">
                                    <!-- <input class="form-check-input" type="checkbox" name="checkbox" id="exampleCheckbox1" value="" />
                                    <label class="form-check-label" for="exampleCheckbox1"></label> -->
                                </td>
                                <td class="image product-thumbnail pt-40">
                                    <a href="{{ url('/product/'.$row->name.'/'.$row->id) }}">
                                        <img src="{{ $row->attributes->image }}" alt="#" height="100" />
                                    </a>
                                </td>
                                <td class="product-des product-name">
                                    <h6><a class="product-name mb-10"
                                            href="{{ url('/product/'.$row->name.'/'.$row->id) }}">{{ $row->name }}</a>
                                    </h6>
                                    @foreach ($row->attributes->options as $op)
                                    <small>{{ $op->name }}</small>,
                                    @endforeach
                                </td>
                                <td class="price" data-title="Price">
                                    <h3 class="text-brand">{{ amount_format($row->price) }}</h3>
                                </td>
                                <td class="text-center detail-info" data-title="Stock">
                                    @if($product->inStock())
                                    <span class="stock-status in-stock mb-0"> {{ __('In Stock') }} </span>
                                    @else
                                    <span class="stock-status out-stock"> {{ __('Out of stock') }} </span>
                                    @endif
                                </td>
                                <!-- <td class="text-right" data-title="Cart">
                                            <button class="btn btn-sm">Add to cart</button>
                                        </td> -->
                                <td class="action text-center" data-title="Remove">
                                    <a @click="remove({{ $row->id }})" class="text-body">
                                        <i class="fi-rs-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="6" style="text-align: center;padding:30px;"><h6 style="color:red;">{{ __('No Product found in your wishlist') }}</h6></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</my-wishlist>
@endsection
