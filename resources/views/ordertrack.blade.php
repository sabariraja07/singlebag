@extends('singlebag::layouts.app') 
<style>
.root {
padding: 1rem;
border-radius: 5px;
box-shadow: 0 2rem 6rem rgba(0, 0, 0, 0.3);
}

figure {
display: flex;
}
figure img {
width: 8rem;
height: 8rem;
border-radius: 15%;
border: 1.5px solid #f05a00;
margin-right: 1.5rem;
padding:1rem;
}
figure figcaption {
display: flex;
flex-direction: column;
justify-content: space-evenly;
}
  
.order-track {
margin-top: 2rem;
padding: 0 1rem;
border-top: 1px dashed #2c3e50;
padding-top: 2.5rem;
display: flex;
flex-direction: column;
}
.order-track-step {
display: flex;
height: 7rem;
}
.order-track-step:last-child {
overflow: hidden;
height: 4rem;
}
.order-track-step:last-child .order-track-status span:last-of-type {
display: none;
}
.order-track-status {
margin-right: 1.5rem;
position: relative;
}
.order-track-status-dot {
display: block;
width: 2.2rem;
height: 2.2rem;
border-radius: 50%;
background: #f05a00;
}
.order-track-status-line {
display: block;
margin: 0 auto;
width: 2px;
height: 7rem;
background: #f05a00;
}
/* .order-track-text-stat {
font-size: 1.3rem;
font-weight: 500;
margin-bottom: 3px;
}
.order-track-text-sub {
font-size: 1rem;
font-weight: 300;
} */

.order-track {
transition: all .3s height 0.3s;
transform-origin: top center;
}
</style>
@section('content')
<main class="main pages">
	<div class="page-header breadcrumb-wrap">
		<div class="container">
		    <h1 class="mb-15">{{ __('Order Status')}}</h1>
			<div class="breadcrumb">
				<a href="{{ url('/') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>{{ __('Home') }}</a>
				<span></span>{{ __('Order Status') }} 
			</div>
		</div>
	</div>
@if($orders)
  <div class="root">
    <h4 style=" line-height: unset; ">Tracking Details</h4>
    <h4>{{$orders->order_no}}</h4>
      <div class="order-track">
        <div class="order-track-step">
          <div class="order-track-status">
            <span class="order-track-status-dot" style="background:green !important"></span>
            @if($orders->status == 'canceled')
              <span class="order-track-status-line" style="background:red !important"></span>
            @else
              <span class="order-track-status-line" style="background:green !important"></span>
            @endif
          </div>
          <div class="order-track-text">
            <p class="order-track-text-stat">Ordered</p>
            {{-- <span class="order-track-text-sub">{{$orders->created_at->format('d-m-Y H:i:s')}}</span> --}}
          </div>
        </div>
        <div class="order-track-step">
          <div class="order-track-status">
            @if($orders->status == 'canceled')
              <span class="order-track-status-dot" style="background:red !important"></span>
            @else
              <span class="order-track-status-dot" style="background:green !important"></span>
            @endif
            @if($orders->status == 'delivered' || $orders->status == 'completed')
              <span class="order-track-status-line" style="background:green !important"></span>
            @else
              <span class="order-track-status-line"></span>
            @endif
          </div>
          <div class="order-track-text">
            <p class="order-track-text-stat">
              @if ($orders->status == 'pending')
                  {{ __('Awaiting processing') }}
              @elseif($orders->status == 'processing')
                  {{ __('Processing') }}
              @elseif($orders->status == 'ready-for-pickup')
                  {{ __('Ready for pickup') }}
              @elseif($orders->status == 'delivered' || $orders->status == 'picked_up' || $orders->status == 'completed')
                  {{ __('Shipped') }}
              @elseif($orders->status == 'archived')
                  {{ __('Archived') }}
              @elseif($orders->status == 'canceled')
                  {{ __('Cancelled') }}

              @endif
            </p>
            {{-- <span class="order-track-text-sub">3rd November, 2019</span> --}}
          </div>
        </div>
        @if($orders->status != 'canceled')
        <div class="order-track-step">
          <div class="order-track-status">
            @if($orders->status == 'delivered' || $orders->status == 'completed')
              <span class="order-track-status-dot" style="background:green !important"></span>
              <span class="order-track-status-line" style="background:green !important"></span>
            @else
              <span class="order-track-status-dot"></span>
              <span class="order-track-status-line"></span>
            @endif
          </div>
          <div class="order-track-text">
            <p class="order-track-text-stat">Delivered</p>
          </div>
        </div>
        @endif
      </div>
  </div>
@else
  <div class="row" style=" padding: 50px; "><div class="col"><div data-v-53982344="" class="empty-page"><div data-v-53982344="" class="empty-container"><div data-v-53982344="" class="empty-icon"><img data-v-53982344="" src="assets/img/empty_cart.png" alt="" style=" display: block; margin: auto; height: 300px; "></div> <div data-v-53982344="" class="empty-header" style="color:red;text-align: center;">Order Not Found</div> <div data-v-53982344="" class="empty-message"></div></div></div></div></div>
@endif  
 
	</div>
</main> 	

@endsection	
