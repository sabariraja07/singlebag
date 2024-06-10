var dataId='';
(function ($) {
	"use strict";	
	$('.option').on('change',function(e){
		var id = $(this).attr('data-productid');;
		var mainprice = parseFloat($('#base-price-' + id).val());
		var total_price = mainprice;
		$( '.option-' + id + ' option:selected').each(function () {
			var price= $(this).attr('data-price');
			var price=parseFloat(price);
			var price_type= $(this).attr('data-amounttype');

			if(price_type == 0){
				total_price +=  mainprice * price / 100;
			}
			else if(price_type == 1 ){
				total_price += price;
			}
			
		});
		$('#price'+id).html(total_price.toFixed(2));

	});

	$(".basicform").on('submit', function(e){
		e.preventDefault();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		var basicbtnhtml=$('#submitbtn'+dataId).html();
		// var inputs = $(this).find(':input[required]');

		var valid = true;
		$('.option-' + dataId + ':input[required]').each( function() {
			if(($(this).val() == '') || ($(this).val() == null)){
		      valid = false;
			}
			
		});
		if(valid == false){
			Sweet('error', 'Please check required options.' );
			return;
		}
		var formData = new FormData(this);
		// formData.append('option[]', $('.option-' + dataId).val());
		$.ajax({
			type: 'POST',
			url: this.action,
			data: formData ,
			dataType: 'json',
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function() {

				$('#submitbtn'+dataId).html('<i class="spinner-border"></i>');
				$('#submitbtn'+dataId).attr('disabled','')

			},

			success: function(response){ 
				$('#submitbtn'+dataId).removeAttr('disabled')
				Sweet('success','Cart Item Added');
				$('#submitbtn'+dataId).html(basicbtnhtml);

				$('#cart_count').html(response.count);
				render_cart_item(response.items);
				location.reload();
			},
			error: function(xhr, status, error) 
			{
				$('#submitbtn'+dataId).html(basicbtnhtml);

				$.each(xhr.responseJSON.errors, function (key, item) 
				{
					Sweet('error',item)
					$("#errors").html("<li class='text-danger'>"+item+"</li>")
				});

			}
		})


	});

	$(".cartform").on('submit', function(e){
		e.preventDefault();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			type: 'get',
			url: this.action,
			data: new FormData(this),
			dataType: 'json',


			success: function(response){ 

				Sweet('success','Cart Item Added');


				$('#cart_count').html(response.count);
				render_cart_item(response.items);
			},

		})
	});
})(jQuery);	

function assignId(id){
	dataId=id;
}

		
function render_cart_item(data) {
	
	var url = $('#removecart_url').val();
			
	$('.cart-row').remove();
	$.each(data, function(index, row){
		
		var r_id=row.id;
		var delete_url=url+'/'+r_id;
		var html='<tr class="cart-row cart'+row.id+'">';
		    html +='<td><img src="'+row.attributes.image+'" height="50"></td>';
			html +='<td>'+row.name+'</td><td>'+row.price+'</td><td>'+row.quantity+'</td>';
			html +='<td class="text-right"><a href="'+delete_url+'" class="btn btn-danger btn-sm remove_btn"><i class="ph-trash"></i></a></td></tr>';
		$('#cart-content').append(html);
	});
}