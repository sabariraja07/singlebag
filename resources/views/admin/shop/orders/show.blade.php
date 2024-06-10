@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=>'Order Details'])
@endsection
@section('content')
<section class="section">

	<div class="section-body">

		<div class="row">
			<div class="col-12 col-md-12">
				<div class="card">
					<div class="card-header">
						<h4>{{ __('Order Details') }}</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover text-center table-borderless">
								<thead>
									<tr>
										<th>{{ __('Product') }}</th>
										<th>{{ __('Tax') }}</th>
										<th>{{ __('Commision') }}</th>
										<th>{{ __('Amount') }}</th>
										<th>{{ __('Payment Method') }}</th>
										<th>{{ __('Shop Name') }}</th>
										<th>{{ __('Address') }}</th>
										<th>{{ __('Date') }}</th>
										<th>{{ __('Status') }}</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($info->order_item as $row)
									<tr>
										<td>{{ $row->product->title ?? '' }}</td>
										@php
										$find_order = App\Models\Order::where('id', $row->order_id)->first();
										$shop_find = App\Models\Shop::where('id',$find_order->shop_id)->first();
										$address = App\Models\OrderMeta::where('key', 'content')->where('order_id', $row->order_id)->first();
										$order_content = json_decode($address->value ?? '');
										@endphp
										<td>{{ $find_order->tax ? amount_format($find_order->tax) : '--' }}</td>
										<td>{{ $find_order->commission ? amount_format($find_order->commission) : '--' }}</td>
										<td>{{ amount_format($find_order->total ?? 0) }}</td>
										<td>{{ strtoupper($find_order->payment_method ?? '')  }}</td>
										<td>{{ ucfirst($shop_find->name ?? '') }}</td>
										<td>{{ $order_content->address ?? ''}}</td>
										<td>{{ $find_order->created_at->format('d-M-Y')  }}</td>
										<td>
											@if($find_order->status== 'completed') <span class="badge badge-success">{{ __('Completed') }}</span>
											@elseif($find_order->status== 'delivered') <span class="badge badge-primary">{{ __('Delivered') }}</span>
											@elseif($find_order->status== 'pending') <span class="badge bg-info text-dark">{{ __('Pending') }}</span>
											@elseif($find_order->status== 'processing') <span class="badge badge-warning">{{ __('Processing') }}</span>
											@elseif($find_order->status== 'ready-for-pickup') <span class="badge badge-light">{{ __('Ready for pickup') }}</span>
											@elseif($find_order->status== 'picked_up') <span class="badge badge-warning">{{ __('picked_up') }}</span>
											@elseif($find_order->status== 'archived') <span class="badge badge-success">{{ __('Archived') }}</span>
											@elseif($find_order->status== 'canceled') <span class="badge badge-danger">{{ __('Cancelled') }}</span>
											@endif
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

</section>
@endsection
@push('js')
<script type="text/javascript" src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/form.js?v=1.0') }}"></script>
@endpush