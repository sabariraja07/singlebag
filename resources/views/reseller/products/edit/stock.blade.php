@extends('layouts.seller')
@section('title', 'Edit Stock')
@section('page-style')
<style>
	.none{
		display: none;
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
				<h2 class="content-header-title float-start mb-0">{{ __('Inventory') }}</h2>
				<div class="breadcrumb-wrapper">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
						</li>
						<li class="breadcrumb-item"><a href="{{ url('/seller/product') }}">{{ __('Products') }}</a>
						</li>   
						<li class="breadcrumb-item active">{{ __('Inventory') }}
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
					@if(current_shop_type() == 'reseller')
					<div class="col-sm-9">
						{{-- <form class="basicform" method="post" action="{{ route('seller.products.stock_update',$info->id) }}">
                        @csrf --}}
                        <div class="form-group mb-1">
                            <label for="sku">{{ __('SKU') }}</label>
                            <input type="text" name="sku"  value="{{ $inventory_stock->stock->sku ?? '' }}" class="form-control" disabled>
                        </div>
						
                        <div class="form-group mb-1">
                            <label for="stock_manage">{{ __('Manage Stock') }}</label>
                           <select name="stock_manage" id="stock_manage" class="form-select" disabled>
                               <option value="1" @if($inventory_stock->stock->stock_manage == 1) selected @endif>{{ __('Manage Stock') }}</option>
                               <option value="0" @if($inventory_stock->stock->stock_manage == 0) selected @endif>{{ __('Dont Need To Manage Stock') }}</option>
                           </select>
                        </div>
						<div class="stock_area @if($inventory_stock->stock->stock_manage == 0) none @endif">
                        <div class="form-group mb-1">
                            <label for="stock_status">{{ __('Stock Status') }}</label>
                           <select name="stock_status" id="stock_status" class="form-select" disabled>
                               <option value="1" @if($inventory_stock->stock->stock_status == 1) selected @endif>{{ __('In Stock') }}</option>
                               <option value="0" @if($inventory_stock->stock->stock_status == 0) selected @endif>{{ __('Out Of Stock') }}</option>
                           </select>
                        </div>
						
                        
                        <div class="form-group mb-1">
                            <label for="stock_qty">{{ __('Stock Quantity') }}</label>
                            <input type="text" name="stock_qty"  value="{{ $inventory_stock->stock->stock_qty ?? '' }}" class="form-control" disabled>
                        </div>
                      </div>
						{{-- <button type="submit" class="btn btn-primary basicbtn">{{ __('Save Changes') }}</button>	 --}}
						</form>
					</div>
					@else
					<div class="col-sm-9">
						<form class="basicform" method="post" action="{{ route('seller.products.stock_update',$info->id) }}">
                        @csrf
                        <div class="form-group mb-1">
                            <label for="sku">{{ __('SKU') }}</label>
                            <input type="text" name="sku"  value="{{ $info->stock->sku ?? '' }}" class="form-control">
                        </div>
						
                        <div class="form-group mb-1">
                            <label for="stock_manage">{{ __('Manage Stock') }}</label>
                           <select name="stock_manage" id="stock_manage" class="form-select">
                               <option value="1" @if($info->stock->stock_manage == 1) selected @endif>{{ __('Manage Stock') }}</option>
                               <option value="0" @if($info->stock->stock_manage == 0) selected @endif>{{ __('Dont Need To Manage Stock') }}</option>
                           </select>
                        </div>
						<div class="stock_area @if($info->stock->stock_manage == 0) none @endif">
                        <div class="form-group mb-1">
                            <label for="stock_status">{{ __('Stock Status') }}</label>
                           <select name="stock_status" id="stock_status" class="form-select">
                               <option value="1" @if($info->stock->stock_status == 1) selected @endif>{{ __('In Stock') }}</option>
                               <option value="0" @if($info->stock->stock_status == 0) selected @endif>{{ __('Out Of Stock') }}</option>
                           </select>
                        </div>
						
                        
                        <div class="form-group mb-1">
                            <label for="stock_qty">{{ __('Stock Quantity') }}</label>
                            <input type="text" name="stock_qty"  value="{{ $info->stock->stock_qty ?? '' }}" class="form-control">
                        </div>
                      </div>
						<button type="submit" class="btn btn-primary basicbtn">{{ __('Save Changes') }}</button>	
						</form>
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
</div>
@endsection
@section('page-script')

<script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/stock.js') }}"></script>
@endsection