//初始化一个全局变量
$(function(){
	window.tbhelper_display_ui_toolbar_buttons = {
		del : $('table.jquerytheme>thead span:has(input[src$="remove.png"])'),
		add : $('table.jquerytheme>thead span:has(input[src$="add.png"])'),
		edit : $('table.jquerytheme>thead span:has(input[src$="edit.png"])'),
		view : $('table.jquerytheme>thead span:has(input[src$="view.png"])'),
		esc : $('table.jquerytheme>thead span:has(input[src$="converse.png"])'),
		z : $('table.jquerytheme>thead span:has(input[src$="orderby.png"])')
	};
	window.tbhelper_display_ui_tab = $("table.jquerytheme");
	window.tbhelper_display_ui_has_pk = !!tbhelper_display_ui_tab.find("thead>tr:last>th").eq(0).find("span[id=tbhelper-thead-pk]").length;
	
	window.addPrimaryKey = function(u,pks){
		var cur = u;
		var url = cur.split("?");
		if(url.length === 1){
			return cur+"?pk="+pks.join();
		}else{
			var args = url[1].split("&");
			var _t = {};
			for(var i=0;i<args.length;i++){
				var _m = args[i].split("=");
				_t[_m[0]] = _m[1];	
			}
			_t['pk'] = pks.join();
			return (url[0] + '?' + $.param(_t));
		}
		
	}
	
	window.focusInSearchInput = false;
});

//美化表格
$(function(){
	function againstWhite(color) {
		if(color.toLowerCase() === "rgb(255, 255, 255)" || color.toLowerCase() === "#ffffff"){
			return "black";
		}
		return color;
	}
	
	var tbodyrowintersectcolor = "#f0f0f0";
	
	
	
	var tab = $("table.jquerytheme").css({
		"border":"1px solid "+JqueryTheme["ui-state-default"]["border"]	
	});
	//JqueryTheme
	tab.find("caption>h1").css({
		"color":"black"//JqueryTheme["ui-state-default"]["color"]
		,"font":"bold 20px/30px 宋体"
		,"border":0
	});
	
	var thead = tab.css({
		"width":"95%"
		,"margin":"15px auto"	
	}).find("thead>tr>td")
	//.addClass("ui-state-error")
	.css({
		"font":"normal 13px/25px arial"
		,"padding":"5px 0px 5px 0px"
		,"color":againstWhite(JqueryTheme["ui-state-default"]["color"])
		,"border":"0px"
	});
	
	thead.find("input[type=image]")
	.css({
		"margin-left":15	
	});
	
	thead.find("form").css({
		"display"	:"inline-block"
		,"margin-left":40
	})
	.find(":input").css({
		"border":"1px solid "+JqueryTheme["ui-state-default"]["border"]	
		,"height":21	
		,"width":260	
		,"color":againstWhite(JqueryTheme["ui-state-default"]["color"])	
		,"margin":0	
		,"line-height":19	
		,"font":"normal 13px/19px arial"	
		,"background":"url('/model/privilege/template/images/search.png') no-repeat 99% center"	
		,"padding-left":5	
		,"padding-top":0	
		,"padding-bottom":0	
	}).focus(function () {
		this.value = this.value === this.defaultValue ? '' : this.value;
		$(this).css({
			//"color":JqueryTheme["ui-state-active"]["color"]	,
			//"border":"1px solid "+JqueryTheme["ui-state-default"]["border"]
		});
		focusInSearchInput = true;
	}).blur(function () {
		this.value = this.value !== '' ? this.value : this.defaultValue;
		$(this).css({
			//"color":JqueryTheme["ui-state-active"]["border"],	
			//"border":"1px solid "+JqueryTheme["ui-state-active"]["border"]
		});
		focusInSearchInput = false;
	}).parent()
	.find(":submit").hide();
	
	
	tab.find("thead>tr:last>th").addClass("ui-state-default")
	.css({
		"padding":5	
		,"font":"normal 14px/20px arial"	
	});
	
	tab.find("tbody td").css({
		"padding":5
		,"text-align":"center"	
		,"font":"normal 12px/20px arial"	
		
	});
	tab.find("tbody>tr:even")
	.css({
		"background":tbodyrowintersectcolor	
	});
	tab.find("tbody>tr:odd").css({
		"background":"#fff"	
	});


//2012-7-25 11:17:12 给TBODY的A加上CSS
	tab.find("a").css({
		"color":againstWhite(JqueryTheme["ui-state-default"]["color"]),
		"text-decoration":"none"
	});	
	
	
//divpage

	tab.find("tfoot td").css({
		"padding":5
		,"text-align":"right"	
		,"font":"normal 12px/20px arial"	
		
	});
	tab.find("tfoot .divpage").css({
		"display":"block"
		,"padding-right":20	
	}).find("a").css({
		"display":"inline-block"
		,"min-width":16	
		,"height":16	
		,"text-align":"center"	
		,"color":JqueryTheme["ui-state-active"]["color"]	
		,"text-decoration":"none"	
	});	
	
	tab.find("tfoot .divpage .total").css({
		"margin-right":40	
		,"color":'gray'
	});
	tab.find("tfoot .divpage input.go").css({
		"border":"1px solid "+JqueryTheme["ui-state-active"]["border"]	
		,"height":18	
		,"width":40	
		,"color":"#aaaaaa"	
		,"margin":0	
		,"line-height":18	
		,"font":"normal 13px/18px arial"	
	});
	
	
	
});


