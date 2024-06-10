@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=> trans('Store')])
@endsection
@section('content')
<section class="section">

	<div class="section-body">

		<div class="row mt-sm-4">
			<div class="col-12 col-md-12 col-lg-5">
				<div class="card profile-widget">
					<div class="profile-widget-header">
						<img alt="" src="{{ get_shop_logo_url($info->id) }}" class="profile-widget-picture" height="80">
						<div class="profile-widget-items">

							<div class="profile-widget-item">
								<div class="profile-widget-item-label">{{ __('Plan') }}</div>
								<div class="profile-widget-item-value">{{ $info->subscription->plan->name ?? '' }}</div>
							</div>

							<div class="profile-widget-item">
								<div class="profile-widget-item-label">{{ __('Will Expired') }}</div>
								<div class="profile-widget-item-value">{{ $info->will_expire }}</div>
							</div>

							<div class="profile-widget-item">
								<div class="profile-widget-item-label">{{ __('Status') }}</div>
								<div class="profile-widget-item-value">
									@if($info->status== 'active') <span class="badge badge-success">{{ __('Active') }}</span>
									@elseif($info->status== 'trash') <span class="badge badge-danger">{{ __('Trash') }}</span>
									@elseif($info->status== 'suspended') <span class="badge badge-warning">{{ __('Suspended') }}</span>
									@elseif($info->status== 'pending') <span class="badge badge-warning">{{ __('Pending') }}</span>
									@endif</div>
							</div>
						</div>
					</div>
					<div class="profile-widget-description">
						<div class="profile-widget-name">{{ $info->name }}
							<div class="text-muted d-inline font-weight-normal">
								<div class="slash"></div><a target="_blank" href="{{ $info->domain->full_domain ?? '' }}">{{ $info->domain->domain ?? '' }}</a>
							</div>
						</div>
						<ul class="list-group">
							<li class="list-group-item">{{ __('Name :') }} {{ $info->name }}</li>
							<li class="list-group-item">{{ __('Email :') }} {{ $info->user->email }}</li>
							<li class="list-group-item">{{ __('Domain :') }} {{ $info->domain->domain ?? '' }}
								@if(!empty($info->domain))
								@if($info->domain->status === 1)
								<span class="badge badge-success">{{ __('Active') }}</span>
								@elseif($info->domain->status === 0) <span class="badge badge-danger">{{ __('Trash') }}</span>
								@elseif($info->domain->status === 2) <span class="badge badge-warning">{{ __('Draft') }} @elseif($info->domain->status === 3) <span class="badge badge-warning">{{ __('Pending') }}</span>
									@endif
									@endif
							</li>

							<li class="list-group-item">{{ __('Total Customers :') }} {{ number_format($info->customers_count) }}</li>
							<li class="list-group-item">{{ __('Total Orders :') }} {{ number_format($info->orders_count) }}</li>
							<li class="list-group-item">{{ __('Total Posts:') }} {{ number_format($info->term_count) }}</li>
							<li class="list-group-item">{{ __('Storage Used:') }} {{ storeStorageSize($info->id) }} / {{ isset($info->subscription->plan->storage) && $info->subscription->plan->storage == -1 ? __('Unlimited') : human_filesize($info->subscription->plan->storage ?? 0) }}</li>

							<li class="list-group-item">{{ __('Joining Date:') }} {{ $info->created_at->format('d-M-Y') }}</li>

						</ul>

					</div>

				</div>
			</div>
			<div class="col-12 col-md-12 col-lg-7">
				<div class="card">
					<form method="post" id="productform" novalidate="" action="{{ route('partner.email.store') }}">
						@csrf
						<div class="card-header">
							<h4>{{ __('Send Email') }} </h4>
						</div>
						<div class="card-body">

							<div class="row">
								<div class="form-group col-md-6 col-12">
									<label>{{ __('Mail To') }}</label>
									<input type="email" name="email" class="form-control" value="{{ $info->email }}" required="">

								</div>

								<div class="form-group col-md-6 col-12">
									<label>{{ __('Subject') }}</label>
									<input type="text" class="form-control" required="" name="subject">

								</div>
							</div>

							<div class="row">
								<div class="form-group col-12">
									<label>{{ __('Message') }}</label>
									<textarea class="form-control" required="" name="content" id="content"></textarea>
								</div>
							</div>
							<button class="btn btn-success basicbtn" type="submit">{{ __('Send') }}</button>
						</div>

					</form>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-12 col-md-12">
				<div class="card">
					<div class="card-header">
						<h4>{{ __('Plan Purchase History') }}</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover text-center table-borderless">
								<thead>
									<tr>


										<th>{{ __('Invoice No') }}</th>
										<th>{{ __('Plan Name') }}</th>
										<th>{{ __('Price') }}</th>
										<th>{{ __('Payment Method') }}</th>
										<th>{{ __('Payment Id') }}</th>

										<th>{{ __('Created at') }}</th>
										<th>{{ __('View') }}</th>
									</tr>
								</thead>
								<tbody>
									@foreach($histories as $row)

									<tr>
										<td><a href="{{ route('partner.order.show_old',$row->id) }}">{{ $row->order_no }}</a></td>
										<td>{{ $row->plan->name }}</td>
										<td>{{ amount_format($row->amount) }}</td>
										<td>{{ $row->PaymentMethod->name ?? '' }}</td>
										<td>{{ $row->trx ?? '' }}</td>
										<td>{{ $row->created_at->format('d-M-Y') }}</td>
										<td><a href="{{ route('partner.order.show_old',$row->id) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a></td>
									</tr>

									@endforeach
								</tbody>

							</table>
							{{ $histories->links('vendor.pagination.bootstrap-4') }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>
@endsection
@push('js')
<script type="text/javascript" src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/form.js?v=1.0') }}"></script>
@endpush