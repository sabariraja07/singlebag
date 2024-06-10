@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Order No: '. $info->order_no])
@endsection
@section('content')
<div class="row">
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header">
				<h4>{{ __('Order Information') }}</h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<tr>
							<td>{{ __('Order No') }}</td>
							<td><b>{{ $info->order_no }}</b></td>
						</tr>
						<tr>
							<td>{{ __('Order Status') }}</td>
							<td>
								@if($info->status == 1) 
								<span class="badge badge-success">{{ __('Approved') }}</span> 
								@elseif($info->status == 2) 
								<span class="badge badge-warning">{{ __('Pending') }}</span>
								@elseif($info->status == 3) 
								<span class="badge badge-danger">{{ __('Expired') }}</span>
								@else 
								<span class="badge badge-danger">{{ __('Cancelled') }}</span> 
								@endif
							</td>
						</tr>
						<tr>
							<td>{{ __('Order Created Date') }}</td>
							<td><b>{{ $info->created_at->format('Y-m-d') }}</b></td>
						</tr>
						<tr>
							<td>{{ __('Order Created At') }}</td>
							<td><b>{{ $info->created_at->diffForHumans() }}</b></td>
						</tr>
						<tr>
							<td>{{ __('Order Will Be Expired') }}</td>
							<td><b>{{ $info->will_expire }}</b></td>
						</tr>
						<tr>
							<td>{{ __('Order Amount') }}</td>
							<td><b>{{ amount_format($info->amount) }}</b></td>
						</tr>
						<tr>
							<td>{{ __('Order Tax Amount') }}</td>
							<td><b>{{ amount_format($info->tax) }}</b></td>
						</tr>
						<tr>
							<td>{{ __('Order Discount Amount') }}</td>
							<td><b>{{ amount_format($info->discount) }}</b></td>
							
						</tr>
						<tr>
							<td>{{ __('Order Commission') }}</td>
							<td><b>{{ amount_format($info->commission) }}</b></td>
						</tr>
						<tr>
							<td>{{ __('Plan Amount') }}</td>
							<td><b>{{ amount_format( $info->amount + $info->discount - $info->tax ) }}</b></td>
						</tr>
						<tr>
							<td>{{ __('Plan Name') }}</td>
							<td><b>{{ $info->plan_info->name ?? '' }}</b></td>
						</tr>
						<tr>
							<td>{{ __('Payment Mode') }}</td>
							<td><b>{{ $info->PaymentMethod->name ?? '' }}</b></td>
						</tr>
						<tr>
							<td>{{ __('Transaction Id') }}</td>
							<td><b>{{ $info->trx }}</b></td>
						</tr>
						<tr>
							<td>{{ __('Transaction Status') }}</td>
							<td>
								
								@if($info->payment_status==1)
								<span class="badge badge-success">{{ __('Paid') }}</span>
								@elseif($info->payment_status == 2)
								<span class="badge badge-warning">{{ __('Pending') }}</span>
								@else
								<span class="badge badge-danger">{{ __('Fail') }}</span>
								@endif
							</td>
						</tr>
						
						
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header">
				<h4>{{ __('Shop Information') }}</h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-hover">
						<tr>
							<td>{{ __('Shop Name') }}</td>
							<td><b><a href="{{ route('admin.shop.show',$shop->id) }}">{{ $shop->name ?? '' }}</a></b></td>
						</tr>
						<tr>
							<td>{{ __('Shop Email') }}</td>
							<td><a href="mailto:{{ $shop->user->email ?? '' }}"><b>{{ $shop->user->email ?? '' }}</b></a></td>
						</tr>

						@php 
						$link=$shop->domain->full_domain;
						@endphp
						<tr>
							<td>{{ __('Store Domain') }}</td>
							<td><b><a href="<?= $link; ?>" target="_blank">{{ $shop->domain->domain}}</a></b></td>
						</tr>
						<tr>
							<td>{{ __('Domain Status') }}</td>
							<td>@if(!empty($shop->domain))<b>
								
								@if($shop->domain->status==1) <span class="badge badge-success">{{ __('Active') }}</span>
								@elseif($shop->domain->status==0) <span class="badge badge-danger">{{ __('Trash') }}</span>
								@elseif($shop->domain->status==2) <span class="badge badge-warning">{{ __('Draft') }}</span>
								@elseif($shop->domain->status==3) <span class="badge badge-warning">{{ __('Requested') }}</span>
								@endif
							</b>@endif</td>
						</tr>
						
					</table>
				</div>   		
			</div>
		</div>
	</div>
</div>
@endsection