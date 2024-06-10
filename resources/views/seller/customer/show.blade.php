@extends('layouts.seller')
@section('title', 'Customer Info')
@section('content')
<div class="content-wrapper container-xxl p-0">
	<div class="content-header row">
		<div class="content-header-left col-md-9 col-12 mb-2">
			<div class="row breadcrumbs-top">
				<div class="col-12">
					<h2 class="content-header-title float-start mb-0">{{ __('Customer Info') }}</h2>
					<div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('Customer Info') }}
                            </li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
		</div>
	</div>
	<div class="content-body desktop_view">
		<div class="row">
			<div class="col-lg-3 col-sm-6">
				<div class="card">
					<div class="card-body d-flex align-items-center justify-content-between">
						<div>
							<h3 class="fw-bolder mb-75">{{ $info->orders_count }}</h3>
							<span>{{ __('Total Orders') }}</span>
						</div>
						<div class="avatar bg-light-primary p-50">
							<span class="avatar-content">
								<i class="ph-clock-counter-clockwise-bold font-medium-5"></i>
							</span>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-sm-6">
				<div class="card">
					<div class="card-body d-flex align-items-center justify-content-between">
						<div>
							<h3 class="fw-bolder mb-75">{{ amount_format($earnings) }}</h3>
							<span>{{ __('Total Spend Of Amount') }}</span>
						</div>
						<div class="avatar bg-light-warning p-50">
							<span class="avatar-content">
								<i class="ph-money-bold font-medium-5"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-sm-6">
				<div class="card">
					<div class="card-body d-flex align-items-center justify-content-between">
						<div>
							<h3 class="fw-bolder mb-75">{{ $info->orders_processing_count }}</h3>
							<span>{{ __('Total Processing Orders') }}</span>
						</div>
						<div class="avatar bg-light-danger p-50">
							<span class="avatar-content">
								<i class="ph-clock-counter-clockwise-bold font-medium-5"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-sm-6">
				<div class="card">
					<div class="card-body d-flex align-items-center justify-content-between">
						<div>
							<h3 class="fw-bolder mb-75">{{ $info->orders_complete_count }}</h3>
							<span>{{ __('Total Complete Orders') }}</span>
						</div>
						<div class="avatar bg-light-success p-50">
							<span class="avatar-content">
								<i class="ph-clock-counter-clockwise-bold font-medium-5"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="basic-datatable">
			<div class="col-12 col-md-4">
				<div class="card">
					<div class="card-body">
						<div class="user-avatar-section">
							<div class="d-flex align-items-center flex-column">
								<img class="img-fluid rounded mt-3 mb-2" src="admin/images/man-2-circle.svg" height="110" width="110" alt="{{ $info->fullname }}">
								<div class="user-info text-center">
									<h4>{{ $info->fullname }}</h4>
									<span class="badge bg-light-secondary">{{ $info->email }}</span>
								</div>
							</div>
						</div>
						<h4 class="fw-bolder border-bottom pb-50 mb-1">{{ __('Details') }}</h4>
						<div class="info-container">
							<ul class="list-unstyled">
								<li class="mb-75">
									<span class="fw-bolder me-25">{{ __('Registered At') }}:</span>
									<span>{{ $info->created_at->diffForHumans() }}</span>
								</li>
								<li class="mb-75">
									<span class="fw-bolder me-25">{{ __('Registration Date') }}:</span>
									<span>{{ $info->created_at->format('d-F-Y') }}</span>
								</li>
								{{-- <li class="mb-75">
									<span class="fw-bolder me-25">Status:</span>
									<span class="badge bg-light-success">Active</span>
								</li> --}}
							</ul>
							{{-- <div class="d-flex justify-content-center pt-2">
								<a href="javascript:;" class="btn btn-primary me-1 waves-effect waves-float waves-light" data-bs-target="#editUser" data-bs-toggle="modal">
									Edit
								</a>
							</div> --}}
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-header">
						<h4 class="card-title">{{ __('Order History') }}</h4>
                    </div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table">
								<tbody><tr>
									<th>{{ __('Invoice ID') }}</th>
									<th>{{ __('Amount') }}</th>
									{{-- <th>{{ __('Items') }}</th> --}}
									
									{{-- <th>{{ __('Payment Method') }}</th> --}}
									<th>{{ __('Payment Status') }}</th>
									<th>{{ __('Order Status') }}</th>
									<th>{{ __('Due Date') }}</th>
									{{-- <th>{{ __('Action') }}</th> --}}
								</tr>
								@foreach($orders as $row)
								<tr>
									<td><a href="{{ route('seller.invoice',$row->id) }}">{{ $row->order_no }}</a></td>
									<td class="font-weight-600">{{ amount_format($row->total) }}</td>
									{{-- <td class="font-weight-600">{{ $row->order_item_count }}</td> --}}
									{{-- <td>
										{{ $row->gateway->name ?? '' }}
									</td> --}}
									<td>
										@if($row->payment_status==2)
										<span class="badge badge-light-warning">{{ __('Pending') }}</span>
		
										@elseif($row->payment_status==1)
										<span class="badge badge-light-success">{{ __('Complete') }}</span>
		
										@elseif($row->payment_status==0)
										<span class="badge badge-light-danger">{{ __('Cancel') }}</span> 
										@elseif($row->payment_status==3)
										<span class="badge badge-light-danger">{{ __('Incomplete') }}</span> 
		
										@endif
		
									</td>
									<td>
										
										@if($row->status=='pending')
										<span class="badge badge-light-warning">{{ __('Awaiting processing') }}</span>
		
										@elseif($row->status=='processing')
										<span class="badge badge-light-primary">{{ __('Processing') }}</span>
		
										@elseif($row->status=='ready-for-pickup')
										<span class="badge badge-light-info">{{ __('Ready for pickup') }}</span>
		
										@elseif($row->status=='completed')
										<span class="badge badge-light-success">{{ __('Completed') }}</span>
										
										@elseif($row->status=='delivered')
										<span class="badge badge-light-dark">{{ __('Delivered') }}</span>
		
										@elseif($row->status=='picked_up')
										<span class="badge badge-light-info">{{ __('Picked_Up') }}</span>
										
										@elseif($row->status=='archived')
										<span class="badge badge-light-warning">{{ __('Archived') }}</span>
		
										@elseif($row->status=='canceled')
										<span class="badge badge-light-danger">{{ __('Canceled') }}</span>
		
										@else
										<span class="badge badge-light-info">{{ $row->status }}</span>
		
										@endif
		
									</td>
									<td>{{ $row->created_at->format('d-F-Y') }}</td>
									{{-- <td>
										<a href="{{ route('seller.order.show',$row->id) }}" class="btn btn-success">{{ __('Detail') }}</a>
									</td> --}}
								</tr>
								@endforeach
		
								</tbody>
							</table>
		
							{{ $orders->links('vendor.pagination.bootstrap-4') }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="content-body mobile_view">
		<div class="row">
			<div class="col-lg-3 col-sm-6">
				<div class="card">
					<div class="card-body d-flex align-items-center justify-content-between">
						<div>
							<h3 class="fw-bolder mb-75">{{ $info->orders_count }}</h3>
							<span>{{ __('Total Orders') }}</span>
						</div>
						<div class="avatar bg-light-primary p-50">
							<span class="avatar-content">
								<i class="ph-clock-counter-clockwise-bold font-medium-5"></i>
							</span>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-3 col-sm-6">
				<div class="card">
					<div class="card-body d-flex align-items-center justify-content-between">
						<div>
							<h3 class="fw-bolder mb-75">{{ amount_format($earnings) }}</h3>
							<span>{{ __('Total Spend Of Amount') }}</span>
						</div>
						<div class="avatar bg-light-warning p-50">
							<span class="avatar-content">
								<i class="ph-money-bold font-medium-5"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-sm-6">
				<div class="card">
					<div class="card-body d-flex align-items-center justify-content-between">
						<div>
							<h3 class="fw-bolder mb-75">{{ $info->orders_processing_count }}</h3>
							<span>{{ __('Total Processing Orders') }}</span>
						</div>
						<div class="avatar bg-light-danger p-50">
							<span class="avatar-content">
								<i class="ph-clock-counter-clockwise-bold font-medium-5"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-sm-6">
				<div class="card">
					<div class="card-body d-flex align-items-center justify-content-between">
						<div>
							<h3 class="fw-bolder mb-75">{{ $info->orders_complete_count }}</h3>
							<span>{{ __('Total Complete Orders') }}</span>
						</div>
						<div class="avatar bg-light-success p-50">
							<span class="avatar-content">
								<i class="ph-clock-counter-clockwise-bold font-medium-5"></i>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="basic-datatable">
			<div class="col-12 col-md-4">
				<div class="card">
					<div class="card-body">
						<div class="user-avatar-section">
							<div class="d-flex align-items-center flex-column">
								<img class="img-fluid rounded mt-3 mb-2" src="admin/images/man-2-circle.svg" height="110" width="110" alt="{{ $info->fullname }}">
								<div class="user-info text-center">
									<h4>{{ $info->fullname }}</h4>
									<span class="badge bg-light-secondary">{{ $info->email }}</span>
								</div>
							</div>
						</div>
						<h4 class="fw-bolder border-bottom pb-50 mb-1">{{ __('Details') }}</h4>
						<div class="info-container">
							<ul class="list-unstyled">
								<li class="mb-75">
									<span class="fw-bolder me-25">{{ __('Registered At') }}:</span>
									<span>{{ $info->created_at->diffForHumans() }}</span>
								</li>
								<li class="mb-75">
									<span class="fw-bolder me-25">{{ __('Registration Date') }}:</span>
									<span>{{ $info->created_at->format('d-F-Y') }}</span>
								</li>
								{{-- <li class="mb-75">
									<span class="fw-bolder me-25">Status:</span>
									<span class="badge bg-light-success">Active</span>
								</li> --}}
							</ul>
							{{-- <div class="d-flex justify-content-center pt-2">
								<a href="javascript:;" class="btn btn-primary me-1 waves-effect waves-float waves-light" data-bs-target="#editUser" data-bs-toggle="modal">
									Edit
								</a>
							</div> --}}
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-8">
				@foreach ($orders as $row)
               <div class="card">
                    {{-- <div class="card-header">
						<h4 class="card-title">{{ __('Order History') }}</h4>
                    </div> --}}
					{{-- <div class="card-body"> --}}
				    <a href="{{ route('seller.invoice',$row->id) }}" style="padding: 13px !important;">{{ $row->order_no }}</a> <br>
					{{-- <p style="padding: 13px !important;"> {{ $row->order_no }} </p>	<br> --}}
					<p style="margin-top: -41px  !important;padding: 13px  !important;">{{ amount_format($row->total) }}</p>
					{{-- </div> --}}
				</div> 
				
				@endforeach
				{{ $orders->links('vendor.pagination.bootstrap-4') }}
			</div>
		</div>
	</div>
</div>
@endsection