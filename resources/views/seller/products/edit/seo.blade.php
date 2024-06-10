@extends('layouts.seller')
@section('title', 'Edit SEO')
@section('page-style')
<style>
        @media screen and (max-width: 900px) {
        #save_mobile_view {
            display: block;
            text-align: center;
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
				<h2 class="content-header-title float-start mb-0">{{ __('SEO') }}</h2>
				<div class="breadcrumb-wrapper">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
						</li>
						<li class="breadcrumb-item"><a href="{{ url('/seller/products') }}">{{ __('Products') }}</a>
						</li>   
						<li class="breadcrumb-item active">{{ __('SEO') }}
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
					<div class="col-sm-9">
						<form  method="post" action="{{ route('seller.products.seo',$info->id) }}">
						@csrf
						<div class="form-group mb-1">
							<label>{{ __('Meta Title') }}</label>
							<input type="text" name="title" class="form-control" required="" value="{{ $seo['meta_title'] ?? ''}}">
						</div>				
						<div class="form-group mb-1">
							<label>{{ __('Meta Keyword') }}</label>
							<input type="text" name="keywords" class="form-control" required="" value="{{ $seo['meta_keyword'] ?? ''}}">
						</div>
						<div class="form-group mb-1">
							<label>{{ __('Meta Description') }}</label>
							<textarea class="form-control" name="description" required="" >{{ $seo['meta_description'] ?? ''}}</textarea>
						</div>
						<div id="save_mobile_view">
							<button type="submit" class="btn btn-primary basicbtn">{{ __('Save Changes') }}</button>	
						</div>
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

@endsection