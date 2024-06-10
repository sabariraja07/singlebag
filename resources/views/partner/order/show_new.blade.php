@extends('layouts.seller')
@section('title', 'Subscription Details')
@section('head')
@include('layouts.partials.headersection',['title'=>'Order No: '. $info->order_no])
@endsection
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">{{ __('Subscription Details') }}</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/seller/dashboard') }}">{{ __('Home') }}</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ url('seller/settings/subscriptions') }}">{{ __('Subscriptions') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('Subscription Details') }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
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
							<td>{{ __('ORDER NO') }}</td>
							<td><b>{{ $info->order_no }}</b></td>
						</tr>
						<tr>
							<td>{{ __('Order Status') }}</td>
							<td>@if($info->status == 1) <span class="badge rounded-pill badge-light-success">{{ __('Approved') }}</span> @elseif($info->status == 2) <span class="badge rounded-pill badge-light-warning">{{ __('Pending') }}</span>@elseif($info->status == 3) <span class="badge rounded-pill badge-light-danger">{{ __('Expired') }}</span>@else <span class="badge rounded-pill badge-light-danger">{{ __('Cancelled') }}</span> @endif</td>
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
							<td><b>{{ amount_admin_format($info->amount) }}</b></td>
						</tr>
						<tr>
							<td>{{ __('Order Discount') }}</td>
							<td><b>{{ amount_admin_format($info->discount) }}</b></td>
						</tr>
						<tr>
							<td>{{ __('Order Tax Amount') }}</td>
							<td><b>{{ amount_admin_format($info->tax) }}</b></td>
						</tr>
						<tr>
							<td>{{ __('Plan Amount') }}</td>
							<td><b>{{ amount_admin_format($info->amount + $info->discount - $info->tax ) }}</b></td>
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
								<span class="badge rounded-pill badge-light-success">{{ __('Paid') }}</span>
								@elseif($info->payment_status == 2)
								<span class="badge rounded-pill badge-light-warning">{{ __('Pending') }}</span>
								@else
								<span class="badge rounded-pill badge-light-danger">{{ __('Fail') }}</span>
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
							<td>{{ __('Store Name') }}</td>
							<td><b>{{ $shop->name ?? '' }}</b></td>
						</tr>
						<tr>
							<td>{{ __('Store Email') }}</td>
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
								
								@if($shop->domain->status==1) <span class="badge rounded-pill badge-light-success">{{ __('Active') }}</span>
								@elseif($shop->domain->status==0) <span class="badge rounded-pill badge-light-danger">{{ __('Trash') }}</span>
								@elseif($shop->domain->status==2) <span class="badge rounded-pill badge-light-warning">{{ __('Draft') }}</span>
								@elseif($shop->domain->status==3) <span class="badge rounded-pill badge-light-warning">{{ __('Requested') }}</span>
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