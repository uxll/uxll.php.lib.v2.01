$(function () {
	$(".topmenu span.ui-icon").css({
		"height":14
		//,"width":16
		
	});
	$(".topmenu").css({
		"border-left":0,
		"border-right":0
	});
	var menuStyle = "ui-state-active";
	var btnStyle = "ui-state-default";
	function btnHover(btn){
		var trl = "1px solid "+JqueryTheme[menuStyle]["border"];
		$(btn).addClass(menuStyle).css({
			"border-top":trl
			,"border-left":trl
			,"border-right":trl
			,"border-bottom":"1px solid "+JqueryTheme[menuStyle]["backgroundColor"]
		});
	}
	$("div.topmenu ul>li:has('a>span[id]')").addClass(btnStyle).css({
		"padding-left":"0px",
		"border":"1px solid transparent"
	}).each(function(i,e){
		
		var li = $(this);
		var id = li.find("a>span").attr('id');
		var p = li.offset();
		
		var menu = $("div."+id).css({
			"position":"absolute",
			"padding":0,
			"min-width":150
		}).addClass(menuStyle)
		.css({
			"background":JqueryTheme[menuStyle]["backgroundColor"]	
		})	
		;
		var w = menu.width();
		var offset = ($("div.topmenu ul>li:has('a>span[id]')").length-i-1)*1;
		
		
		//alert(p.left+li.width()-w);
		var liwidth = li.width();
		menu.css({
			top:Math.ceil(p.top)+li.height()-0,
			left:p.left+liwidth-w
			,"zIndex":40
		}).hover(function(){
			btnHover(li);
			$(this).show();
		},function(){
			$(this).hide();
			$(li).removeClass(menuStyle).css({"border":"1px solid transparent"});
		}).hide();		
		$(this).hover(function(){
			btnHover(li);
			menu.css({
				left:li.offset().left+liwidth-w
			});
			$("div."+id).show();	
		},function(){
			$(li).removeClass(menuStyle).css({"border":"1px solid transparent"});
			$("div."+id).hide();
		});
	});
});