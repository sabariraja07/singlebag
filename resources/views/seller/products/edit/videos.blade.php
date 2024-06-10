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
						<li class="breadcrumb-item"><a href="{{ url('/seller/products') }}">{{ __('Products') }}</a>
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
						<ul class="nav nav-pills flex-column">
							<li class="nav-item">
								<a class="nav-link" href="{{ route('seller.products.edit',$info->id) }}"><i class="fas fa-cogs"></i> {{ __('Item') }}</a>
                            </li>
                            
							<li class="nav-item">
								<a class="nav-link" href="{{ url('seller/product/'.$info->id.'/price') }}"><i class="fas fa-money-bill-alt"></i> {{ __('Price') }}</a>
                            </li>
                            <li class="nav-item">
								<a class="nav-link " href="{{ url('seller/product/'.$info->id.'/option') }}"><i class="fas fa-tags"></i> {{ __('Options') }}</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{ url('seller/product/'.$info->id.'/variant') }}"><i class="fas fa-expand-arrows-alt"></i> {{ __('Variants') }}</a>
							</li>
							
							<li class="nav-item">
								<a class="nav-link" href="{{ url('seller/product/'.$info->id.'/image') }}"><i class="far fa-images"></i> {{ __('Images') }}</a>
							</li>
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ url('seller/product/'.$info->id.'/video') }}"><i class="fa fa-cubes"></i> {{ __('Video') }}</a>
                            </li>							
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('seller/product/'.$info->id.'/inventory') }}"><i class="fa fa-cubes"></i> {{ __('Inventory') }}</a>
                            </li>
							<li class="nav-item">
								<a class="nav-link" href="{{ url('seller/product/'.$info->id.'/files') }}"><i class="fas fa-file"></i> {{ __('Files') }}</a>
							</li>

							<li class="nav-item">
								<a class="nav-link" href="{{ url('seller/product/'.$info->id.'/seo') }}"><i class="fas fa-chart-line"></i> {{ __('SEO') }}</a>
							</li>
						</ul>
					</div>
					<div class="col-sm-9">
						<form class="basicform" method="post" action="">
                        @csrf
                        <div class="form-group mb-1">
                            <label for="video_provider">{{ __('Video Provider') }}</label>
                           <select name="video_provider" id="video_provider" class="form-select">
                               <option value="youtube">{{ __('Youtube') }}</option>
                               <option value="dailymotion">{{ __('Dailymotion') }}</option>
                               <option value="vimeo">{{ __('Vimeo') }}</option>
                           </select>
                        </div>
                        <div class="form-group mb-1">
                            <label for="video_link">{{ __('Video Link') }}</label>
                            <input type="text" name="video_link"  value="" class="form-control">
                        </div>
						<button type="submit" class="btn btn-primary basicbtn">{{ __('Save Changes') }}</button>	
						</form>
					</div>
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