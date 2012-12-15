$(function(){
	$(document).keydown(function(e){
		if(focusInSearchInput === true)return;
			switch(e.keyCode){
				case 68:return tbhelper_display_ui_toolbar_buttons.del.click();
				case 65:return tbhelper_display_ui_toolbar_buttons.add.click();
				case 69:
				case 85:return tbhelper_display_ui_toolbar_buttons.edit.click();
				case 86:return tbhelper_display_ui_toolbar_buttons.view.click();
				case 27:return tbhelper_display_ui_toolbar_buttons.esc.click();
				case 90:return tbhelper_display_ui_toolbar_buttons.z.click();
			}


	});

});