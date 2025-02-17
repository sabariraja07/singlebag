@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Express checkout'])
@endsection
@section('content')

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
							<div class="row">
								<div class="col-12 col-md-12 col-lg-12">
									<h6>{{ __('Create Express Checkout Url For Direct Order') }}</h6>
									<hr>
								</div>
								<div class="col-12 col-md-8 col-lg-8">
									
									<form class="express_form">
										<input type="hidden" name="id" value="{{ $info->id }}">
									@foreach ($variations as $key=> $item)
									<div class="form-group">
										<label class="form-label">{{ $key }}</label>
										<div class="selectgroup w-100">
											@foreach ($item as $row)
										   <label class="selectgroup-item">
										   <input type="radio" name="variation[{{ $row->category_id }}]" value="{{ $row->variation->id }}" class="selectgroup-input" >
										   <span class="selectgroup-button">{{ $row->variation->name }}</span>
										   </label>
										   @endforeach
										</div>
									 </div>
									 @endforeach
									 <input type="hidden" name="term" value="{{ $info->id }}">
									 @foreach ($info->options as $key=> $option)
									
									<div class="form-group">
										<label class="form-label">{{ $option->name }} @if($option->is_required == 1) <span class="text-danger">*</span> @endif</label>
										<div class="selectgroup w-100">
											@foreach ($option->childrenCategories as $row)
										   <label class="selectgroup-item">
										   <input  @if($option->select_type == 1) type="checkbox" name="option[]"  @else type="radio" name="option[{{ $key }}]" @endif  value="{{ $row->id }}" class="selectgroup-input @if($option->is_required == 1) req @endif" >
										   <span class="selectgroup-button">{{ $row->name }}</span>
										   </label>
										   @endforeach
										</div>
									 </div>
									 @endforeach
									 <div class="form-group">
										<label class="form-label">{{ __('Quantity') }}</label>
										<input type="number" name="qty" class="form-control" value="1" required min="1">
									 </div>	
									 <p class="text-danger none required_option">{{ __('Please Select A Option From Required Field') }}</p>
									 <button type="submit" class="btn btn-success">{{ __('Generate Url') }}</button>
									</form>
									
								</div>	
								<div class="col-12 col-md-4 col-lg-4"> <p class="exp_area none">{{ __('Checkout Url') }}: <span class="express_url text-primary"></span></p></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

</div>


<input type="hidden" id="base_url" value="{{ url('/') }}">
@endsection
@push('js')

<script type="text/javascript" src="{{ asset('assets/js/form.js') }}"></script>
<script src="{{ asset('assets/seller/product/index.js') }}"></script>
@endpush