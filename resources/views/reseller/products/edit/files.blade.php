@extends('layouts.seller')
@section('title', 'Edit Files')
@section('content')
<div class="content-header row">
	<div class="content-header-left col-md-9 col-12 mb-2">
		<div class="row breadcrumbs-top">
			<div class="col-12">
				<h2 class="content-header-title float-start mb-0">{{ __('Files') }}</h2>
				<div class="breadcrumb-wrapper">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
						</li>
						<li class="breadcrumb-item"><a href="{{ url('/seller/product') }}">{{ __('Products') }}</a>
						</li>   
						<li class="breadcrumb-item active">{{ __('Files') }}
						</li>
					</ol>
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

						<!-- <button class="btn btn-success float-right mb-2" data-toggle="modal" data-target="#attribute_modal">{{ __('Create File') }}</button> -->
						<a href="javascript:void(0)" data-bs-target="#attribute_modal" data-bs-toggle="modal">
							<span class="btn btn-primary mb-1" style="float:right">{{ __('Create File') }}</span><br><br>
						</a>
						<p class="text-left text-danger">{{ __('Your customer will automatically receive the download link via email') }}</p>
							
							<div class="table-responsive">
								<table class="table table-hover table-nowrap card-table">
									<thead>
										<tr>
											
											<th>{{ __('URL') }}</th>
											<th width="200">&nbsp;</th>
										</tr>
									</thead>
									<tbody>
										@foreach($info->files as $row)
										<tr>
											
											<td>{{ $row->url }}</td>
											<td class="text-right">
												<div class="btn-group" role="group">

														<button type="button" class="btn btn-success edit" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $row->id }}" data-attribute="{{ $row->attribute_id }}" data-name="{{ $row->name }}" data-url="{{ $row->url }}"><i class="ph-pencil-bold"></i></button>
													
														<button type="button" onclick="make_trash('{{ base64_encode($row->id) }}')" class="btn btn-danger"><i class="ph-trash" aria-hidden="true"></i></button>
													
												</div>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						



					</div>
				</div>
			</div>
		</div>
	</div>

</div>

</div>


<!-- Modal -->
<form action="{{ route('seller.file.store') }}" class="basicform">
	@csrf
<div class="modal fade" id="attribute_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">{{ __('Add New File') }}</h5>
			</div>
			<div class="modal-body">
				
				<input type="hidden" name="term" value="{{ $info->id }}">
				
				<div class="form-group">
					<label>{{ __('Url') }}</label>
					<input type="text" name="url" class="form-control" required="">
				</div>
				
			</div>
			<div class="modal-footer">

				<button type="reset" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal" aria-label="Close">
                                    Close
                </button>
				<button type="submit" class="btn btn-primary basicbtn">{{ __('Save') }}</button>
			</div>
		</div>
	</div>
</div>
</form>

<form method="post" action="{{ route('seller.files.update') }}" class="basicform">
	@csrf
<div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Edit</h5>
			</div>
			<div class="modal-body">
				<input type="hidden" name="id" id="id">
				<input type="hidden" name="term" value="{{ $info->id }}">
				
				<div class="form-group">
					<label>{{ __('Url') }}</label>
					<input type="text" name="url" class="form-control" required="" id="url">
				</div>
				
			</div>
			<div class="modal-footer">

				<button type="reset" class="btn btn-outline-secondary"
					data-bs-dismiss="modal" aria-label="Close">
					Close
				</button>
				<button type="submit" class="btn btn-primary basicbtn">{{ __('Save') }}</button>
			</div>
		</div>
	</div>
</div>
</form>


<form action="{{ route('seller.files.destroy') }}" id="basicform">
	@csrf
	<input type="hidden" name="a_id" id="m_id">
</form>
@endsection
@section('page-script')
<script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
<script src="{{ asset('assets/seller/product/files.js') }}"></script>
@endsection