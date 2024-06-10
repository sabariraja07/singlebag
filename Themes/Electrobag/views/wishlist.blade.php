@extends('electrobag::layouts.app')
@php
$wishlist = app('wishlist')->getContent();
@endphp
@section('content')

<my-wishlist inline-template>
    <div>
    <div class="container">
        <div class="empty-space col-xs-b15 col-sm-b30"></div>
        <div class="breadcrumbs">
            <a href="{{ url('/') }}">{{ __('Home') }}</a>
            <a href="{{ url('/wishlist') }}">{{ __('Wishlist') }}</a>
        </div>
        <div class="empty-space col-xs-b15 col-sm-b50 col-md-b100"></div>
        <div class="text-center">
            {{-- <div class="simple-article size-3 grey uppercase col-xs-b5">{{ __('Your Wishlist') }}</div> --}}
            <div class="h2">{{ __('Your Wishlist') }}</div>
            <div class="title-underline center"><span></span></div>
            <div class="simple-article size-3 grey uppercase col-xs-b5">
                {!! __('There are :total products in this list', ['total' => count($wishlist)]) !!}
            </div>
        </div>
    </div>
    
    <div class="empty-space col-xs-b35 col-md-b70"></div>
    <div class="container">
        <table class="cart-table">
            <thead>
                <tr>
                    <th></th>
                    <th colspan="2">{{ __('Product') }}</th>
                    <th style="width: 150px;">{{ __('Price') }}</th>
                    <th style="width: 260px;">{{ __('Stock Status') }}</th>                               
                    <th style="width: 70px;">{{ __('Remove') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if(count($wishlist) > 0)
                @foreach($wishlist as $row)
                @php
                $product = \App\Models\Product::find($row->id);
                @endphp
                <tr class="pt-30" style="border-bottom: 2px solid #edecec;">
                    <td class="image product-thumbnail pt-40">
                        <a href="{{ url('/product/'.$row->name.'/'.$row->id) }}">
                            <img src="{{ $row->attributes->image }}" alt="#" height="100" />
                        </a>
                    </td>
                    <td colspan="2" class="product-des product-name">
                        <h3>{{ $row->name }}</h3>
                        @foreach ($row->attributes->options as $op)
                        <small>{{ $op->name }}</small>,
                        @endforeach
                       
                    </td>
                    <td class="price" data-title="Price">
                        <h3 class="text-brand">{{ amount_format($row->price) }}</h3>
                    </td>
                    <td data-title="Stock">
                        @if($product->inStock())
                        <h3 class="text-brand"> {{ __('In Stock') }} </h3>
                        @else
                        <h3 class="text-brand"> {{ __('Out Of Stock') }} </h3>
                        @endif
                    </td>
                    
                    <td  data-title="Remove">
                        <a @click="remove('{{ $row->id }}')" class="text-body">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                    <td></td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td></td>
                    <td colspan="5" style="text-align: center;padding:30px;"><h6>{{ __('No Product found in your wishlist') }}</h6></td>
                    <td></td>
                </tr>
                @endif
            </tbody>
        </table>
        <div class="empty-space col-xs-b35 col-md-b70"></div>
        <div class="empty-space col-xs-b35 col-md-b70"></div>
    </div>
    </div>
</my-wishlist>
@endsection
