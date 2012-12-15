var JqueryTheme = {};
$(function(){
	var temp = $("<div>test</div>").appendTo(document.body);
	function getcssbbc(clsname){
		temp.addClass(clsname);
		var bbc = {
			color:temp.css('color')
			,backgroundColor:temp.css('background-color')
			,backgroundImage:temp.css('background-image')
			,border:temp.css('border-left-color')
		};
		temp.removeClass(clsname);
		return bbc;
	}
	var clss = [
		"ui-state-default"
		,"ui-state-hover"
		,"ui-state-focus"
		,"ui-state-active"
		,"ui-state-highlight"
		,"ui-state-error"
	];
	for(var i = 0;i<clss.length;i++){
		JqueryTheme[clss[i]] = getcssbbc(clss[i])
	}
	temp.remove();
});