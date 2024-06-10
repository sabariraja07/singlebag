<div class="card">
    <div class="card-body">
        <label class="section-label form-label mb-1">Options</label>

        {{-- <form class="apply_coupon_form" method="post" action="{{ route('seller.orders.apply_coupon') }}">
            @csrf
            <div class="coupons input-group input-group-merge">
                <input type="text" class="form-control" placeholder="Promo code" required="" name="code">
                <span class="input-group-text text-primary ps-1 redeembtn" type="submit" id="input-coupons">{{ __('Redeem') }}</span>
            </div>
        </form> --}}
        <hr>
        <div class="price-details">
            <h6 class="price-title">Price Details</h6>
            <ul class="list-unstyled">
                <li class="price-detail">
                    <div class="detail-title">Total MRP</div>
                    <div class="detail-amt">$598</div>
                </li>
                <li class="price-detail">
                    <div class="detail-title">Bag Discount</div>
                    <div class="detail-amt discount-amt text-success">-25$</div>
                </li>
                <li class="price-detail">
                    <div class="detail-title">Estimated Tax</div>
                    <div class="detail-amt">$1.3</div>
                </li>
                <li class="price-detail">
                    <div class="detail-title">EMI Eligibility</div>
                    <a href="#" class="detail-amt text-primary">Details</a>
                </li>
                <li class="price-detail">
                    <div class="detail-title">Delivery Charges</div>
                    <div class="detail-amt discount-amt text-success">Free</div>
                </li>
            </ul>
            <hr>
            <ul class="list-unstyled">
                <li class="price-detail">
                    <div class="detail-title detail-total">Total</div>
                    <div class="detail-amt fw-bolder">$574</div>
                </li>
            </ul>
            <button type="button" class="btn btn-primary w-100 btn-next place-order waves-effect waves-float waves-light">Place Order</button>
        </div>
    </div>
</div>
@section('page-script')
<script>
	$(".apply_coupon_form").on('submit', function(e){
		e.preventDefault();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var basicbtnhtml=$('.redeembtn').html();
		$.ajax({
			type: 'POST',
			url: this.action,
			data: new FormData(this),
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function() {
       			
       			$('.redeembtn').html("Please Wait....");
       			$('.redeembtn').attr('disabled','')

    		},
			
			success: function(response){ 
				$('.redeembtn').removeAttr('disabled')
				Sweet('success',response.message);
				$('.redeembtn').html(basicbtnhtml);
				console.log(response);
				$('#cart-details').html(response.data);
			},
			error: function(xhr, status, error) 
			{
				$('.redeembtn').html(basicbtnhtml);
				$('.redeembtn').removeAttr('disabled')
				$('.errorarea').show();
				$.each(xhr.responseJSON.errors, function (key, item) 
				{
					Sweet('error',item)
					$("#errors").html("<li class='text-danger'>"+item+"</li>")
				});
				errosresponse(xhr, status, error);
			}
		})


	});
</script>

@endsection