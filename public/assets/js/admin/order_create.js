"use strict";
function openModal(id) {
	$('#plan').val(id);
	get_plan_discount();
}

function get_plan_discount(){
	var btnhtml=$('.basicbtn').html();
	var plan_id = $('#plan').val();
	var id = $('#shop_id').val();
	$.ajax({
		type: 'GET',
		url: 'admin/store/' + id + '/plan-discount?plan_id=' + plan_id,
		dataType: 'json',
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function() {
			$('.basicbtn').attr('disabled','');
			$('.basicbtn').html('Please Wait....');
		},
		
		success: function(response){ 
			$('.basicbtn').removeAttr('disabled');
			$('.basicbtn').html(btnhtml);
			console.log(response);
			$('#paid_amount').val(response.amount);
			$('#discount_amount').val(response.discount);
		},
		error: function(xhr, status, error) 
		{
			$('.basicbtn').removeAttr('disabled');
			$('.basicbtn').html(btnhtml);
			Sweet('error',"Invalid data given.")
		}
	});
}
$('#notification_status').on('change',()=>{
	var val=$('#notification_status').val();
	if (val=="yes") {
		$('#email_content').show()
	}
	else{
		$('#email_content').hide()
	}
});