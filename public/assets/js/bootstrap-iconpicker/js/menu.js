(function ($) {
	"use strict";
    // menu item
    var arrayjson = $('#arrayjson').val();
    // sortable list options
    var sortableListOptions = {
    	placeholderCss: {'background-color': "#cccccc"}
    };

    var editor = new MenuEditor('myEditor', {listOptions: sortableListOptions});
    editor.setForm($('#frmEdit'));
    editor.setUpdateButton($('#btnUpdate'));
    $('#btnReload').on('click', function () {
    	editor.setData(arrayjson);
    });

    $('#btnOutput').on('click', function () {
    	var str = editor.getString();
    	$("#out").text(str);
    });

    $(document).on('click',"#btnUpdate", function(){
    	if ($('#text').val() != '' && $('#href').val() != '') {
    		editor.update();
    	}	
    });

    $(document).on('click',"#btnAdd", function(){
        var menu_type= $("#menu_type");
        var menu_page = $("#href");
        var text = $("#text");

        if (text.val() == '') {
            Sweet('error','Please Enter Text!');
            return false;
        }

        if (menu_type.val() == null) {
            Sweet('error','Please Select Menu Type!');
            return false;
        }

        if (menu_page.val() == null) {
            Sweet('error','Please Select URL!');
            return false;
        }

        if (menu_page.val() == '') {
            Sweet('error','Please Select URL!');
            return false;
        }
     
    	if ($('#text').val() != '' && $('#href').val() != '') {
    		editor.add();
            $(".btnEdit").hide();
    	}
    	
    });

    $('#form-button').on('click',function(){
    	$("#data").val(editor.getString());
    })
    editor.setData(arrayjson);
})(jQuery);	