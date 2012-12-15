$(function(){

	$('form').submit(function(){
		var flag = true;
		$(":input",this).each(function(){
			if(this.value === ""){
				if(this.tagName.toLowerCase() !== "select"){
					if(!$(this).parent().parent().hasClass("tbhelper-null-row")){
						flag = false;	
						$(this).focus();
						return false;
					}					
				}

			}	
			
		});
		return flag;	
		
	});	
	
});