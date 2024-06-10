@extends('layouts.seller')
@section('content')
<div class="content-wrapper container-md p-0 ecommerce-application">
	<div class="content-header row">
		<div class="content-header-left col-md-9 col-12 mb-2">
			<div class="row breadcrumbs-top">
				<div class="col-12">
					<h2 class="content-header-title float-start mb-0">{{ __('Delivery Agent') }}</h2>
					<div class="breadcrumb-wrapper">
						<ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/seller/dashboard">{{ __('Home') }}</a>
							<li class="breadcrumb-item"><a href="/seller/orders/all">{{ __('Orders') }}</a>
                            </li>
                            <li class="breadcrumb-item active">
								{{ __('Delivery Agent') }}
                            </li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
		</div>
	</div>
	<div class="content-body">
    <div class="row" id="order">
        <div class="col-12 col-lg-8">

            <div class="card card-info">
                <form class="basicform" action="{{ route('seller.orders.agent-update', $info->id) }}" method="post">
                    @csrf
                    @method('POST')
                <div class="card-body">


                    <div class="form-group">
                        <label>Assign Delivery Agent</label>

                        <select name="agents" id="agents" class="form-control agents">
                            
                        </select>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit"
                        class="btn btn-success float-right mt-2 ml-2 basicbtn">{{ __('Submit') }}</button>

                </div>
            </form>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card-grouping">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Status') }}</h4>

                    </div>
                    <div class="card-body">

                        <p>{{ __('Payment Status') }}
                            @if ($info->payment_status == 2)
                                <span class="badge badge-warning float-right">{{ __('Pending') }}</span>

                            @elseif($info->payment_status == 1)
                                <span class="badge badge-success float-right">{{ __('Paid') }}</span>

                            @elseif($info->payment_status == 0)
                                <span class="badge badge-danger float-right">{{ __('Cancel') }}</span>
                            @elseif($info->payment_status == 3)
                                <span class="badge badge-danger float-right">{{ __('Incomplete') }}</span>
                            @endif
                        </p>


                        <p>{{ __('Order Status') }} @if ($info->status == 'pending')
                                <span class="badge badge-warning float-right">{{ __('Awaiting processing') }}</span>

                            @elseif($info->status == 'processing')
                                <span class="badge badge-primary float-right">{{ __('Processing') }}</span>

                            @elseif($info->status == 'ready-for-pickup')
                                <span class="badge badge-info float-right">{{ __('Ready for pickup') }}</span>

                            @elseif($info->status == 'completed')
                                <span class="badge badge-success float-right">{{ __('Completed') }}</span>

                            @elseif($info->status == 'archived')
                                <span class="badge badge-danger float-right">{{ __('Archived') }}</span>
                            @elseif($info->status == 'canceled')
                                <span class="badge badge-danger float-right">{{ __('Canceled') }}</span>

                            @else
                                <span class="badge badge-primary float-right">{{ $info->status }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Payment Mode') }}</h4>

                    </div>
                    <div class="card-body">
                        @if ($info->payment_method != null)
                            <p>{{ __('Transaction Method') }} <span
                                    class="badge  badge-success  float-right">{{ $info->PaymentMethod->name ?? '' }} </span>
                            </p>
                            <p>{{ __('Transaction Id') }} <span
                                    class="float-right">{{ $info->transaction_id ?? '' }}</span></p>
                        @else
                            <p>{{ __('Incomplete Payment') }}</p>
                        @endif
                    </div>
                </div>
                @if(isset($order_content->comment))
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-header-title">{{ __('Note') }}</h4>

                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order_content->comment }}</p>
                    </div>
                </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-header-title">{{ __('Customer') }}</h4>
                    </div>
                    <div class="card-body">
                        @if ($info->customer != null)
                            <a href="{{ route('seller.customer.show', $info->customer->id) }}">{{ $info->customer->fullname }}
                                (#{{ $info->customer->id }})</a>
                        @else
                            {{ __('Guest Customer') }}
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-header-title">{{ __('Shipping details') }}</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-responsive" id="shipping-details-table">
                            <tr>
                                <td>{{ __('Customer Name') }}:</td>
                                <td>{{ $order_content->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Customer Email') }}:</td>
                                <td>{{ $order_content->email ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Customer Phone') }}:</td>
                                <td>{{ $order_content->phone ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Location') }}:</td>
                                <td>{{ $info->shipping_info->city->name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Zip Code') }}:</td>
                                <td>{{ $order_content->zip_code ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Address') }}:</td>
                                <td>{{ $order_content->address ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Shipping Method') }}:</td>
                                <td>{{ $info->shipping_info->shipping_method->name ?? '' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>


    </div>
    </div>
</div>
@endsection
@section('page-script')
    <script src="{{ asset('assets/js/form.js') }}"></script>
@endsection

