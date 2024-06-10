@extends('layouts.seller')
@section('title', 'Edit Menu')
@section('page-style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
	<style>
.select2-container--classic .select2-selection--single .select2-selection__arrow b, .select2-container--default .select2-selection--single .select2-selection__arrow b {
		padding-right: 0px !important;
	}
	.menu-header{
	color: #7367f0;
    text-decoration: none;
	}
	a:hover {
    color: #7367f0;
    text-decoration: none;
     }
	 body{
	 font-family: var(--bs-body-font-family) !important;
	 }
	  /* Mobile View */
	  @media screen and (max-width: 900px) {
            .menu_set{
				margin-right: 105px !important;
            }
        }
		.loader {
			border: 16px solid #f3f3f3; /* Light grey */
			border-top: 16px solid #3498db; /* Blue */
			border-radius: 50%;
			width: 80px;
			height: 80px;
			animation: spin 2s linear infinite;
		}

			@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}	
	</style>
@endsection
@section('content')
<div class="content-wrapper container-xxl p-0">
	<div class="content-header row">
	<div class="content-header-left col-md-9 col-12 mb-2">
		<div class="row breadcrumbs-top">
			<div class="col-12">
				<h2 class="content-header-title float-start mb-0" style="color:#5e5873">{{ __('Edit Menu') }}</h2>
				<div class="breadcrumb-wrapper">
					<ol class="breadcrumb" style="background-color: #f8f8f8 !important;">
						<li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}" class="menu-header">{{ __('Home') }}</a>
						</li>
						<li class="breadcrumb-item"><a href="{{ url('/seller/setting/menu') }}" class="menu-header">{{ __('Menus') }}</a>
						</li>
						<li class="breadcrumb-item active">{{ __('Edit Menu') }}
						</li>
					</ol>
				</div>

			</div>
		</div>
    </div>
</div>
<div class="loader" style=" margin: auto; "></div>
<div class="row" id="body" style="display: none; ">
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<div style=" display: flex; ">
					<h4 class="mb-20">{{ __('Menu List') }}</h4>
					<a href="https://community.singlebag.com/docs/menus/" target="_blank" style="color: #7367f0 !important;"><p style=" margin-left: 10px; "><i class="fas fa-question-circle"></i>{{ __('How to use') }} </p></a>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<form id="frmEdit" class="form-horizontal">
							<div class="custom-form">
								<div class="form-group">
									<label for="text">{{ __('Text') }}</label>
									<div class="input-group">
										<input type="text" class="form-control item-menu" name="text" id="text" placeholder="Text" autocomplete="off">
										
									</div>
									
								</div>
								<div class="form-group">
									<label for="menu_type">{{ __('Menu Type') }}</label>
									<select name="menu_type" id="menu_type" class="custom-select mr-sm-2 item-menu" required>
										<option value="" disabled selected>{{ __('Select Menu Type') }}</option>
										<option value="pages">{{ __('Pages') }}</option>
										<option value="custom_link">{{ __('Custom Link') }}</option>
										<option value="products">{{ __('Products') }}</option>
										<option value="brands">{{ __('Brands') }}</option>
										<option value="categories">{{ __('Categories') }}</option>
									</select>
								</div>								
								<div class="form-group" id="href_append">
									<!-- <label for="href">{{ __('URL') }}</label>
									<input type="text" class="form-control item-menu" id="href" name="href" placeholder="URL" required autocomplete="off"> -->
								</div>
								<div class="form-group">
									<label for="target">{{ __('Target') }}</label>
									<select name="target" id="target" class="custom-select mr-sm-2 item-menu">
										<option value="_self">{{ __('Self') }}</option>
										<option value="_blank">{{ __('Blank') }}</option>
										<option value="_top">{{ __('Top') }}</option>
									</select>
								</div>
								<!-- <div class="form-group none">
									<label for="title">{{ __('Tooltip') }}</label>
									<input type="text" name="title" class="form-control item-menu" id="title" placeholder="Tooltip">
								</div> -->
							</div>
						</form>
						<div class="menu-add-update d-flex">
							<button type="button" id="btnUpdate" class="btn btn-update  btn-warning text-white col-6 mr-2" disabled><i class="fas fa-sync-alt"></i> {{ __('Update') }}</button>
							<button type="button" id="btnAdd" class="btn btn-primary col-6 "><i class="fas fa-plus"></i> {{ __('Add') }}</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="card mb-3">
			<div class="card-body">
				<div class="row mb-10" >
					<div class="col-lg-6">
						<h4>{{ __('Menu structure') }}</h4>
					</div>
					<div class="col-lg-6">
						<div class="save-menu float-right" >
							<form  class="float-right basicform" method="post" action="{{ route('seller.menu.update',$info->id) }}"> @csrf @method('PUT') <input type="hidden" name="data" id="data">
								
								<div class="input-group mb-2 menu_set">

									<input type="text" id="name" class="form-control" placeholder="Name" value="{{ $info->name }}" required="" name="name" autocomplete="off" value="">

									<div class="input-group-append">                                            
										<button class="btn btn-primary basicbtn" id="form-button"  type="submit">{{ __('Save') }}</button>
									</div>
								</div>
								</form>
							</div>
						</div>
					</div>
					<ul id="myEditor" class="sortableLists list-group">
					</ul>	
					<div>
						<p style="padding-top: 13px;"><font color="red">*</font><span> {{ __('Footer menu not support child menu item!') }}</span></p>
					<div>	
					
            </div>
			</div>
		</div>
	</div>
	</div>
    </div>
	 </div>
