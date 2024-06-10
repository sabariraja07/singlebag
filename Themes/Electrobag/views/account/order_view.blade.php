@extends('electrobag::account.layout.app')
@section('account_breadcrumb')
<a href="{{ url('/user/orders') }}">{{ __('Orders') }}</a>
<a href="{{ url()->current() }}">{{ __('Order Information') }}</a>
@endsection

@section('account_title', __('Order Information') )
@section('user_content')
<div class="section-body">
    <div class="invoice">
        <div class="invoice-print">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <div class="h2">{{ __('Order Information') }}</div>
                        <div class="title-underline center"><span></span></div>
                    </div>
                    <div class="invoice-title d-flex justify-content-between align-items-center">

                        <div class="invoice-number"><strong>{{ __('Order Id') }}:</strong> {{ $info->order_no }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        @if ($info->order_type == 1)
                        <div class="col-md-6">

                            <div class="simple-article size-3">
                                <strong>{{ __('Shipped To') }}:</strong><br>
                                {{ $order_content->address ?? '' }}<br>
                                {{ __('City') }}: {{ $info->shipping_info->city->name ?? '' }}
                                <br>
                                {{ __('Postal Code') }}: {{ $order_content->zip_code ?? '' }}
                                <br>
                                {{-- {{ __('Address') }}: {{ $order_content->address ?? '' }} --}}
                            </div>

                        </div>
                        @endif
                        @if ($info->order_type == 1)
                        <div class="col-md-6 text-right">
                            @else
                            <div class="col-md-12 text-right">
                                @endif
                                <div class="simple-article size-3">
                                    <strong>{{ __('Order Status') }}:</strong>
                                    @if ($info->status == 'pending')
                                    <span class="badge rounded-pill bg-warning ">{{ __('Awaiting processing') }}</span>
                                    @elseif($info->status == 'processing')
                                    <span class="badge rounded-pill bg-primary ">{{ __('Processing') }}</span>
                                    @elseif($info->status == 'ready-for-pickup')
                                    <span class="badge rounded-pill bg-info ">{{ __('Ready for pickup') }}</span>
                                    @elseif($info->status == 'completed')
                                    <span class="badge rounded-pill bg-success ">{{ __('Completed') }}</span>
                                    @elseif($info->status == 'archived')
                                    <span class="badge rounded-pill bg-danger ">{{ __('Archived') }}</span>
                                    @elseif($info->status == 'canceled')
                                    <span class="badge rounded-pill bg-danger ">{{ __('Canceled') }}</span>
                                    @else
                                    <span class="badge rounded-pill bg-primary ">{{ $info->status }}</span>
                                    @endif
                                </div><br>
                                <div class="simple-article size-3">
                                    <strong>{{ __('Payment Status') }}:</strong>


                                    @if ($info->payment_status == 2)
                                    <span class="badge rounded-pill bg-warning ">{{ __('Pending') }}</span>
                                    @elseif($info->payment_status == 1)
                                    <span class="badge rounded-pill bg-success ">{{ __('Paid') }}</span>
                                    @elseif($info->payment_status == 0)
                                    <span class="badge rounded-pill bg-danger ">{{ __('Cancel') }}</span>
                                    @elseif($info->payment_status == 3)
                                    <span class="badge rounded-pill bg-danger ">{{ __('Incomplete') }}</span>
                                    @endif
                                </div><br>
                                @php
								    $shipping_method = $info->shipping_info ? $info->shipping_info->method->title : '';
                                    $estimate_date = $info->shipping_info ? $info->shipping_info->method->estimated_delivery : '';
							    @endphp
                                <div class="simple-article size-3">
                                    <strong>{{ __('Shipping Method') }}:</strong>
                                    <span class="badge rounded-pill bg-success ">{{ $shipping_method.'('.$estimate_date. ')'  }}</span>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="simple-article size-3">
                                    <strong>{{ __('Payment Info') }}:</strong>

                                    <p>{{ __('Payment Method') }} : <b>{{ $info->PaymentMethod->name ?? '' }}</b></p>
                                    <p>{{ __('Transaction Id') }} : <b>{{ $info->transaction_id }}</b></p>

                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="simple-article size-3">

                                    <strong>{{ __('Order Date') }}:</strong>

                                    {{ $info->created_at->format('d-F-Y') }}<br><br>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <br /><br />
                        <div class="h4" style="margin-bottom:30px;">{{ __('Order Summary') }}</div>


                        <div class="table-responsive">
                            <table class="cart-table">
                                <tbody>
                                    <tr>

                                        <th>{{ __('Name') }}</th>

                                        <th class="text-center">{{ __('Amount') }}</th>

                                        <th class="text-right">{{ __('Total') }}</th>

                                    </tr>
                                    @foreach ($info->order_item as $row)
                                    <tr>
                                        <td>
                                            @if($row->term->status == '1')
                                                {{ Str::limit($row->term->title,50) ?? '' }}
                                            @else
                                                <a class="unpublished" @click="Sweet('error','This Product is no longer available in this site.')">{{ Str::limit($row->term->title,50) ?? '' }}</a>
                                            @endif
                                            <br>
                                            @php
                                            $attributes = $row->info;
                                            @endphp
                                            @foreach ($attributes->options ?? [] as $option)
                                            <span>{{ __('Option') }} :</span>
                                            <small>{{ $option->name ?? '' }}</small>,
                                            @endforeach
                                            @if ($info->status == 'completed' && $info->payment_status == 1)
                                            <br>
                                            @foreach ($row->file ?? [] as $file)
                                            <a href="{{ url($file->url) }}" target="_blank">{{ __('Download') }}</a>
                                            @endforeach
                                            @endif
                                        </td>
                                        <td class="text-center">{{ amount_format($row->amount) }} × {{ $row->qty }}
                                        </td>

                                        <td class="text-right">{{ amount_format($row->amount * $row->qty) }}</td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-8">
                            </div>
                            <div class="col-lg-4 text-right">
                                <div class="order-details-entry simple-article size-3 grey uppercase">
                                    <div class="row">
                                        <div class="col-xs-6">{{ __('Subtotal') }}:</div>
                                        <div class="col-xs-6 col-xs-text-right">
                                            <div class="color">
                                                {{ amount_format($order_content->sub_total + $order_content->coupon_discount ?? 0) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="order-details-entry simple-article size-3 grey uppercase">
                                    <div class="row">
                                        <div class="col-xs-6">{{ __('Discount') }}:</div>
                                        <div class="col-xs-6 col-xs-text-right">
                                            <div class="color">{{ amount_format($order_content->coupon_discount ?? 0) }}
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="order-details-entry simple-article size-3 grey uppercase">
                                    <div class="row">
                                        <div class="col-xs-6">{{ __('Tax') }}:</div>
                                        <div class="col-xs-6 col-xs-text-right">
                                            <div class="color">{{ amount_format($info->tax) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="order-details-entry simple-article size-3 grey uppercase">
                                    <div class="row">
                                        <div class="col-xs-6">{{ __('Shipping') }}:</div>
                                        <div class="col-xs-6 col-xs-text-right">
                                            <div class="color">{{ amount_format($info->shipping) }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="order-details-entry simple-article size-3 grey uppercase">
                                    <div class="row">
                                        <div class="col-xs-6">{{ __('Total') }}:</div>
                                        <div class="col-xs-6 col-xs-text-right">
                                            <div class="color">{{ amount_format($info->total) }}</div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
<script>
function Sweet(icon, title, time = 3000) {

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: time,
  timerProgressBar: true,
  onOpen: (toast) => {
	toast.addEventListener('mouseenter', Swal.stopTimer)
	toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})


Toast.fire({
  icon: icon,
  title: title,
})
}


function SweetAudio(icon, title, time = 3000, audio = "") {

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: time,
  timerProgressBar: true,
  onOpen: (toast) => {
	toast.addEventListener('mouseenter', Swal.stopTimer)
	toast.addEventListener('mouseleave', Swal.resumeTimer)
	var aud = new Audio(audio)
	aud.play();
  }
})


Toast.fire({
  icon: icon,
  title: title,
})
}
</script>
@endpush