//初始化ID INPUT
$(function(){
	var tab = tbhelper_display_ui_tab;
	if(!tbhelper_display_ui_has_pk){
		return;
	}	
	tab.find("tbody>tr>td:nth-child(1)").html(function(i,html){
		$(this).html("<input type='checkbox' title='"+html+"' value='"+html+"'>");
	});
	
});


//绑定事件到反选按钮
$(function(){
	var tab = tbhelper_display_ui_tab;
	if(!tbhelper_display_ui_has_pk){
		return;
	}	
	var esc = tbhelper_display_ui_toolbar_buttons.esc;
	esc.click(function(i,e){
		tab.find("tbody>tr>td:nth-child(1)>input").html(function(i,html){
			this.checked = !this.checked;
		});
		return false;
	}).css({
		"cursor":"pointer"	
	});
	
});


//绑定事件到   排序   按钮
$(function(){
	var tab = tbhelper_display_ui_tab;
	if(!tbhelper_display_ui_has_pk){
		return;
	}	
	tbhelper_display_ui_toolbar_buttons.z.click(function(i,e){
		return false;
	}).css({
		"cursor":"pointer"	
	});
	
});


//绑定事件到   编辑   按钮
$(function(){
	var tab = tbhelper_display_ui_tab;
	if(!tbhelper_display_ui_has_pk){
		return;
	}	
	tbhelper_display_ui_toolbar_buttons.edit.click(function(i,e){
		var pks = [];
		tab.find("tbody>tr>td:nth-child(1)>input").html(function(i,html){
			if(this.checked){
				pks.push(this.value);
			};
		});
		if(pks.length)window.location = addPrimaryKey(tbhelper_display_ui_toolbar_buttons.edit.find("a").attr('href') ,pks);
		return false;
	}).css({
		"cursor":"pointer"	
	});
	
});


//绑定事件到   添加   按钮
$(function(){
	var tab = tbhelper_display_ui_tab;
	if(!tbhelper_display_ui_has_pk){
		return;
	}	
	tbhelper_display_ui_toolbar_buttons.add.click(function(i,e){
		window.location = tbhelper_display_ui_toolbar_buttons.add.find("a").attr('href');
		return false;
	}).css({
		"cursor":"pointer"	
	});
	
});



//绑定事件到   查看   按钮
$(function(){
	var tab = tbhelper_display_ui_tab;
	if(!tbhelper_display_ui_has_pk){
		return;
	}	
	tbhelper_display_ui_toolbar_buttons.view.click(function(i,e){
		var pks = [];
		tab.find("tbody>tr>td:nth-child(1)>input").html(function(i,html){
			if(this.checked){
				pks.push(this.value);
			};
		});
		if(pks.length)window.location = addPrimaryKey(tbhelper_display_ui_toolbar_buttons.view.find("a").attr('href'),pks);;
		return false;
	}).css({
		"cursor":"pointer"	
	});
	
});


//绑定事件到   删除   按钮
$(function(){
	var tab = tbhelper_display_ui_tab;
	if(!tbhelper_display_ui_has_pk){
		return;
	}	
	tbhelper_display_ui_toolbar_buttons.del.click(function(i,e){
		var pks = [];
		tab.find("tbody>tr>td:nth-child(1)>input").html(function(i,html){
			if(this.checked){
				pks.push(this.value);
			};
		});
		if(pks.length && confirm("真的要删除选中的("+pks.length+")项吗?"))window.location = addPrimaryKey(tbhelper_display_ui_toolbar_buttons.del.find("a").attr('href'),pks);;
		return false;
	}).css({
		"cursor":"pointer"	
	});
	
});