<input type="hidden" value="{{ $info->data }}" id="arrayjson">
@endsection
@section('page-script')
<script src="{{ asset('assets/js/form.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-menu-editor.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-iconpicker/js/menu.js') }}"></script>
<script>
	$(document).on('change',"#menu_type", function(){
		var menu_type = $(this).find(":selected").val();
		if(menu_type == 'pages') {
		$("#href_append").html("<label for='href'>{{ __('URL') }}</label><select name='href' id='href' class='custom-select mr-sm-2 select2 item-menu' required><option value='' selected disabled>Select Page</option>@foreach($pages as $page)<option value='/page/{{ $page->slug }}/{{ $page->id }}'>{{ $page->title }}</option>@endforeach</select></div>");
		} else if(menu_type == 'custom_link') {
		$("#href_append").html("<label for='href'>{{ __('URL') }}</label><input type='text' class='form-control item-menu' id='href' name='href' placeholder='URL' required autocomplete='off'>");
		} else if(menu_type == 'products') {
		$("#href_append").html("<label for='href'>{{ __('URL') }}</label><select name='href' id='href' class='custom-select mr-sm-2 select2 item-menu' required><option value='' selected disabled>Select Product</option>@foreach($products as $product)<option value='/product/{{ $product->slug }}/{{ $product->id }}'>{{ $product->title }}</option>@endforeach</select></div>");
		} else if(menu_type == 'brands') {
		$("#href_append").html("<label for='href'>{{ __('URL') }}</label><select name='href' id='href' class='custom-select mr-sm-2 select2 item-menu' required><option value='' selected disabled>Select Brand</option>@foreach($brands as $brand)<option value='/brand/{{ $brand->slug }}/{{ $brand->id }}'>{{ $brand->name }}</option>@endforeach</select></div>");
		} else if(menu_type == 'categories') {
		$("#href_append").html("<label for='href'>{{ __('URL') }}</label><select name='href' id='href' class='custom-select mr-sm-2 select2 item-menu' required><option value='' selected disabled>Select Category</option>@foreach($categories as $category)<option value='/category/{{ $category->slug }}/{{ $category->id }}'>{{ $category->name }}</option>@endforeach</select></div>");
		}
		$('.select2').select2();
	});
	$( document ).ready(function() {
		$(".btnEdit").hide();
		$("#btnUpdate").hide();
	});	
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#body').show();
		$('.loader').hide();
	});
</script>
@endsection