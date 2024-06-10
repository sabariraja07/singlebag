(function ($) {
	"use strict";

	$('.add_attr').on('click',function(){
		var html=$('.attrs_row').html();
		var num=Math.floor(Math.random() * 99) + 1;  
		$('#data-body').append('<tr class="attr_'+num+'"><td><select  data-id="'+num+'" class="form-control parent_attr"><option value="" disabled selected required>Select Variant</option>'+html+'</select></td><td><select name="child[]"  class="multi-select form-control child'+num+'" width="100%" required></select></td><td><a data-id="'+num+'" class="btn btn-danger remove_attr text-white"><i class="ph-trash"></i></a></td></tr>');
	
	});	

	$('.add_attr_mobile_view').on('click',function(){
		var html=$('.attrs_row').html();
		var num=Math.floor(Math.random() * 99) + 1;  
		$('#data-body-mobile-view').append('<div class="col-12 attr_'+num+'"><div class="card"><div style="padding-left: 0.5rem !important; padding-right: 0.5rem !important;"> <div class="attr_{{ $i }}"> <div class="mb-1"> <br><b><label class="mb-1">Attributes</label></b><div class="attr_'+num+'"></div></div><div class="mb-1"><select  data-id="'+num+'" class="form-control parent_attr" required><option value="" disabled selected>Select Variant</option>'+html+'</select></div><div class="mb-1"><b> <label class="mb-1">Values</label></b><div class="mb-1"><select name="child[]"  class="multi-select form-control child'+num+'" required width="100%"></select></div><div><a data-id="'+num+'" class="btn btn-danger remove_attr text-white"><i class="ph-trash"></i></a></div></div>');
	});	
	
	$(document).on('change','.parent_attr',function(){
		var variations=$('option:selected', this).attr('data-parentattribute');
		var variations=JSON.parse(variations);
		var id= $(this).attr('data-id');
		var value= $(this).val();

		$('.attr'+id).remove();
		$('.child'+id).attr('multiple','');
		$('.child'+id).attr('name','child['+value+'][]');
		$.each(variations, function (key, item) 
		{
			var html="<option value="+item.id+"  class='attr"+id+"'>"+item.name+"</option>";
			$('.child'+id).append(html);
		});
		$('.child'+id).val('');
		$('.multi-select').select2()
	});
	
	$(document).on('click','.remove_attr',function(){
		var id= $(this).data('id');
		$('.attr_'+id).remove();
	});

})(jQuery);
