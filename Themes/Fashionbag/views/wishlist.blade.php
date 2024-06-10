@extends('fashionbag::layouts.app')
@section('content')
@php
$wishlist = app('wishlist')->getContent();
@endphp
@section('breadcrumb')
@php
$title = 'Wishlist';
@endphp
<li class="active"> {{__('Wishlist')}} </li>
@endsection
<my-wishlist inline-template>
    <div class="section section-margin">
        <div class="container">

            <div class="row">
                <div class="col-12">
                    <div class="wishlist-table table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="pro-thumbnail">{{ __('Image') }}</th>
                                    <th class="pro-title">{{ __('Product') }}</th>
                                    <th class="pro-price">{{ __('Price') }}</th>
                                    <th class="pro-stock">{{ __('Stock Status') }}</th>
                                    <!-- <th scope="col">Action</th> -->
                                    <th class="pro-remove">{{ __('Remove') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($wishlist) > 0)
                                @foreach($wishlist as $row)
                                @php
                                $product = \App\Models\Product::find($row->id);
                                @endphp
                                <tr>
                                    <td class="pro-thumbnail">
                                        <a href="{{ url('/product/'.$row->name.'/'.$row->id) }}">
                                            <img class="img-fluid" style="max-width: 100px;" src="{{ $row->attributes->image }}" alt="Product">
                                        </a>
                                    </td>
                                    <td class="pro-title"><a href="{{ url('/product/'.$row->name.'/'.$row->id) }}">{{ $row->name }}</a></td>
                                    <td class="pro-price"><span>{{ amount_format($row->price) }}</span></td>
                                    <td class="pro-stock">
                                        @if($product->inStock())
                                        <span class="badge bg-success"> {{ __('In Stock') }} </span>
                                        @else
                                        <span class="badge bg-danger"> {{ __('Out of stock') }} </span>
                                        @endif
                                    </td>
                                    <td class="pro-remove"><a href="javascript::void(0)" @click="remove('{{ $row->id }}')"><i class="pe-7s-trash"></i></a></td>
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
    </div>
    <div class="cart-area bg-gray pt-160 pb-160">
        @if(count($wishlist) > 0)
        <div class="container">
            <form action="#">
                <div class="cart-table-content wishlist-wrap">
                    <div class="table-content table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>{{ __('Product') }}</th>
                                    <th scope="th-text-center">{{ __('Price') }}</th>
                                    <th scope="th-text-center">{{ __('Stock Status') }}</th>
                                    <!-- <th scope="col">Action</th> -->
                                    <th class="th-text-center">{{ __('Remove') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($wishlist) > 0)
                                @foreach($wishlist as $row)
                                @php
                                $product = \App\Models\Product::find($row->id);
                                @endphp
                                <tr>
                                    <td class="cart-product">
                                        <div class="product-img-info-wrap">
                                            <div class="product-img">
                                                <a href="{{ url('/product/'.$row->name.'/'.$row->id) }}">
                                                    <img src="{{ $row->attributes->image }}" alt="">
                                                </a>
                                            </div>
                                            <div class="product-info">
                                                <h4><a href="{{ url('/product/'.$row->name.'/'.$row->id) }}">{{ $row->name }}</a></h4>
                                                {{-- <span>Color :  Black</span>
                                                <span>Size :     SL</span> --}}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="product-price"><span class="amount">{{ amount_format($row->price) }}</span></td>
                                    <td class="product-total">
                                        @if($product->inStock())
                                        <span class="badge bg-success"> {{ __('In Stock') }} </span>
                                        @else
                                        <span class="badge bg-danger"> {{ __('Out of stock') }} </span>
                                        @endif
                                    </td>
                                    <td class="product-wishlist-cart">
                                        <a @click="remove('{{ $row->id }}')">
                                            <i class="fa fa-trash"></i>
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
            </form>
        </div>
        @else
        <div class="container">
            <div class="empty-cart-content text-center">
                <img :src="img" alt="">
                <h3>{{ __('Your wishlist is empty') }}</h3>
                <div class="empty-cart-btn">
                    <a href="{{ url('shop') }}">{{ __('Return To Shop') }}</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</my-wishlist>
@endsection
